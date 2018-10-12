<?php

/*
 * This file is part of the GTI SOLUTIONS, C.A. - J409603954 package.
 * 
 * (c) www.gtisolutions.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Validator;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Base de validadores
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class BaseConstraintValidator extends ConstraintValidator implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * Agrega un error
     * @param string|array $errors
     * @throws Exception
     */
    protected function addError($errors,array $parameters = array(),$atPath = null)
    {
        if($errors === null || count($errors) == 0){
            return;
        }
        if(is_array($errors)){
            foreach ($errors as $error) {
                $builder = $this->context->buildViolation($error,$parameters);
                if($atPath !== null){
                    $builder->atPath($atPath);
                }
                $builder->addViolation();
            }
        }else{
            $builder = $this->context->buildViolation($errors,$parameters);
            if($atPath !== null){
                $builder->atPath($atPath);
            }
            $builder->addViolation();
        }
    }
    
    /**
     * Shortcut to return the Doctrine Registry service.
     *
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     *
     * @throws \LogicException If DoctrineBundle is not available
     */
    protected function getDoctrine()
    {
        if (!$this->container->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application.');
        }

        return $this->container->get('doctrine');
    }
}
