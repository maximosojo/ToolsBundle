<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Traits\Component;

/**
 * ErrorTrait
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait ErrorTrait
{
    /**
     * $context
     */
    protected $context;
    
    /**
     * $errors
     * @var Array
     */
    protected $errors;

    /**
     * Agrega un error
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  string|array $errors
     * @param  array|null $parameters
     * @param  string     $domain
     * @param  $atPath
     */
    function addError($errors,array $parameters = null, $domain = "flashes",$atPath = null)
    {
        if ($this->errors === null) {
            $this->errors = array();
        }

        if ($errors === null || (is_array($errors) && count($errors) == 0)) {
            throw new \Exception("No se puede agregar un error vacio, por favor enviar el error.");
        }
        if (is_array($errors)) {
            $this->errors = array_merge($this->errors, $errors);
        } else {
            if($atPath !== null && $this->context !== null){
                $builder = $this->context->buildViolation($errors,$parameters);
                if($atPath !== null){
                    $builder->atPath($atPath);
                }
                $builder->addViolation();
            }else {
                if(is_array($parameters)){
                    $errors = $this->trans($errors,$parameters,$domain);
                }
                $this->errors[] = $errors;
            } 
        }
    }

    /**
     * Retorna true si existe algun error
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Boolean
     */
    public function hasErrors()
    {
        if ($this->errors === null) {
            return false;
        }
        if($this->context !== null && $this->context->getViolations()->count() > 0){
            return true;
        }
        return (count($this->errors) > 0);
    }

    /**
     * Retorna lo errores
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Array
     */
    public function getErrors()
    {
        $errors = $this->errors;
        $this->clearErrors();

        if($errors === null){
            $errors = [];
        }

        return $errors;
    }
    
    /**
     * Limpia los errores
     * @author Máximo Sojo <maxsojo13@gmail.com>
     */
    public function clearErrors()
    {
        $this->errors = array();
    }

    /**
     * Retorna errores formateados para formularios
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return FormError
     */
    public function getFormErrors()
    {
        $errors = $this->getErrors();
        $formErrors = [];
        if (is_array($errors)) {
            foreach ($errors as $error) {
                $formErrors [] = new \Symfony\Component\Form\FormError($error);
            }
        }
        return $formErrors;
    }
}