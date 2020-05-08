<?php

namespace App\Services;

use App\Entity\Grape;

class GrapeService extends ResourceService
{
    protected function getNewResource()
    {
        return new Grape();
    }

    protected function getResourceClassName(): string
    {
        return 'Grape';
    }

    public function getList($request)
    {
        return $this->repository
        ->select('grape')
        ->setFilters($request)
        ->setOrdering($request)
        ->paginate($request);
        
    }
}
