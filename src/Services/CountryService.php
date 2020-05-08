<?php

namespace App\Services;

use App\Entity\Country;

class CountryService extends ResourceService
{
    protected function getNewResource()
    {
        return new Country();
    }

    protected function getResourceClassName(): string
    {
        return 'Country';
    }

    public function getList($request)
    {
        return $this->repository
        ->select('country')
        ->setFilters($request)
        ->setOrdering($request)
        ->paginate($request);
        
    }
}
