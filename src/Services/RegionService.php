<?php

namespace App\Services;

use App\Entity\Region;

class RegionService extends ResourceService
{
    protected function getNewResource()
    {
        return new Region();
    }

    protected function getResourceClassName(): string
    {
        return 'Region';
    }

    public function getList($request)
    {
        return $this->repository
        ->select('region')
        ->includes('region.country', 'country')
        ->setFilters($request)
        ->setOrdering($request)
        ->paginate($request);
        
    }

    public function findAsArray($id)
    {
        return $this->repository
            ->select('region')
            ->includes('region.country', 'country')
            ->where('region.id', $id)
            ->getOneResult();
    }
}
