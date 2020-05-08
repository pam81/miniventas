<?php

namespace App\Controller;

use App\ApiRequest\ProviderCreateRequest;
use App\ApiRequest\ProviderUpdateRequest;
use App\EntityType\ProviderListType;
use App\Services\ApiResponderService;
use App\Services\ProviderService;
use App\EntityType\ProviderType;
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
class ProviderController extends AbstractFOSRestController
{

    /**
     *
     * Return a list of Providers
     *
     * @SWG\Tag(name="Provider")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Providers information successfully given",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Provider::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Providers doesn't exist",
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
     * @Rest\Get("/providers", name="providers_list")
     *
     *
     */
    public function list(Request $request, ProviderService $providerService, ApiResponderService $apiResponder, 
    ProviderListType $providerListType): Response
    {
        try {
            $providers = $providerService->getList($request);
            return $apiResponder->getResponse([
                'providers' => $providers->getResult()], 200, $providers->getMetadata(), $providerListType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

     /**
     *
     * Get Provider information
     *
     * @SWG\Tag(name="Provider")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Provider information successfully given",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Provider::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Provider doesn't exist",
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
     *     description="Id of the Company",
     *     schema={},
     *     required=true
     * )
     *
     * @Rest\Get("/providers/{id}", name="provider_get")
     * 
     *
     */
    public function read($id, ProviderService $providerService, ApiResponderService $apiResponder,
     ProviderType $providerType)
    {
        try {
            $provider = $providerService->findAsArray($id);
            return $apiResponder->getResponse($provider, 200, null, $providerType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    /**
     *
     * Create a Provider
     *
     * @SWG\Tag(name="Provider")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Provider was successfully created",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Provider::class)),
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
     *     description="The name of the Provider",
     *     schema={},
     *     required=true
     * )
     *
     * 
     * @Rest\Post("/providers", name="provider_create")
     * 
     *
     */
    public function create(Request $request, ProviderService $providerService, 
    ProviderCreateRequest $providerCreateRequest, ProviderType $providerType, ApiResponderService $apiResponder)
    {
        try {
            $provider = $providerService->create($providerCreateRequest->submitData($request));
            $provider = $providerService->findAsArray($provider->getId());
            return $apiResponder->getResponse($provider, 200, null, $providerType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    /**
     *
     * Update Provider
     *
     * @SWG\Tag(name="Provider")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Provider information successfully updated",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Provider::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Company doesn't exist",
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
     *     description="The name of the Provider",
     *     schema={},
     *     required=true
     * )
     *
     * 
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer",
     *     description="Id of the Provider",
     *     schema={},
     *     required=true
     *)
     *
     * @Rest\Put("/providers/{id}", name="provider_update")
     * 
     *
     */
    public function update($id, Request $request, ProviderService $providerService, 
    ProviderUpdateRequest $providerUpdateRequest, ProviderType $providerType, ApiResponderService $apiResponder)
    {
        try {
            $providerService->find($id);
            $providerService->update($providerUpdateRequest->submitData($request));
            $provider = $providerService->findAsArray($id);
            return $apiResponder->getResponse($provider, 200, null, $providerType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    /**
     *
     * Delete Provider
     *
     * @SWG\Tag(name="Provider")
     *
     * @SWG\Response(
     *     response=204,
     *     description="Provider deleted",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Provider::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Provider doesn't exist",
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
     *     description="Id of the Provider",
     *     schema={},
     *     required=true
     * )
     *
     * @Rest\Delete("/providers/{id}", name="provider_delete")
     *
     */
    public function delete($id, ProviderService $providerService, ApiResponderService $apiResponder)
    {
        try {
            $providerService->find($id);
            $providerService->delete();
            return $apiResponder->getResponse(null, 204);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    
}


