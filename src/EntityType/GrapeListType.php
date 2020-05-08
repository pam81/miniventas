<?php
namespace App\EntityType;

class GrapeListType extends EntityType
{
    protected function buildFields() {
        $this
            ->config('snake_case')
            ->field('grapes', [
                'type' => [GrapeType::class]
            ])
        ;
    }

}


