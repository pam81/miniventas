<?php
namespace App\ApiRequest;

class RegionUpdateRequest extends ApiRequest
{

    protected function buildParameters()
    {
        $this->add('name', [
            'required' => true
        ])
        
        ;
    }
}