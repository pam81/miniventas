<?php
namespace App\ApiRequest;

class CountryCreateUpdateRequest extends ApiRequest
{

    protected function buildParameters()
    {
        $this->add('name', [
            'required' => true
        ]);
    }
}