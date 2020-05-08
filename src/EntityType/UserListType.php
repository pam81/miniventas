<?php
namespace App\EntityType;

class UserListType extends EntityType
{
    protected function buildFields() {
        $this
            ->config('snake_case')
            ->field('users', [
                'type' => [UserType::class]
            ])
        ;
    }

}