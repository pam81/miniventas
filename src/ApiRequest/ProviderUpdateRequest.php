<?php
namespace App\ApiRequest;

class ProviderUpdateRequest extends ApiRequest
{

    protected function buildParameters()
    {
        $this->add('name', [
            'required' => true
        ])
        ->add('lastname', [
            'required' => true
        ])
        ->add('address', [
            'required' => false
        ])
        ->add('comment', [
            'required' => false
        ])
        ->add('cellphone', [
            'required' => false
        ]);
    }
}