<?php

namespace App\ResourceStrategy\User;

use App\Entity\User;

class AdminCreateUserStrategy extends UserStrategy
{
    public function config($context)
    {
        $this->userService = $context['userService'];
      
    }

    public function checkPreconditions(): bool
    {
        return !$this->userService->getResource();
    }

    public function preProcess()
    {   $this->_checkIfEmailNotExist();
         $this->_checkPasswordPolicy();
        $this->userService->newResource();
        $this->userService->setPassword();
        $this->userService->populateResource();
    }

    public function validate()
    {
       
    }

    public function postProcess()
    {
        $this->userService->persist();
    }

    protected function allowedFields()
    {
        return [
            
        ];
    }
}