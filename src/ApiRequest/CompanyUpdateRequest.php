<?php
namespace App\ApiRequest;

class CompanyUpdateRequest extends ApiRequest
{

    protected function buildParameters()
    {
        $this->add('name', [
            'required' => true
        ])
        ->add('countries', [
            'required' => false
        ])
        ->add('regions', [
            'required' => false
        ]);
    }
}