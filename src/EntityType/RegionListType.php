<?php
namespace App\EntityType;

class RegionListType extends EntityType
{
    protected function buildFields() {
        $this
            ->config('snake_case')
            ->field('regions', [
                'type' => [RegionType::class]
            ])
        ;
    }

}


