<?php

namespace App\Services;

use App\Entity\Provider;

class ProviderService extends ResourceService
{
    protected function getNewResource()
    {
        return new Provider();
    }

    protected function getResourceClassName(): string
    {
        return 'Provider';
    }

    public function getList($request)
    {
        return $this->repository
        ->select('provider')
        ->setFilters($request)
        ->setOrdering($request)
        ->paginate($request);
        
    }

   
}
