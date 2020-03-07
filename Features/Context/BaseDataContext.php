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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Exception;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Base de contexto para generar data
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
abstract class BaseDataContext extends RawMinkContext implements \Behat\Symfony2Extension\Context\KernelAwareContext 
{
    use \Behat\Symfony2Extension\Context\KernelDictionary;
    use \Maxtoan\ToolsBundle\DependencyInjection\ContainerAwareTrait;

    /**
     *
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;
    protected $scenarioParameters;
    protected $requestFiles;
    protected $requestBody;
    protected $lastRequestBody;

    /**
     *
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
     * Initializes context.
     */
    public function __construct()
    {
        $this->parameters = [
            "token_url" => "/api/login_check"
        ];
    }

    /**
     * Genera un password estandar en base a un nombre de usuario
     * @param type $username
     * @return type
     */
    public function getPassword($username) 
    {
        $pass = substr(md5($username), 0, 8) . '.5$';
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
    * 
    * @param type $selector
    * @return \Behat\Mink\Element\NodeElement
    * @throws Exception
    */
    protected function findElement($selector)
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
     * Constante seria "Pandco\Bundle\AppBundle\Model\Base\TransactionItemInterface__STATUS_FINISH"
     * Traduccion con 'validators.invalid.phone.nro::{"%phoneNro%":"02475550001"}'
     * @param type $value
     * @param array $parameters
     * @param type $domain
     * @return type
     * @throws \RuntimeException
     */
    public function parseParameter($value,$parameters = [],$domain = "flashes") 
    {
        $valueExplode = explode("__",$value);
        if(is_array($valueExplode) && count($valueExplode) == 2){
//            var_dump($valueExplode[0]);
            $reflection = new \ReflectionClass($valueExplode[0]);
            if(!$reflection->hasConstant($valueExplode[1])){
                throw new \RuntimeException(sprintf("The class '%s' no has a constant name '%s'",$valueExplode[0],$valueExplode[1]));
            }
            $value = $reflection->getConstant($valueExplode[1]);
        } else if ($this->isScenarioParameter($value)) {
           $value = $this->getScenarioParameter($value);
        }else {
            if($parameters === null){
                $parameters = [];
            }
            $value = $this->parseTrans($value, $parameters, $domain);
        }
        return $value;
    }

    /**
     * Realiza el parse de un string para traducirlo
     * validators.sale.registration.code_quantity_min::{"%n%":2}::validators seria un ejemplo
     * validators.sale.registration.code_quantity_min::{}::validators seria un ejemplo
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
//                var_dump($paramsString);
                $parametersParsed = json_decode($paramsString,true);
                if(is_array($parametersParsed)){
                    $this->parseScenarioParameters($parametersParsed);
                    foreach ($parametersParsed as $x => $v) {
                        if(strpos($v,$subSeparator) !== false){
                            $v = str_replace($subSeparator, $separator, $v);
                            $parametersParsed[$x] = $this->parseTrans($v);
                        }
                    }
//                    var_dump($parametersParsed);
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
    protected function getDoctrine() 
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
        // var_dump(array_keys($parameters));
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

    public function setRequestBody($key,$value)
    {
        $this->requestBody[$key] = $value;
        return $this;
    }
    
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
     * 
     *  \Symfony\Component\BrowserKit\Client
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    function getClient()
    {
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
}