<?php
namespace App\EntityType;

class RegionType extends EntityType
{

    protected function buildFields()
    {
        $this->config('snake_case')
            ->field('id')
            ->field('name')
            ->field('country', [
                'type' => CountryType::class
            ]);
    }
}


