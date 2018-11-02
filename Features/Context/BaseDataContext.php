<?php

/*
 * This file is part of the Máximo Sojo - maxtoan package.
 * 
 * (c) https://maxtoan.github.io/
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Features\Context;

use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Exception;

/**
 * Base de contexto para generar data
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
abstract class BaseDataContext extends RawMinkContext implements \Behat\Symfony2Extension\Context\KernelAwareContext 
{
    use \Behat\Symfony2Extension\Context\KernelDictionary;

    use \Symfony\Component\DependencyInjection\ContainerAwareTrait;

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
}