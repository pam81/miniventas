<?php
namespace App\ApiRequest;

class GrapeCreateUpdateRequest extends ApiRequest
{

    protected function buildParameters()
    {
        $this->add('name', [
            'required' => true
        ]);
    }
}