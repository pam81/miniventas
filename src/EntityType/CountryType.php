<?php
namespace App\EntityType;

class CountryType extends EntityType
{

    protected function buildFields()
    {
        $this->config('snake_case')
            ->field('id')
            ->field('name');
    }
}


