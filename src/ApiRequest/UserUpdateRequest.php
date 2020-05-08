<?php
namespace App\ApiRequest;

class UserUpdateRequest extends ApiRequest
{

    protected function buildParameters()
    {
        $this->add('name', [
            'required' => false
        ])
        ->add('lastname', [
            'required' => false
        ])
        ->add('password', [
            'required' => false
        ])
        ->add('roles', [
            'required' => false
        ]);
       
    }
}
