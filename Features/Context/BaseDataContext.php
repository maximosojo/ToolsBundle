<?php

/*
 * This file is part of the Máximo Sojo - maxtoan package.
 * 
 * (c) https://maxtoan.github.io/
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Features\Context;

use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Maxtoan\ToolsBundle\DependencyInjection\ContainerAwareTrait;
use LogicException;
use Exception;
use FOS\UserBundle\Model\UserInterface;

if(class_exists("PHPUnit\Exception")){
    $reflection = new \ReflectionClass("PHPUnit\Exception");
    require_once dirname($reflection->getFileName()) . '/Framework/Assert/Functions.php';
}

/**
 * Base de contexto para generar data
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
abstract class BaseDataContext extends RawMinkContext implements KernelAwareContext
{
    use KernelDictionary;
    use ContainerAwareTrait;

    /**
     *
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;

    /**
     * $scenarioParameters
     * @var Scenario
     */
    protected $scenarioParameters;

    /**
     * $requestFiles
     * @var files
     */
    protected $requestFiles;

    /**
     * $requestBody
     * @var Body
     */
    protected $requestBody;

    /**
     * $lastRequestBody
     * @var Request
     */
    protected $lastRequestBody;

    /**
     * $response
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected $response;

    /**
     * Respuesta de la ultima peticion http
     * @var array
     */
    protected $data;

    /**
     * Usuario logueado
     * @var UserInterface
     */
    protected $currentUser;

    /**
     * User
     * @var string 
     */
    protected $userClass;

    /**
     * Funcion para hacer parse de parametros customs
     * @var callable
     */
    protected $parseParameterCallBack;

    /**
     * Initializes context.
     */
    public function __construct()
    {
        $this->parameters = [
            "token_url" => "/api/login_check"
        ];
    }

    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Genera un password estandar en base a un nombre de usuario
     * @param type $username
     * @return type
     */
    public function getPassword($username) 
    {
        $pass = substr(md5($username), 0, 8) . '.*/';
        return $pass;
    }

    /**
     * Se dirige a la pagina de inicio de sesion
     * @Given /^I am on login page$/
     */
    public function iAmOnLoginPage()
    {
        $this->getSession()->visit($this->generatePageUrl('fos_user_security_login'));
    }

    /**
     * Se dirige a una ruta de symfony
     * Example: Given I am on "app_route_test" page
     * @Given I am on :route page
     * @Given I am on :route page with :parameters
     */
    public function iAmOnPage($route,$parameters = [])
    {
        if ($parameters) {
            $parameters = (array)json_decode($parameters);
        }
        
        $this->getSession()->visit($this->generatePageUrl($route,$parameters));
        if($this->isOpenBrowser()){
            $this->getSession()->wait(2000);
        }
    }

    /**
     * Esperar que un elemento desaparezca de la pagina
     * Example: And I wait for "div.loading" element to disappear
     * @Then I wait for :selector element to disappear
     */
    public function iWaitForElementToDisappear($selector)
    {
        $this->spin(function($context) use ($selector) {
            try {
                $this->findElement($selector);
                return false;
            } catch (Exception $ex) {
                return true;
            }
       });
    }

    /**
     * Obtine un elemento
     * @param type $selector
     * @return \Behat\Mink\Element\NodeElement
     * @throws Exception
     */
    public function findElement($selector)
    {
       $page = $this->getSession()->getPage();
       $element = $page->find('css', $selector);

       if (empty($element)) {
           throw new Exception("No html element found for the selector ('$selector')");
       }
       return $element;
    }

    /**
     * Espera unos segundos antes de ejecutar el siguiente paso
     * Example: I wait for 5 seconds
     * @Given /^I wait for (\d+) seconds$/
     */
    public function iWaitForSeconds($seconds)
    {
        $this->getSession()->wait($seconds * 1000);
    }

    /**
     * Espera hasta que devuelva true la funcion pasada
     * Based on Behat's own example
     * @see http://docs.behat.org/en/v2.5/cookbook/using_spin_functions.html#adding-a-timeout
     * @param $lambda
     * @param int $wait
     * @throws \Exception
     */
   	public function spin($lambda, $wait = 15,$errorCallback = null)
   	{
       $time = time();
       $stopTime = $time + $wait;
       while (time() < $stopTime)
       {
           try {
               if ($lambda($this)) {
                   return;
               }
           } catch (\Exception $e) {
               // do nothing
           }

           usleep(250000);
       }
       
       if($errorCallback !== null){
           $errorCallback($this);
       }

       throw new \Exception("Spin function timed out after {$wait} seconds");
   }

    /**
     * Verifica el test usa seleninum, es decir, si el test usa el navegador se abrio.
     * @return type
     */
    public function isOpenBrowser() 
    {
        return get_class($this->getMink()->getSession()->getDriver()) === "Behat\Mink\Driver\Selenium2Driver";
    }

    /**
     * Limpia clases
     * @Given a clean entity :class
     */
    public function aCleanClass($class, $andWhere = null)
    {
        $em = $this->getDoctrine()->getManager();
        if ($em->getFilters()->isEnabled('softdeleteable')) {
            $em->getFilters()->disable('softdeleteable');
        }
        
        $query = $em->createQuery("DELETE FROM " . $class . " " . $andWhere);
        $query->execute();
        $em->flush();
        $em->clear();
    }

    /**
     * Registro de escenario
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  String $key
     * @param  String $value
     */
    public function setScenarioParameter($key, $value) 
    {
        if ($this->scenarioParameters === null) {
            $this->initParameters();
        }
        if (is_array($value)) {
            foreach ($value as $subKey => $val) {
                $newKey = $key . "." . $subKey;
                $this->setScenarioParameter($newKey, $val);
            }
        } else {
            if (!$this->isScenarioParameter($key)) {
                $key = "%" . $key . "%";
            }
            $this->scenarioParameters[$key] = $value;
        }
    }

    /**
     * Verifica si el texto es un parametro
     * @param type $value
     * @return boolean
     */
    public function isScenarioParameter($value,$checkExp = false) 
    {
        if ($this->scenarioParameters === null) {
            $this->initParameters();
        }
        $result = isset($this->scenarioParameters[$value]);
        if (!$result) {
            if (substr($value, 0, 1) === "%" && substr($value, strlen($value) - 1, 1) === "%") {
                $result = true;
            }
        }
        if(!$result && $checkExp === true){
            foreach ($this->scenarioParameters as $key => $v) {
                if(preg_match("/".$key."/", $value)){
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Parsea un parametro para ver si es una constante o una traduccion con parametros
     * Constante seria "App\Model\Base\ClassInterface__STATUS"
     * Traduccion con 'validators.invalid.phone.nro::{"%key%":"value"}'
     * @param type $value
     * @param array $parameters
     * @param type $domain
     * @return type
     * @throws \RuntimeException
     */
    public function parseParameter($value, $parameters = [], $domain = "flashes",array $options = [])
    {
        if (is_string($value)) {
            $jsonObject = json_decode((string) $value, true);
            if(is_array($jsonObject)){
                $this->replaceParameters($jsonObject);
                return $jsonObject;
            }
        }else if(is_array($value)){
            //No se procesa un array nativo, solo array en string.
            return $value;
        }else if(is_bool($value)){
            //No se procesa un booleano nativo
            return $value;
        }
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "return_object" => false,
        ]);
        $options = $resolver->resolve($options);
        if ($value === "now()") {
            return new \DateTime();
        }
        if (strpos($value, "date::") !== false) {
            $exploded = explode("::", $value);
            $value = \DateTime::createFromFormat("Y-m-d", $exploded[1]);
            if ($value === null) {
                throw new \RuntimeException(sprintf("The date format must be Y-m-d of '%s'", $exploded[1]));
            }
            return $value;
        }
        if (strpos($value, "lastResponse::") !== false) {
            $exploded = explode("::", $value);
            $propertyPath = $exploded[1];
            $lastResponse = $this->getScenarioParameter("%lastResponse%");
            $value = $this->accessor->getValue($lastResponse, $propertyPath);
            return $value;
        }
        if($this->parseParameterCallBack){
            $value = call_user_func_array($this->parseParameterCallBack, [$value, $parameters,$domain,$this]);
        }
        $valueExplode = explode("__", $value);
        if (is_array($valueExplode) && count($valueExplode) == 2) {
//            var_dump($valueExplode[0]);
            $valueExplode[0] = str_replace("\\\\","\\", $valueExplode[0]);//Fix de clases con doble \\
            $reflection = new \ReflectionClass($valueExplode[0]);
            if (!$reflection->hasConstant($valueExplode[1])) {
                throw new \RuntimeException(sprintf("The class '%s' no has a constant name '%s'", $valueExplode[0], $valueExplode[1]));
            }
            $value = $reflection->getConstant($valueExplode[1]);
        } else if ($this->isScenarioParameter($value)) {
            $value = $this->getScenarioParameter($value,false,$options["return_object"]);
        } else {
            if ($parameters === null) {
                $parameters = [];
            }
            $value = $this->parseTrans($value, $parameters, $domain);
        }
        return $value;
    }

    /**
     * Realiza el parse de un string para traducirlo
     * validators.test.translation::{"%n%":2}::validators seria un ejemplo
     * validators.test.translation::{}::validators seria un ejemplo
     * @param type $id
     */
    protected function parseTrans($id,array $parameters = [],$domain = "flashes") 
    {
        $text = $id;
        $separator = "::";
        $subSeparator = ";;";
        
        $textExplode = explode($separator, $id);
        if(is_array($textExplode)){
            //id a traducir
            if(isset($textExplode[0])){
                $text = $textExplode[0];
            }
            //Parametros de la traduccion
            if(isset($textExplode[1])){
                $paramsString = $textExplode[1];
                $parametersParsed = json_decode($paramsString,true);
                if(is_array($parametersParsed)){
                    $this->parseScenarioParameters($parametersParsed);
                    foreach ($parametersParsed as $x => $v) {
                        if(strpos($v,$subSeparator) !== false){
                            $v = str_replace($subSeparator, $separator, $v);
                            $parametersParsed[$x] = $this->parseTrans($v);
                        }
                    }
                    $parameters = $parametersParsed;
                }
            }
            //Dominio e la traduccion
            if(isset($textExplode[2])){
                $domain = $textExplode[2];
            }
        }
        if(strpos($text,"|") && isset($parameters["{{ limit }}"])){
            $trans = $this->container->get('translator')->transChoice($text, (int)$parameters["{{ limit }}"], $parameters,$domain);
        }else {
            $trans = $this->trans($text,$parameters,$domain);
        }
        return $trans;
    }

    /**
     * Busca los parametros dentro de un array por su indice y le hace el parse a su valor final.
     * @param array $parameters
     * @param type $checkExp
     */
    public function parseScenarioParameters(array &$parameters,$checkExp = false) 
    {
        foreach ($parameters as $key => $value) {
            if($this->isScenarioParameter($value,$checkExp)){
                $parameters[$key] = $this->getScenarioParameter($value);
            }
        }
        return $parameters;
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    public function getDoctrine() 
    {
        return $this->container->get("doctrine");
    }

    /**
     * Se registra entidad
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  [type]
     * @param  boolean
     * @return [type]
     */
    public function save($entity, $flush = true)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        if ($flush) {
            $em->flush();
        }
    }

    /**
     * Se hace flush de datos
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  [type]
     * @param  boolean
     * @return [type]
     */
    public function flush()
    {
        $em = $this->getDoctrine()->getManager();
        $em->flush();
    }

    /**
     * Generate page url.
     * This method uses simple convention where page argument is prefixed
     * with "sylius_" and used as route name passed to router generate method.
     *
     * @param string $page
     * @param array  $parameters
     *
     * @return string
     */
    protected function generatePageUrl($route, array $parameters = array())
    {
        $path = $this->generateUrl($route, $parameters);

        if ('Selenium2Driver' === strstr(get_class($this->getSession()->getDriver()), 'Selenium2Driver')) {
            return sprintf('%s%s', $this->getMinkParameter('base_url'), $path);
        }
        
        return $path;
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param string $route         The name of the route
     * @param mixed  $parameters    An array of parameters
     * @param int    $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     *
     * @return string The generated URL
     *
     * @see UrlGeneratorInterface
     */
    protected function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH) 
    {
        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }
    
    /**
     * Traduce un indice
     * @param type $id
     * @param array $parameters
     * @param type $domain
     * @return type
     */
    protected function trans($id,array $parameters = array(), $domain = '')
    {
        return $this->container->get('translator')->trans($id, $parameters, $domain);
    }

    /**
     * Obtiene el valor de un parametro en el escenario
     * @param type $key
     * @return type
     * @throws Exception
     */
    public function getScenarioParameter($key, $checkExp = false)
    {
        $parameters = $this->getScenarioParameters();
        $user = null;
        if ($this->currentUser) {
            $user = $this->find($this->userClass, $this->currentUser->getId());
        }
        $value = null;
        if (empty($key)) {
            xdebug_print_function_stack();
            throw new Exception("The scenario parameter can not be empty.");
        }
        if (isset($parameters[$key])) {
            if (is_callable($parameters[$key])) {
                $value = call_user_func_array($parameters[$key], [$user, $this]);
            } else {
                $value = $parameters[$key];
            }
        } else {
            $found = false;
            if ($checkExp === true) {
                foreach ($parameters as $k => $v) {
                    if (preg_match("/" . $k . "/", $key)) {
                        $value = str_replace($k, $this->getScenarioParameter($k), $key);
                        $found = true;
                        break;
                    }
                }
            }
            if (!$found) {
                throw new Exception(sprintf("The scenario parameter '%s' is not defined", $key));
            }
        }
        return $value;
    }

    /**
     * Retorna los parametros definidos
     * @return type
     */
    private function getScenarioParameters()
    {
        if ($this->scenarioParameters === null) {
            $this->initParameters();
        }
        return $this->scenarioParameters;
    }

    /**
     * Crea un cliente para hacer peticiones
     * @return \Symfony\Component\BrowserKit\Client
     */
    public function createClient()
    {
        $this->client = $this->getKernel()->getContainer()->get('test.client');
        $client = $this->client;
        return $client;
    }

    public function replaceParameters(&$array)
    {
        $this->arrayReplaceRecursiveValue($array, $this->scenarioParameters);
    }

    /**
     * Reemplaza recursivamente los parametros en un array
     * @param type $array
     * @param type $parameters
     * @return type
     */
    private function arrayReplaceRecursiveValue(&$array, $parameters)
    {
        foreach ($array as $key => $value) {
            // create new key in $array, if it is empty or not an array
            if (!isset($array[$key]) || (isset($array[$key]) && !is_array($array[$key]))) {
                $array[$key] = array();
            }

            // overwrite the value in the base array
            if (is_array($value)) {
                $value = $this->arrayReplaceRecursiveValue($array[$key], $parameters);
            } else {
                $value = $this->parseParameter($value, $parameters);
            }
            $array[$key] = $value;
        }
        return $array;
    }

    /**
     * Realiza una peticion a la API Rest
     * @When I request :fullUrl
     */
    public function iRequest($fullUrl, array $parameters = null, array $files = null,array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "clear_request" => true,
        ]);
        $options = $resolver->resolve($options);
        $explode = explode(" ", $fullUrl);
        $method = $explode[0];
        $url = $explode[1];
        if ($parameters === null) {
            $parameters = $this->getRequestBody();
        }
        if ($files === null) {
            $files = $this->requestFiles;
        }
        if ($parameters === null) {
            $parameters = [];
        }
        if ($files === null) {
            $files = [];
        }
        
        $this->getClient()->request($method, $url, $parameters, $files);
        $this->response = $this->getClient()->getResponse();
        if($options["clear_request"] === true){
            $this->initRequest();
        }
        $contentType = (string) $this->response->headers->get('Content-type');
        if ($this->response->getStatusCode() != 404 && !empty($contentType) && $contentType !== 'application/json') {
            throw new \Exception(sprintf("Content-type must be application/json received '%s' \n%s", $contentType, $this->echoLastResponse()));
        }
        $content = $this->response->getContent();
        $this->data = [];
        if ($content) {
            $this->data = json_decode($this->response->getContent(), true);
            $this->lastErrorJson = json_last_error();
            if ($this->response->getStatusCode() != 404 && $this->lastErrorJson != JSON_ERROR_NONE) {
                throw new \Exception(sprintf("Error parsing response JSON " . "\n\n %s", $this->echoLastResponse()));
            }
        }
        if (isset($this->data["id"])) {
            $this->setScenarioParameter("%lastId%", $this->data["id"]);
        }
        $this->setScenarioParameter("request",$this->data);
        $this->restartKernel();
    }

    /**
     * @Then echo last response
     */
    public function echoLastResponse()
    {
        $this->printDebug(sprintf("Request:\n %s \n\n Response:\n %s", json_encode($this->lastRequestBody, JSON_PRETTY_PRINT, 10), $this->response->getContent()));
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

    public function restartKernel()
    {
        $kernel = $this->getKernel();
        $kernel->shutdown();
        $kernel->boot();
        $this->setKernel($kernel);
    }

    public function getRequestBody($key = null,$default = null)
    {
        if($key === null){
            return $this->requestBody;
        }
        if(isset($this->requestBody[$key])){
            $default = $this->requestBody[$key];
        }
        return $default;
    }

    /**
     * setRequestBody
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  $key
     * @param  $value
     */
    public function setRequestBody($key,$value)
    {
        $this->requestBody[$key] = $value;
        return $this;
    }
    
    /**
     * initRequestBody
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  array  $body
     * @return This
     */
    public function initRequestBody(array $body = [])
    {
        $this->requestBody = $body;
        return $this;
    }

    /**
     * Inicializa los datos para un siguiente request
     */
    public function initRequest()
    {
        $this->lastRequestBody = $this->getRequestBody();
        $this->lastResponse = $this->response;
        $this->initRequestBody();
        $this->requestFiles = [];
    }

    /**
     * \Symfony\Component\BrowserKit\Client
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    function getClient()
    {
        if (!$this->client) {
            $this->createClient();
        }
        return $this->client;
    }

    /**
     * @Then the response status code is :httpStatus
     */
    public function theResponseStatusCodeIs($httpStatus)
    {
        if ((string) $this->response->getStatusCode() !== (string) $httpStatus) {
            throw new \Exception(sprintf("HTTP code does not match %s (actual: %s)\n\n %s", $httpStatus, $this->response->getStatusCode(), $this->echoLastResponse()));
        }
        
        return true;
    }

    /**
     * Limia una tabla de la base de datos
     * @example Given a clear entity "App\Entity\EntityName" table
     * @Given a clear entity :className table
     * @Given a clear entity :className table and where :where
     */
    public function aClearEntityTable($className, $andWhere = null)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        if ($em->getFilters()->isEnabled('softdeleteable')) {
            $em->getFilters()->disable('softdeleteable');
        }
        
        $query = $em->createQuery("DELETE FROM " . $className . " " . $andWhere);
        $query->execute();
        $em->flush();
        $em->clear();
    }

    /**
     * Verifica que exista un mensaje de error
     * @example And the response has a errors property and contains "Invalid username or password."
     * @Then the response has a errors property and contains :message
     */
    public function theResponseHasAErrorsPropertyAndContains($message)
    {
        $message = $this->parseParameter($message, [], "validators");
        $errors = $this->getPropertyValue("errors");

        $found = false;
        if (is_array($errors['errors'])) {
            foreach ($errors['errors'] as $error) {
                if ($error === $message) {
                    $found = true;
                    break;
                }
            }
        } else {
            throw new Exception(sprintf("The error property no contains error message. '%s' \n \n %s", $message, var_export($errors['errors'], true), $this->echoLastResponse()));
        }
        if ($found === false) {
            throw new Exception(sprintf("The error response no contains error message '%s', response with '%s'", $message, implode(",", $errors['errors'])));
        }
    }

    /**
     * Verifica que una propiedad x contiene un error
     * @example And the response has a errors in property "message" and contains "Invalid username or password."
     * @Then the response in property :propertyName and contains :message
     */
    public function theResponseInPropertyAndContains($propertyName, $message)
    {
        $properties = explode(".", $propertyName);
        $property = $this->getPropertyValue($propertyName);        
        $message = $this->parseParameter($message, [], 'validators');        
        if ($property === $message) {
            $found = true;
        } else {
            throw new Exception(sprintf("The error property contains error message '%s', response with '%s'", $propertyName, implode(", ", $property)));
        }
    }

    /**
     * Verifica que una propiedad x contiene un error
     * @example And the response has a errors in property "username_password" and contains "Invalid username or password."
     * @Then the response has a errors in property :propertyName and contains :message
     */
    public function theResponseHasAErrorsInPropertyAndContains($propertyName, $message = null,$negate = false)
    {
        $properties = explode(".", $propertyName);
        $errors = $this->getPropertyValue("errors");
        $children = $errors["children"];
        if (count($properties) > 1) {
            $data = $children;
            foreach ($properties as $property) {
                if (isset($data[$property]) && isset($data[$property]["children"])) {
                    $data = $data[$property]["children"];
                }
                if (isset($data[$property]) && isset($data[$property]["errors"])) {
                    $children = $data;
                    $propertyName = $property;
                    break;
                }
            }
        }        
        if (!isset($children[$propertyName])) {
            throw new Exception(sprintf("The response no contains error property '%s' \n Available are %s", $propertyName, implode(", ", array_keys($children))));
        }
        $message = $this->parseParameter($message, [], 'validators');
        if (isset($children[$propertyName]["errors"])) {
            if ($message === null) {
                if (count($children[$propertyName]["errors"]) == 0) {
                    throw new Exception(sprintf("The error property no contains errors in '%s', response with '%s'", $propertyName, var_export($errors, true)));
                }
            } else {
                $found = false;
                foreach ($children[$propertyName]["errors"] as $error) {
                    if ($error === $message) {
                        $found = true;
                        break;
                    }
                }
                if ($negate === false && $found === false) {
                    throw new Exception(sprintf("The error property no contains error message '%s', response with '%s'", $propertyName, implode(", ", $children[$propertyName]["errors"])));
                }else if ($negate === true && $found === true) {
                    throw new Exception(sprintf("The error property contains error message '%s', response with '%s'", $propertyName, implode(", ", $children[$propertyName]["errors"])));
                }
            }
        } else {
            throw new Exception(sprintf("The error property no contains errors '%s', response with '%s'", $propertyName, var_export($errors, true)));
        }
    }

    /**
     * Get property value from response data
     *
     * @param string $propertyName property name
     */
    public function getPropertyValue($propertyName)
    {
        return $this->getValue($propertyName, $this->data);
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
     * Afirma que la respuesta es un paginador
     * @Then the response is a paginator
     */
    public function theResponseIsAPaginator()
    {
        $this->theResponseHasAProperty("links");
        $this->theResponseHasAProperty("meta");
        $this->theResponseHasAProperty("data");
        // \assertCount(3, $this->data);
//        echo json_encode($this->data,JSON_PRETTY_PRINT);
    }

    /**
     * Verifica que el ultimo resultado tenga unas propiedades separadas por coma (id,name,description)
     * @example And the response has a "id,number,secure_label,email,bank,alias,digital_account" properties
     * @Then the response has a :propertiesName properties
     */
    public function theResponseHasAProperties($propertiesName)
    {
        $propertiesName = explode(",", $propertiesName);
        foreach ($propertiesName as $propertyName) {
            $this->theResponseHasAProperty($propertyName);
        }
    }

    /**
     * Verifica que la ultima respuesta tenga una propiedad
     * @Then the response has a :propertyName property
     */
    public function theResponseHasAProperty($propertyName)
    {
        if ((isset($this->parameters['recommended'][$propertyName]) && !$this->parameters['recommended'][$propertyName])) {
            return;
        }
        if ((isset($this->parameters['optional'][$propertyName]) && !$this->parameters['optional'][$propertyName])) {
            return;
        }
        try {
            return $this->getPropertyValue($propertyName);
        } catch (\LogicException $e) {
            throw new \Exception(sprintf("%s\n\n %s", $e->getMessage(), $this->echoLastResponse()));
        }
    }

    /**
     * Verifica que la respuesta no tenga una propiedad en especifico
     * @Then the response does not have :propertyName property
     */
    public function theResponseDoesNotHaveProperty($propertyName)
    {
        try {
            $this->getPropertyValue($propertyName);
        } catch (\LogicException $e) {
            //La propiedad no existe
            return;
        }
        throw new \Exception(sprintf("Property %s is exists in response!\n\n %s", $propertyName, $this->echoLastResponse()));
    }

    /**
     * Busca una propiedad y verifica que la cantidad de elmentos sea la deseada
     * @example Then the response has a "transactions.0.pay_tokens" property and contains "= 0" values
     * @Then the response has a :propertyName property and contains :expresion values
     */
    public function theResponseHasAPropertyAndContainsValues($propertyName, $expresion)
    {
        $this->theResponseHasAPropertyAndItsTypeIs($propertyName, "array");
        $value = $this->theResponseHasAProperty($propertyName);
        $expresionExplode = explode(" ", $expresion);
        $quantity = count($value);
        if (version_compare($quantity, (int) $expresionExplode[1], $expresionExplode[0]) === false) {
            throw new Exception(sprintf("Expected '%s' but there is '%s' elements.\n%s", $expresion, $quantity, var_export($value, true)));
        }
    }
    
    /**
     * @example And the response its type is "array" and contains "= 1" values
     * @Then the response its type is :typeString and contains :expresion values
     * @Then the response its type is :typeString
     */
    public function theResponseItsTypeIsAndContainsValues($typeString, $expresion = null) {
        $value = $this->data;

        // check our type
        switch (strtolower($typeString)) {
            case 'numeric':
                if (is_numeric($value)) {
                    break;
                }
            case 'array':
                if (is_array($value)) {
                    break;
                }
            case 'null':
                if ($value === NULL) {
                    break;
                }
            default:
                throw new \Exception(sprintf("Property %s is not of the correct type: %s!\n\n %s", $propertyName, $typeString, $this->echoLastResponse()));
        }
        
        if ($expresion !== null) {
            $quantity = count($value);
            ToolsUtils::testQuantityExp($expresion, $quantity);
        }
    }

    /**
     * @Given the response has a :propertyName property and it is equals :propertyValue
     */
    public function theResponseHasAPropertyAndItIsEquals($propertyName, $propertyValue)
    {
        if ($this->isScenarioParameter($propertyValue)) {
            $propertyValue = $this->getScenarioParameter($propertyValue);
        }
        $propertyValue = $this->parseParameter($propertyValue);
        $value = $this->theResponseHasAProperty($propertyName);
        if ($value == $propertyValue) {
            return;
        }
        throw new \Exception(sprintf("Given %s value is not %s is equals to '%s'\n\n %s", $propertyName, $propertyValue, $value, $this->echoLastResponse()));
    }

    /**
     * Verifica que un campo sea de un tipo en especifico
     * @example And the response has a "files" property and its type is "array"
     * @Then the response has a :propertyName property and its type is :typeString
     */
    public function theResponseHasAPropertyAndItsTypeIs($propertyName, $typeString)
    {
        $value = $this->theResponseHasAProperty($propertyName);
        // check our type
        switch (strtolower($typeString)) {
            case 'numeric':
                if (is_numeric($value)) {
                    break;
                }
            case 'array':
                if (is_array($value)) {
                    break;
                }
            case 'null':
                if ($value === NULL) {
                    break;
                }
            default:
                throw new \Exception(sprintf("Property %s is not of the correct type: %s!\n\n %s", $propertyName, $typeString, $this->echoLastResponse()));
        }
    }

    /**
     * Establece el usuario de la sesion actual en el cliente http
     * @param User $currentUser
     * @return \DataContext
     */
    public function setCurrentUser(UserInterface $currentUser)
    {
        $this->currentUser = $currentUser;
        if($this->client !== null){
            $container = $this->client->getContainer();
            $session = $this->client->getContainer()->get('session');
            /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
            $loginManager = $container->get('FOS\UserBundle\Security\LoginManager');//fos_user.security.login_manager
            $firewallName = $container->getParameter('fos_user.firewall_name');
            // the firewall context defaults to the firewall name
            $firewallContext = $firewallName;
            $fwName = '_security_'.$firewallContext;
            if($currentUser === null){
                $session->remove($fwName);
                $session->save();
                $cookie = new \Symfony\Component\BrowserKit\Cookie($session->getName(), $session->getId());
                $this->client->getCookieJar()->set($cookie);
                // assertTrue(is_null($container->get('security.token_storage')->getToken()->getUser()));
            }else{
                $loginManager->loginUser($firewallName, $currentUser);
                $token = new \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken(
                    $currentUser,
                    $currentUser->getPassword(),
                    $firewallContext,
                    $currentUser->getRoles()
                );
                $session->set($fwName, serialize($token));
                $session->save();
                $cookie = new \Symfony\Component\BrowserKit\Cookie($session->getName(), $session->getId());
                $this->client->getCookieJar()->set($cookie);
                // assertTrue(is_object($container->get('security.token_storage')->getToken()->getUser()));
            }
        }
        
        return $this;
    }

    /**
     * Retorna el usuario actual
     * @param User $currentUser
     * @return \DataContext
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * Devuelve el primer elemento que encuentr de la base de datos
     * @param type $class
     * @return type
     */
    public function findOneElement($class, UserInterface $user = null)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->enable("enableable");
        $alias = "o";
        $qb = $em->createQueryBuilder()
                ->select($alias)
                ->from($class, $alias);
        if ($user !== null) {
            $qb
                    ->andWhere("o.user = :user")
                    ->setParameter("user", $user)
            ;
        }
        $qb->orderBy("o.createdAt", "DESC");
        $entity = $qb->setMaxResults(1)->getQuery()->getOneOrNullResult();
        return $entity;
    }

    /**
     * Busca una entidad
     * @param type $className
     * @param type $id
     * @return type
     */
    public function find($className, $id) 
    {
        return $this->getDoctrine()->getManager()->find($className, $id);
    }

    /**
     * iDeleteForTest
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  $class
     * @param  array  $method
     * @return Entity
     */
    public function iDeleteForTest($class, array $method = []) 
    {
        $em = $this->getDoctrine()->getManager();
        if ($em->getFilters()->isEnabled('softdeleteable')) {
            $em->getFilters()->disable('softdeleteable');
        }
        
        $a = "c";
        foreach ($method as $key => $value) {
            $query = $em->createQuery("DELETE FROM " . $class ." c WHERE ". sprintf("%s.%s = '%s",$a,$key,$value). "'");
            $query->execute();
        }
        $em->flush();
    }

    /**
     * Contruye un queryBuilder de una clase
     * @param type $class
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findQueryBuilder($class, $alias = "o",$manager = null)
    {
        $em = $this->getDoctrine()->getManager($manager);
        $qb = $em->createQueryBuilder()
                ->select($alias)
                ->from($class, $alias);
        return $qb;
    }

    /**
     * Contruye un queryBuilder de una clase
     * @param type $class
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findQueryBuilderForClass($class, array $method = [], $queryResult = null) 
    {
        $em = $this->getDoctrine()->getManager();        
        $alias = "c";
        $qb = $em->createQueryBuilder()
                ->select($alias)
                ->from($class, $alias);
        foreach ($method as $key => $value) {
            $qb
                ->andWhere(sprintf("%s.%s = :%s",$alias,$key,$key))
                ->setParameter($key, $value)
            ;
        }
        if ($queryResult == "OneOrNull") {
            return $qb->setMaxResults(1)->getQuery()->getOneOrNullResult();
        } else {
            return $qb->getQuery()->getResult();
        }
    }

    /**
     * Elimina un usuario de prueba
     * Ejemplo: Given I delete user "maximosojo" for test
     * @Given I delete user :username for test
     */
    public function iDeleteUserForTest($username) 
    {
        $this->iDeleteForTest($this->userClass,["username" => $username]);
    }

    /**
     * Busca un usuario en la base de datos
     * @param type $username
     * @return User
     */
    public function findUser($username)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($this->userClass)->findOneByUsername($username);
        if($entity){
            $em->refresh($entity);
        }
        return $entity;
    }

    /**
     * @When I send a access token request
     */
    public function iMakeAAccessTokenRequest()
    {
        $url = $this->parameters['token_url'];
        $body = $this->getRequestBody();
        
        $this->getClient()->request("POST", $url, $body);
        $this->response = $this->getClient()->getResponse();
        $contentType = (string) $this->response->headers->get('Content-type');
        $this->initRequest();
        if ($contentType !== 'application/json') {
            throw new \Exception(sprintf("Content-type must be application/json %s", $this->echoLastResponse()));
        }
        $this->data = json_decode($this->response->getContent(), true);
        $this->lastErrorJson = json_last_error();
        if ($this->lastErrorJson != JSON_ERROR_NONE) {
            throw new \Exception(sprintf("Error parsing response JSON " . "\n\n %s", $this->echoLastResponse()));
        }
    }

    /**
     * Evalua el campo de retorno _sever contenga un mensaje flash
     * @example And the response server has flash type "success" with message 'Registro exitoso.'
     * @Then the response server has flash type :type with message :message
     */
    public function theResponseServerHasFlashTypeWithMessage($type, $message)
    {
        $server = $this->response->headers->get("_server");
        if($server){
            $server = json_decode($server,true);
        }
        // assertArrayHasKey("flashes", $server);
        $message = $this->parseParameter($message,[],"flashes");
        $found = false;
        foreach ($server["flashes"] as $flash) {
            if ($flash["type"] === $type && $flash["message"] === $message) {
                $found = true;
                break;
            }
        }
        // assertTrue($found, sprintf("The flash type '%s' no found with message '%s', response contains: \n%s", $type, $message, var_export($server["flashes"], true)));
    }

    public function setParseParameterCallBack($parseParameterCallBack)
    {
        $this->parseParameterCallBack = $parseParameterCallBack;
        return $this;
    }

    /**
     * Click a un elemento visible por su id
     * Example: And I click the "#idelement" element
     * @Given I click the :selector element
     */
    public function iClickTheElement($selector)
    {
        $element = $this->findElement($selector);
        $this->getSession()->wait(2 * 1000);
        try {
            $element->click();
        } catch (\Exception $ex) {
            //esperamos 2 segundos para intentar de nuevo hacer click
            $this->getSession()->wait(2 * 1000);
            $element->click();
        }
    }

    /**
     * Executa un comando
     * @Given a execute command to :command
     */
    public function aExecuteCommandTo($command)
    {
        $this->restartKernel();
        $kernel = $this->getKernel();

        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);

        $exploded = explode(" ", $command);

        $commandsParams = [
        ];
        
        foreach ($exploded as $value) {
            if (!isset($commandsParams["command"])) {
                $commandsParams["command"] = $value;
            } else {
                $e2 = explode("=", $value);
                if (count($e2) == 1) {
                    $commandsParams[$e2[0]] = true;
                } else if (count($e2) == 2) {
                    $commandsParams[$e2[0]] = $e2[1];
                }
            }
        }

        foreach ($commandsParams as $key => $value) {
            $commandsParams[$key] = $value;
        }

        $output = null;
        $input = new \Symfony\Component\Console\Input\ArrayInput($commandsParams);
        if ($output === null) {
            $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        }
        $application->run($input, $output);
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getData()
    {
        return $this->data;
    }
}