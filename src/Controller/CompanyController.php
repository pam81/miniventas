<?php

namespace App\Controller;

use App\ApiRequest\CompanyCreateRequest;
use App\ApiRequest\CompanyUpdateRequest;
use App\EntityType\CompanyListType;
use App\Services\ApiResponderService;
use App\Services\CompanyService;
use App\EntityType\CompanyType;
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
class CompanyController extends AbstractFOSRestController
{

    /**
     *
     * Return a list of Companies
     *
     * @SWG\Tag(name="Company")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Companies information successfully given",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Company::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Companies doesn't exist",
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
     * @Rest\Get("/companies", name="companies_list")
     *
     *
     */
    public function list(Request $request, CompanyService $companyService, ApiResponderService $apiResponder, 
    CompanyListType $companyListType): Response
    {
        try {
            $companies = $companyService->getList($request);
            return $apiResponder->getResponse([
                'companies' => $companies->getResult()], 200, $companies->getMetadata(), $companyListType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

     /**
     *
     * Get Company information
     *
     * @SWG\Tag(name="Company")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Company information successfully given",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Company::class)),
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
     * @Rest\Get("/companies/{id}", name="company_get")
     * 
     *
     */
    public function read($id, CompanyService $companyService, ApiResponderService $apiResponder,
     CompanyType $companyType)
    {
        try {
            $company = $companyService->findAsArray($id);
            return $apiResponder->getResponse($company, 200, null, $companyType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    /**
     *
     * Create a Company
     *
     * @SWG\Tag(name="Company")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Company was successfully created",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Company::class)),
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
     *     description="The name of the Company",
     *     schema={},
     *     required=true
     * )
     *
     * 
     * @Rest\Post("/companies", name="company_create")
     * 
     *
     */
    public function create(Request $request, CompanyService $companyService, 
    CompanyCreateRequest $companyCreateRequest, CompanyType $companyType, ApiResponderService $apiResponder)
    {
        try {
            $company = $companyService->create($companyCreateRequest->submitData($request));
            $company = $companyService->findAsArray($company->getId());
            return $apiResponder->getResponse($company, 200, null, $companyType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    /**
     *
     * Update Company
     *
     * @SWG\Tag(name="Company")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Company information successfully updated",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Company::class)),
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
     *     description="The name of the Company",
     *     schema={},
     *     required=true
     * )
     *
     * 
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer",
     *     description="Id of the Company",
     *     schema={},
     *     required=true
     *)
     *
     * @Rest\Put("/companies/{id}", name="company_update")
     * 
     *
     */
    public function update($id, Request $request, CompanyService $companyService, 
    CompanyUpdateRequest $companyUpdateRequest, CompanyType $companyType, ApiResponderService $apiResponder)
    {
        try {
            $companyService->find($id);
            $companyService->update($companyUpdateRequest->submitData($request));
            $company = $companyService->findAsArray($id);
            return $apiResponder->getResponse($company, 200, null, $companyType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    /**
     *
     * Delete Company
     *
     * @SWG\Tag(name="Company")
     *
     * @SWG\Response(
     *     response=204,
     *     description="Company deleted",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=Company::class)),
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
     *     name="id",
     *     in="query",
     *     type="integer",
     *     description="Id of the Company",
     *     schema={},
     *     required=true
     * )
     *
     * @Rest\Delete("/companies/{id}", name="company_delete")
     *
     */
    public function delete($id, CompanyService $companyService, ApiResponderService $apiResponder)
    {
        try {
            $companyService->find($id);
            $companyService->delete();
            return $apiResponder->getResponse(null, 204);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    
}


