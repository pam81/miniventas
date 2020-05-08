<?php
namespace App\EntityType;

class UserType extends EntityType
{

    protected function buildFields()
    {
        $this->config('snake_case')
            ->field('id')
            ->field('name')
            ->field('lastname')
            ->field('email')
            ->field('roles');
    }
}