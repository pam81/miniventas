<?php
namespace App\ApiRequest;

class CompanyCreateRequest extends ApiRequest
{

    protected function buildParameters()
    {
        $this->add('name', [
            'required' => true
        ])
        ->add('countries', [
            'required' => true
        ])
        ->add('regions', [
            'required' => true
        ]);
    }
}