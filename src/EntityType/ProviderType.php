<?php
namespace App\EntityType;

class ProviderType extends EntityType
{

    protected function buildFields()
    {
        $this->config('snake_case')
            ->field('id')
            ->field('name')
            ->field('lastname')
            ->field('cellphone')
            ->field('address')
            ->field('email')
            ->field('comment');
            
    }
}


