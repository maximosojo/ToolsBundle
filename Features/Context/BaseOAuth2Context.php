<?php

/*
 * This file is part of the Máximo Sojo - maximosojo package.
 * 
 * (c) https://maximosojo.github.io/
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Exception;
use LogicException;
use Symfony\Component\HttpFoundation\Response;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Maximosojo\ToolsBundle\Features\Context\BaseDataContext;
use Maximosojo\ToolsBundle\Features\Context\TraitContext;

/**
 * Base para peticiones oauth2
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class BaseOAuth2Context implements Context
{
    use TraitContext;

    /**
     *
     * @var BaseDataContext
     */
    protected $dataContext;
    
    protected $serverParameters;
    protected $lastRequestBody;
    protected $parameters;
    protected $requestFiles;

    /**
     * Headers adicionales
     * @var array
     */
    protected $requestHeaders;

    /**
     *
     * @var Response
     */
    protected $response;

    protected $kernel;
    
    /**
     * Localizador de archivos en %kernel.project_dir%/Resources
     * @var FileLocator
     */
    protected $fileLocator;
    
    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    protected $accessor;

    public function __construct(FileLocator $fileLocator)
    {
        $this->fileLocator = $fileLocator;
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    public function setDataContext(BaseDataContext $dataContext)
    {
        $this->dataContext = $dataContext;
        $this->dataContext->setOAuth2Context($this);
        $this->kernel = $this->dataContext->getKernel();
        return $this;
    }

    /**
     * Se loguea con un usuario en la api
     * @Given I am logged in api as oauth2 :username
     * @Given I am logged in api as oauth2 :username and :password
     */
    public function iAmLoggedInApiAsOAuth2($username, $usePassword = null)
    {
        $this->dataContext->setParameters($this->parameters);
        $this->dataContext->flush();
        $this->iCreateOAuth2Request();
        if ($usePassword !== null) {
            $password = $usePassword;
        } else {
            $password = $this->dataContext->getPassword($username);
        }

        $this->dataContext->setRequestBody("grant_type", "password");
        $this->dataContext->setRequestBody("username", $username);
        $this->dataContext->setRequestBody("password", $password);

        $this->dataContext->iMakeAAccessTokenRequest();
        if ($usePassword === null) {
            $this->dataContext->theResponseStatusCodeIs("200");
        }
        if ((string) $this->dataContext->getResponse()->getStatusCode() === "200") {
            $token = $this->getPropertyValue("access_token");
            $this->dataContext->getClient()->setServerParameter("HTTP_AUTHORIZATION", sprintf("Bearer %s", $token));
            $user = $this->dataContext->findUser($username);
            $user->setPlainPassword($password);
            $this->dataContext->setCurrentUser($user);
            
            $this->dataContext->flush();
            $qb = $this->dataContext->findQueryBuilder(\App\Entity\M\Auth\AccessToken::class,"at");
            $qb
                ->andWhere("at.token = :token")
                ->setParameter("token",$token)
                ;
            $t = $qb->getQuery()->getOneOrNullResult();
            // assertNotNull($t,  sprintf("El token de acceso '%s' no existe en la base de datos.",$token));
        }
    }

    /**
     * Verifica que un paginador tenga una cantidad de elementos especificos
     * @example And the paginator "GET /api/url.json" must contain "= 1" elements
     * @Then the paginator :fullUrl must contain :elements elements
     */
    public function thePaginatorMustContainElements($fullUrl, $elements)
    {
        $this->iRequest($fullUrl);
        $this->dataContext->theResponseStatusCodeIs(Response::HTTP_OK);
        $this->theResponseIsAPaginator();
        $this->theResponseHasAPropertyAndContainsValues("data", $elements);
    }

    /**
     * Se evalua el mensaje que devuelve la excepcion
     * @example And the server exception respond with message 'validators.digital_account.location.is_not_editable::{"%type%":"30"}'
     * @Then the server exception respond with message :message
     */
    public function theServerExceptionRespondWithMessage($message)
    {
        $message = $this->dataContext->parseParameter($message);
        $this->theResponseHasAProperty("error");
        $error = $this->getPropertyValue("error");
        $hasMessage = false;
        if(is_array($error["exception"]) && count($error["exception"]) > 0){
            $hasMessage = $message === $error["exception"][0]["message"];
        }
        assertTrue(( $error["message"] === $message ||  $hasMessage),sprintf("The exception response message is not '%s'. Is a '%s'",$message,$error["message"]));
    }
    
    /**
     * And the response server has property name "App\Custom\HttpFoundation\View__EXTRA"
     * @Then the response server has property name :property
     */
    public function theResponseServerHasPropertyName($property)
    {
        $property = $this->dataContext->parseParameter($property);
        $server = $this->dataContext->getResponse()->headers->get("_server");
        if($server){
            $server = json_decode($server,true);
        }
        assertTrue(isset($server[$property]),sprintf("The property '%s' no found in response headers contains: \n%s", $property,  var_export($server,true)));
    }

    /**
     * Get property value from response data
     *
     * @param string $propertyName property name
     */
    public function getPropertyValue($propertyName)
    {
        return $this->getValue($propertyName, $this->dataContext->getData());
    }

    /**
     * Get property value from data
     *
     * @param string $propertyName property name
     * @param mixed $data data as array or object
     */
    protected function getValue($propertyName, $data)
    {
        if ($data === null) {
            throw new Exception(sprintf("Response was not set\n %s", var_export($data, true)));
        }

        $properties = explode(".", $propertyName);
        $totalProperties = count($properties);
        if (count($properties) > 1) {
            $data2 = $data;
            $i = 0;
            foreach ($properties as $property) {
                $i++;
                if (is_numeric($property)) {
                    $data2 = $data2[(int) $property];
                } else if (isset($data2[$property]) && is_array($data2[$property]) && $i < $totalProperties) {
                    $data2 = $data2[$property];
                }
                if ($i == $totalProperties && isset($data2[$property])) {
                    $data = $data2;
                    $propertyName = $property;
                    break;
                }
            }
        }

        if (is_array($data) && array_key_exists($propertyName, $data)) {
            $data = $data[$propertyName];
            return $data;
        }
        if (is_object($data) && property_exists($data, $propertyName)) {
            $data = $data->$propertyName;
            return $data;
        }
        if (is_string($data)) {
            throw new LogicException(sprintf("The response is a string data, verify call 'I request' not 'I html request'."));
        }
        throw new LogicException(sprintf("Property '%s' is not set! \n%s", $propertyName, var_export($data, true)));
    }

    /**
     * Creo un request oauth2
     * @When I create oauth2 request
     */
    public function iCreateOAuth2Request()
    {
        $this->dataContext->createClient();
        $this->initRequest();
        $this->serverParameters = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
            'HTTP_HOST' => $this->getContainer()->getParameter("router.request_context.host"),
            'HTTPS' => ($this->getContainer()->getParameter("router.request_context.scheme") === "https" ? true : false),
        ];
        $this->dataContext->getClient()->setServerParameters($this->serverParameters);

        $this->dataContext->setRequestBody('client_id', $this->parameters['oauth2']['client_id']);
        $this->dataContext->setRequestBody('client_secret', $this->parameters['oauth2']['client_secret']);
    }

    /**
     * Inicializa los datos para un siguiente request
     */
    protected function initRequest()
    {
        $this->lastRequestBody = $this->dataContext->getRequestBody();
        $this->lastResponse = $this->dataContext->getResponse();
        $this->dataContext->initRequestBody();
        $this->requestFiles = [];
        $this->requestHeaders = [];
    }

    /**
     * @Then the response is oauth2 format
     */
    public function theResponseHasTheOAuth2Format()
    {
        $expectedHeaders = [
            'cache-control' => 'no-store, private',
            'pragma' => 'no-cache'
        ];
        foreach ($expectedHeaders as $name => $value) {
            if ($this->dataContext->getResponse()->headers->get($name) != $value) {
                throw new \Exception(sprintf("Header %s is should be %s, %s given", $name, $value, $this->dataContext->getResponse()->headers->get($name)));
            }
        }
    }
    
    /**
     * @When I add resource owner credentials
     */
    public function iAddResourceOwnerCredentials()
    {
        $this->dataContext->setRequestBody('username', $this->parameters['oauth2']['username']);
        $this->dataContext->setRequestBody('password', $this->parameters['oauth2']['password']);
    }
    
    /**
     * @When I log in with client credentials
     */
    public function iLogInWithClientCredentials()
    {
        $this->iCreateOAuth2Request();
        $this->dataContext->setRequestBody('grant_type', 'client_credentials');
        $this->iAddResourceOwnerCredentials();
        $this->dataContext->iMakeAAccessTokenRequest();
        $this->dataContext->theResponseStatusCodeIs('200');
        $this->theResponseHasTheOAuth2Format();
        $this->dataContext->getClient()->setServerParameter("HTTP_AUTHORIZATION", sprintf("Bearer %s", $this->getPropertyValue("access_token")));
    }

    /**
     * @When I log in with client apps
     */
    public function iLogInWithClientApps()
    {
        $this->iCreateOAuth2Request();
        $this->dataContext->setRequestBody('grant_type', 'urn:client_apps');
        $this->dataContext->iMakeAAccessTokenRequest();
        $this->dataContext->theResponseStatusCodeIs('200');
        $this->theResponseHasTheOAuth2Format();
        $this->dataContext->getClient()->setServerParameter("HTTP_AUTHORIZATION", sprintf("Bearer %s", $this->getPropertyValue("access_token")));
    }
    
    /**
     * @When I log in with password
     */
    public function iLogInWithPassword()
    {
        $this->iCreateOAuth2Request();
        $this->dataContext->setRequestBody('grant_type', 'password');
        $this->iAddResourceOwnerCredentials();
        $this->dataContext->iMakeAAccessTokenRequest();
        $this->dataContext->theResponseStatusCodeIs('200');
        $this->theResponseHasTheOAuth2Format();
        $this->dataContext->getClient()->setServerParameter("HTTP_AUTHORIZATION", sprintf("Bearer %s", $this->getPropertyValue("access_token")));
    }
    
    /**
     * Limpia el token de acceso actual
     * @When I clear access token
     */
    public function iClearAccessToken()
    {
        $this->dataContext->getClient()->setServerParameter("HTTP_AUTHORIZATION",null);
    }
    
     /**
     * @Given that I have an refresh token
     */
    public function thatIHaveAnRefreshToken()
    {
        $this->dataContext->createClient();
        $parameters = $this->parameters['oauth2'];
        $parameters['grant_type'] = 'password';
        $url = $this->parameters['token_url'];
        $this->dataContext->getClient()->request('GET', $url, $parameters);
        $response = $this->dataContext->getClient()->getResponse();
        $data = json_decode($response->getContent(), true);
        if (!isset($data['refresh_token'])) {
            throw new Exception(sprintf("Error refresh token. Response: %s", $response->getContent()));
        }
        $this->refreshToken = $data['refresh_token'];
    }
    
    /**
     * @When I make a access token request with given refresh token
     */
    public function iMakeAAccessTokenRequestWithGivenRefreshToken()
    {
        $this->dataContext->setRequestBody('refresh_token', $this->refreshToken);
        $this->dataContext->iMakeAAccessTokenRequest();
    }
    
    /**
     * Ejecuta un request con la ultima data enviada en otro request
     * @example When I send last request body to "POST /api/url.json"
     * @When I send last request body to :fullUrl
     */
    public function iSendLastRequestBodyTo($fullUrl)
    {
        $this->iRequest($fullUrl, $this->lastRequestBodySave);
    }

    /**
     * Realiza una peticion a la API Rest
     * @When I request :fullUrl with options :options
     */
    public function iRequestOptions($fullUrl, $options)
    {
        $options = json_decode($options, true);
        $this->iRequest($fullUrl,null,null,$options);
    }

    public function getKernel()
    {
        return $this->kernel;
    }

    public function getContainer()
    {
        return $this->kernel->getContainer();
    }
    
    /**
     * Prints beautified debug string.
     *
     * @param string $string debug string
     */
    protected function printDebug($string)
    {
        echo sprintf("\n\033[36m| %s\033[0m\n\n", strtr($string, ["\n" => "\n|  "]));
    }
}
