<?php

namespace App\ResourceStrategy\User;

use App\ResourceStrategy\ResourceStrategy;
use Exception;

class UserStrategy extends ResourceStrategy
{
    protected $warehouseService;
    protected $userService;
 
    public function config($context)
    {
    }

    public function checkPreconditions(): bool
    {
    }

    public function preProcess()
    {
    }

    public function validate()
    {
    }

    public function postProcess()
    {
    }

    protected function _checkIfCurrentUserHasRoleAdmin(): void
    {
        if (!$this->userService->currentUser($setResource=false)->isAdmin()) {
            throw new Exception("Action Denied. Current User should have ADMIN role");
        }
        
    }

    
    
    protected function _checkIfEmailNotExist(): void
    {
       $user = $this->userService->existByEmail($this->userService->getData('email'));
        if ($user){
                throw new \Exception('Email Exist', 400);
        }
    }

    protected function _checkIfEmailExistDifferentUser(): void
    {
       
        $user = $this->userService->existByEmail($this->userService->getData('email'));
        if ($user && $user->getId() != $this->userService->_getResource()->getId()){
            throw new \Exception('Email Exist', 400);
        }
        
        
    }

        
    protected function _checkPasswordPolicy(): void
    {   $password = $this->userService->getData('password'); 
        
        if ($password && !preg_match("#.*^(?=.{8,20})(?=.*[A-Za-z])(?=.*[0-9]).*$#", $password ) ){
            throw new \Exception('Password should be at least 8 characters, no more than 20 characters and had letters and numbers', 400);
        }
    }

    protected function _validateAllowedFieldsToModify()
    {
        foreach ($this->userService->getAllData() as $fieldName => $dataField) {
            if(!in_array($fieldName, $this->allowedFields())) {
                throw new Exception("The user is trying to modify fields not allowed -> '$fieldName'");
            }
        }
    }

    protected function allowedFields()
    {
        return [];
    }
}