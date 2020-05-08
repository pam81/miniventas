<?php
namespace App\EntityType;

class CountryListType extends EntityType
{
    protected function buildFields() {
        $this
            ->config('snake_case')
            ->field('countries', [
                'type' => [CountryType::class]
            ])
        ;
    }

}


