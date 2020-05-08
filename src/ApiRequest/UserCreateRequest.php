<?php
namespace App\ApiRequest;

class UserCreateRequest extends ApiRequest
{

    protected function buildParameters()
    {
        $this->add('name', [
            'required' => true
        ])
        ->add('lastname', [
            'required' => true
        ])
        ->add('email', [
            'required' => true
        ])
        ->add('password', [
            'required' => true
        ])
        ->add('roles', [
            'required' => true
        ]);
    }
}