<?php

namespace App\Controller;

use App\ApiRequest\RegionCreateRequest;
use App\ApiRequest\RegionUpdateRequest;
use App\EntityType\RegionListType;
use App\Services\ApiResponderService;
use App\Services\RegionService;
use App\EntityType\RegionType;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class RegionController extends AbstractFOSRestController
{

    /**
     *
     * Return a list of Regions
     *
     * @SWG\Tag(name="Region")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Regions information successfully given",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Region::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Regions doesn't exist",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="message", type="string"),
     *         @SWG\Property(property="error", type="boolean"),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Uncaught exception",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="message", type="string"),
     *         @SWG\Property(property="error", type="boolean"),
     *     )
     * )
     *
     * @Rest\Get("/regions", name="regions_list")
     *
     *
     */
    public function list(Request $request, RegionService $regionService, 
    ApiResponderService $apiResponder, RegionListType $regionListType): Response
    {
        try {
            $regions = $regionService->getList($request);
            return $apiResponder->getResponse([
                'regions' => $regions->getResult()], 200, $regions->getMetadata(), $regionListType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

     /**
     *
     * Get Region information
     *
     * @SWG\Tag(name="Region")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Region information successfully given",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Region::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Region doesn't exist",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="message", type="string"),
     *         @SWG\Property(property="error", type="boolean"),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Uncaught exception",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="message", type="string"),
     *         @SWG\Property(property="error", type="boolean"),
     *     )
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer",
     *     description="Id of the Region",
     *     schema={},
     *     required=true
     * )
     *
     * @Rest\Get("/regions/{id}", name="region_get")
     * 
     *
     */
    public function read($id, RegionService $regionService, ApiResponderService $apiResponder,
     RegionType $regionType)
    {
        try {
            $region = $regionService->findAsArray($id);
            return $apiResponder->getResponse($region, 200, null, $regionType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    /**
     *
     * Create a Region
     *
     * @SWG\Tag(name="Region")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Region was successfully created",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Region::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Invalid Data",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="message", type="string"),
     *         @SWG\Property(property="error", type="boolean"),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Uncaught exception",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="message", type="string"),
     *         @SWG\Property(property="error", type="boolean"),
     *     )
     * )
     *
     * @SWG\Parameter(
     *     name="name",
     *     in="body",
     *     type="string",
     *     description="The name of the Region",
     *     schema={},
     *     required=true
     * )
     *
     * 
     * @Rest\Post("/regions", name="region_create")
     * 
     *
     */
    public function create(Request $request, RegionService $regionService, 
    RegionCreateRequest $regionCreateRequest, RegionType $regionType, ApiResponderService $apiResponder)
    {
        try {
            $region = $regionService->create($regionCreateRequest->submitData($request));
            $region = $regionService->findAsArray($region->getId());
            return $apiResponder->getResponse($region, 200, null, $regionType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    /**
     *
     * Update Region
     *
     * @SWG\Tag(name="Region")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Region information successfully updated",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Region::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Region doesn't exist",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="message", type="string"),
     *         @SWG\Property(property="error", type="boolean"),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Invalid Data",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="message", type="string"),
     *         @SWG\Property(property="error", type="boolean"),
     *     )
     * )
     *
     * @SWG\Parameter(
     *     name="name",
     *     in="body",
     *     type="string",
     *     description="The name of the Region",
     *     schema={},
     *     required=true
     * )
     *
     * 
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer",
     *     description="Id of the Region",
     *     schema={},
     *     required=true
     *)
     *
     * @Rest\Put("/regions/{id}", name="region_update")
     * 
     *
     */
    public function update($id, Request $request, RegionService $regionService, 
    RegionUpdateRequest $regionUpdateRequest, RegionType $regionType, ApiResponderService $apiResponder)
    {
        try {
            $regionService->find($id);
            $regionService->update($regionUpdateRequest->submitData($request));
            $region = $regionService->findAsArray($id);
            return $apiResponder->getResponse($region, 200, null, $regionType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    /**
     *
     * Delete Region
     *
     * @SWG\Tag(name="Region")
     *
     * @SWG\Response(
     *     response=204,
     *     description="Region deleted",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Region::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Region doesn't exist",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="message", type="string"),
     *         @SWG\Property(property="error", type="boolean"),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Invalid Data",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="message", type="string"),
     *         @SWG\Property(property="error", type="boolean"),
     *     )
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer",
     *     description="Id of the Season",
     *     schema={},
     *     required=true
     * )
     *
     * @Rest\Delete("/regions/{id}", name="country_delete")
     *
     */
    public function delete($id, RegionService $regionService, ApiResponderService $apiResponder)
    {
        try {
            $regionService->find($id);
            $regionService->delete();
            return $apiResponder->getResponse(null, 204);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    
}


