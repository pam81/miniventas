<?php

namespace App\Services;

use App\Entity\Company;

class CompanyService extends ResourceService
{
    protected function getNewResource()
    {
        return new Company();
    }

    protected function getResourceClassName(): string
    {
        return 'Company';
    }

    public function getList($request)
    {
        return $this->repository
        ->select('company')
        ->setFilters($request)
        ->setOrdering($request)
        ->paginate($request);
        
    }
}
