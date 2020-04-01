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
    protected $errors;

    /**
     * Agrega un error
     * @param string|array $errors
     * @throws Exception
     */
    function addError($errors,array $parameters = null, $domain = "flashes",$atPath = null)
    {
        if ($this->errors === null) {
            $this->errors = array();
        }

        if ($errors === null || (is_array($errors) && count($errors) == 0)) {
            throw new Exception("No se puede agregar un error vacio, por favor enviar el error.");
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
     * @return boolean
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

    public function getErrors()
    {
        $errors = $this->errors;
        $this->errors = array();
        if($errors === null){
            $errors = [];
        }
        return $errors;
    }
    
    public function clearErrors()
    {
        $this->errors = array();
    }
}