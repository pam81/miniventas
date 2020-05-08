<?php
namespace App\EntityType;

class GrapeType extends EntityType
{

    protected function buildFields()
    {
        $this->config('snake_case')
            ->field('id')
            ->field('name');
    }
}


