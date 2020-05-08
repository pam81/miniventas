<?php
namespace App\EntityType;

class CompanyListType extends EntityType
{
    protected function buildFields() {
        $this
            ->config('snake_case')
            ->field('companies', [
                'type' => [CompanyType::class]
            ])
        ;
    }

}


