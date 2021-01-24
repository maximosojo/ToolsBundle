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

use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Tester\Exception\PendingException;
use Maxtoan\ToolsBundle\Features\Context\BaseDataContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;
use App\Entity\M\User;
use App\Entity\M\Group;

/**
 * Contexto para agregas test de navegador
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class AbstractMinkContext extends MinkContext
{	
    /**
     * $scenarioParameters
     * @var ScenarioParameters
     */
    protected $scenarioParameters;

	/**
	 * $kernel
	 * @var Kernel
	 */
	protected $kernel;

	/**
	 * $container
	 * @var Container
	 */
    protected $container;

    /**
     *
     * @var BaseDataContext
     */
    protected $dataContext;

    protected $parameters;

    private $isMaximized = false;

    /** @BeforeScenario */
    public function gatherContexts(\Behat\Behat\Hook\Scope\BeforeScenarioScope $scope)
    {
    	$environment = $scope->getEnvironment();
        $contexts = $environment->getContexts();
        foreach ($contexts as $context) {
            if($context instanceof BaseDataContext){
                $this->setDataContext($context);
                break;
            }
        }
    }

    public function setDataContext(BaseDataContext $dataContext) 
    {
        $this->dataContext = $dataContext;
        $this->kernel = $this->dataContext->getKernel();
        $this->container = $this->kernel->getContainer();
        $this->dataContext->setContainer($this->container);
        return $this;
    }

    /**
     * @BeforeStep
     */
    public function beforeStep()
    {
        if($this->dataContext->isOpenBrowser() && $this->isMaximized === false
                && $this->getSession()->getDriver()->isStarted()){
            $this->isMaximized = true;
            $this->getSession()->getDriver()->maximizeWindow();
        }
    }
    
    /**
     * @When I create oauth request
     */
    public function iCreateOauthRequest()
    {
        $this->dataContext->createClient();
        $this->dataContext->initRequest();
        $this->serverParameters = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json'
        ];
        $this->dataContext->getClient()->setServerParameters($this->serverParameters);
    }

    /**
     * Se loguea con un usuario en la api
     * @Given I am logged in api as :username
     * @Given I am logged in api as :username and :usePassword
     */
    public function iAmLoggedInApiAs($username, $usePassword = null)
    {
        $this->iCreateOauthRequest();
        if ($usePassword !== null) {
            $password = $usePassword;
        } else {
            $password = $username;
        }
        $this->dataContext->setRequestBody("_username", $username);
        $this->dataContext->setRequestBody("_password", $password);
        $this->dataContext->iMakeAAccessTokenRequest();
        if ($this->dataContext->theResponseStatusCodeIs("200")) {
            $token = $this->dataContext->getPropertyValue("token");
            $this->dataContext->getClient()->setServerParameter("HTTP_AUTHORIZATION", sprintf("Bearer %s", $token));
            $this->dataContext->setCurrentUser($this->dataContext->findUser($username));
        }
    }    

    /**
     * Agrega data tipo json al siguiente request
     * @Given I add the request data:
     */
    public function iAddTheRequestData(PyStringNode $string,$andSave = false)
    {
        $parameters = json_decode((string) $string, true);
        if ($parameters === null) {
            throw new \Exception(sprintf("Json invalid: %s, %s", json_last_error_msg(), json_last_error()));
        }
        $this->dataContext->replaceParameters($parameters);
        foreach ($parameters as $key => $row) {
            $this->dataContext->setRequestBody($key, $row);
        }
        if($andSave === true){
            $this->lastRequestBodySave = $parameters;
        }else{
            $this->lastRequestBodySave = null;
        }
    }    

    /**
     * Agrego parametros al request
     * @When I add the request parameters:
     */
    public function iAddTheRequestParameters(TableNode $parameters)
    {
        if ($parameters !== null) {
            foreach ($parameters->getRowsHash() as $key => $row) {
                $row = trim($row);
                $row = $this->dataContext->parseParameter($row);
                $this->dataContext->setRequestBody($key, $row);
            }
        }
    }

    /**
     * @Given I should see :text appear
     */
    public function iShouldSeeAppear($text)
    {
        $text = $this->dataContext->parseParameter($text,[],"titles");

       	$this->dataContext->spin(function($context) use ($text) {
           /** @var $context FeatureContext */
           return $context->getSession()->getPage()->hasContent($text);
       	},15,function() use ($text){
           echo sprintf("No se encontro el texto '%s'",$text);
       	});
    }

    public function pressButton($button)
    {
        $locator = $this->dataContext->parseParameter($button,[],"buttons");
        $this->dataContext->spin(function($context) use ($locator){
            return $context->getSession()->getPage()->findButton($locator) !== null;
        });

        try {
           parent::pressButton($locator);
        } catch (\Exception $ex) {
        //    $this->iScrollBottomAndElementAppear($locator);
        //    //esperamos 2 segundos para intentar de nuevo hacer click
        //    //$this->getSession()->wait(2 * 1000);
        //    parent::pressButton($locator);
        }
    }
}