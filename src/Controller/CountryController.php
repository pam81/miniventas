<?php

namespace App\Controller;

use App\ApiRequest\CountryCreateUpdateRequest;
use App\EntityType\CountryListType;
use App\Services\ApiResponderService;
use App\Services\CountryService;
use App\EntityType\CountryType;
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
class CountryController extends AbstractFOSRestController
{

    /**
     *
     * Return a list of Countries
     *
     * @SWG\Tag(name="Country")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Countries information successfully given",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Country::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Countries doesn't exist",
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
     * @Rest\Get("/countries", name="country_list")
     *
     *
     */
    public function list(Request $request, CountryService $countryService, ApiResponderService $apiResponder, CountryListType $countryListType): Response
    {
        try {
            $countries = $countryService->getList($request);
            return $apiResponder->getResponse([
                'countries' => $countries->getResult()], 200, $countries->getMetadata(), $countryListType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

     /**
     *
     * Get Country information
     *
     * @SWG\Tag(name="Country")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Country information successfully given",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Country::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Country doesn't exist",
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
     *     description="Id of the Country",
     *     schema={},
     *     required=true
     * )
     *
     * @Rest\Get("/countries/{id}", name="country_get")
     * 
     *
     */
    public function read($id, CountryService $countryService, ApiResponderService $apiResponder,
     CountryType $countryType)
    {
        try {
            $country = $countryService->findAsArray($id);
            return $apiResponder->getResponse($country, 200, null, $countryType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    /**
     *
     * Create a Country
     *
     * @SWG\Tag(name="Country")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Country was successfully created",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Country::class)),
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
     *     description="The name of the Country",
     *     schema={},
     *     required=true
     * )
     *
     * 
     * @Rest\Post("/countries", name="country_create")
     * 
     *
     */
    public function create(Request $request, CountryService $countryService, 
    CountryCreateUpdateRequest $countryCreateRequest, CountryType $countryType, ApiResponderService $apiResponder)
    {
        try {
            $country = $countryService->create($countryCreateRequest->submitData($request));
            $country = $countryService->findAsArray($country->getId());
            return $apiResponder->getResponse($country, 200, null, $countryType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    /**
     *
     * Update Country
     *
     * @SWG\Tag(name="Country")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Country information successfully updated",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Country::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Country doesn't exist",
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
     *     description="The name of the Country",
     *     schema={},
     *     required=true
     * )
     *
     * 
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer",
     *     description="Id of the Country",
     *     schema={},
     *     required=true
     *)
     *
     * @Rest\Put("/countries/{id}", name="country_update")
     * 
     *
     */
    public function update($id, Request $request, CountryService $countryService, 
    CountryCreateUpdateRequest $countryUpdateRequest, CountryType $countryType, ApiResponderService $apiResponder)
    {
        try {
            $countryService->find($id);
            $countryService->update($countryUpdateRequest->submitData($request));
            $country = $countryService->findAsArray($id);
            return $apiResponder->getResponse($country, 200, null, $countryType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    /**
     *
     * Delete Country
     *
     * @SWG\Tag(name="Country")
     *
     * @SWG\Response(
     *     response=204,
     *     description="Country deleted",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Country::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Country doesn't exist",
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
     *     description="Id of the Country",
     *     schema={},
     *     required=true
     * )
     *
     * @Rest\Delete("/countries/{id}", name="country_delete")
     *
     */
    public function delete($id, CountryService $countryService, ApiResponderService $apiResponder)
    {
        try {
            $countryService->find($id);
            $countryService->delete();
            return $apiResponder->getResponse(null, 204);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    
}


