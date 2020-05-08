<?php
namespace App\ApiRequest;

class RegionCreateRequest extends ApiRequest
{

    protected function buildParameters()
    {
        $this->add('name', [
            'required' => true
        ])
        ->add('country', [
            'required' => true
        ])
        ;
    }
}