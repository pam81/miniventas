<?php

namespace App\ResourceStrategy\User;
use Exception;

class AdminUpdateUserStrategy extends UserStrategy
{
    public function config($context)
    {
        $this->userService = $context['userService'];
      
    }

    public function checkPreconditions(): bool
    {   
        return $this->userService->getResource() ? true : false ;
    }

    public function preProcess()
    {  
       
        if ($this->userService->getData('password')){
            $this->_checkPasswordPolicy();
            $this->userService->setPassword();
        }
        
        
    }

    public function validate()
    { 
       if ($this->userService->getData('email') && $this->userService->getData('email') !== $this->userService->getResource()->getEmail()){
        throw new Exception("Change email could not be done");
       }
    }

    public function postProcess()
    {   
        $this->userService->validateResource();
        $this->userService->populateResource();
        $this->userService->persist();
    }

    protected function allowedFields()
    {
        return [
            'name',
            'lastname',
            'roles',
            'password'
        ];
    }
}