<?php
namespace App\EntityType;

class CompanyType extends EntityType
{

    protected function buildFields()
    {
        $this->config('snake_case')
            ->field('id')
            ->field('name')
            ->field('countries', [
                'type' => [CountryType::class]
            ])
            ->field('regions', [
                'type' => [RegionType::class]
            ]);

    }
}


