<?php

/*
 * This file is part of the GTI SOLUTIONS, C.A. - J409603954 package.
 * 
 * (c) www.gtisolutions.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Component\Validator;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\ConstraintValidator as ConstraintValidatorBase;
use Maxtoan\ToolsBundle\DependencyInjection\ContainerAwareTrait;
use Maxtoan\ToolsBundle\DependencyInjection\DoctrineTrait;

/**
 * Base de validadores
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
abstract class ConstraintValidator extends ConstraintValidatorBase implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    use DoctrineTrait;
    
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
}