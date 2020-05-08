<?php

namespace App\Controller;

use App\ApiRequest\GrapeCreateUpdateRequest;
use App\EntityType\GrapeListType;
use App\Services\ApiResponderService;
use App\Services\GrapeService;
use App\EntityType\GrapeType;
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
class GrapeController extends AbstractFOSRestController
{

    /**
     *
     * Return a list of Grapes
     *
     * @SWG\Tag(name="Grape")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Grapes information successfully given",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Grape::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Grapes doesn't exist",
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
     * @Rest\Get("/grapes", name="grape_list")
     *
     *
     */
    public function list(Request $request, GrapeService $grapeService, 
    ApiResponderService $apiResponder, GrapeListType $grapeListType): Response
    {
        try {
            $grapes = $grapeService->getList($request);
            return $apiResponder->getResponse([
                'grapes' => $grapes->getResult()], 200, $grapes->getMetadata(), $grapeListType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

     /**
     *
     * Get Grape information
     *
     * @SWG\Tag(name="Grape")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Grape information successfully given",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Grape::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Grape doesn't exist",
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
     *     description="Id of the Grape",
     *     schema={},
     *     required=true
     * )
     *
     * @Rest\Get("/grapes/{id}", name="grape_get")
     * 
     *
     */
    public function read($id, GrapeService $grapeService, ApiResponderService $apiResponder,
                        GrapeType $grapeType)
    {
        try {
            $grape = $grapeService->findAsArray($id);
            return $apiResponder->getResponse($grape, 200, null, $grapeType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    /**
     *
     * Create a Grape
     *
     * @SWG\Tag(name="Grape")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Grape was successfully created",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Grape::class)),
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
     *     description="The name of the Grape",
     *     schema={},
     *     required=true
     * )
     *
     * 
     * @Rest\Post("/grapes", name="grape_create")
     * 
     *
     */
    public function create(Request $request, GrapeService $grapeService, 
                    GrapeCreateUpdateRequest $grapeCreateRequest, GrapeType $grapeType, ApiResponderService $apiResponder)
    {
        try {
            $grape = $grapeService->create($grapeCreateRequest->submitData($request));
            $grape = $grapeService->findAsArray($grape->getId());
            return $apiResponder->getResponse($grape, 200, null, $grapeType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    /**
     *
     * Update Grape
     *
     * @SWG\Tag(name="Grape")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Grape information successfully updated",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Grape::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Grape doesn't exist",
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
     *     description="The name of the Grape",
     *     schema={},
     *     required=true
     * )
     *
     * 
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer",
     *     description="Id of the Grape",
     *     schema={},
     *     required=true
     *)
     *
     * @Rest\Put("/grapes/{id}", name="grape_update")
     * 
     *
     */
    public function update($id, Request $request, GrapeService $grapeService, 
                    GrapeCreateUpdateRequest $grapeUpdateRequest, GrapeType $grapeType, ApiResponderService $apiResponder)
    {
        try {
            $grapeService->find($id);
            $grapeService->update($grapeUpdateRequest->submitData($request));
            $grape = $grapeService->findAsArray($id);
            return $apiResponder->getResponse($grape, 200, null, $grapeType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    /**
     *
     * Delete Grape
     *
     * @SWG\Tag(name="Grape")
     *
     * @SWG\Response(
     *     response=204,
     *     description="Grape deleted",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Grape::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Grape doesn't exist",
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
     *     description="Id of the Grape",
     *     schema={},
     *     required=true
     * )
     *
     * @Rest\Delete("/grapes/{id}", name="grape_delete")
     *
     */
    public function delete($id, GrapeService $grapeService, ApiResponderService $apiResponder)
    {
        try {
            $grapeService->find($id);
            $grapeService->delete();
            return $apiResponder->getResponse(null, 204);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    
}


