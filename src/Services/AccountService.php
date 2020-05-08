<?php

namespace App\Services;

use App\Entity\Account;

class AccountService extends ResourceService
{
    protected function getNewResource()
    {
        return new Account();
    }

    protected function getResourceClassName(): string
    {
        return 'Account';
    }

    public function getList($request)
    {
        return $this->repository
        ->select('account')
        ->setFilters($request)
        ->setOrdering($request)
        ->paginate($request);
        
    }
}
