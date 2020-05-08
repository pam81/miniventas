<?php
namespace App\EntityType;

class ProviderListType extends EntityType
{
    protected function buildFields() {
        $this
            ->config('snake_case')
            ->field('providers', [
                'type' => [ProviderType::class]
            ])
        ;
    }

}


