<?php

namespace App\Controller;

use App\Services\ApiResponderService;
use App\ApiRequest\UserCreateRequest;
use App\ApiRequest\UserUpdateRequest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\UserService;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Repository\UserRepository;    
use App\EntityType\UserType;
use App\EntityType\UserListType;

/**
 * Class UserController
 *
 * @Route("/api")
 */
class UserController extends AbstractFOSRestController
{

    /**
     *
     * Return a list of Users
     *
     * @SWG\Tag(name="Users")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Users information successfully given",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=User::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Users doesn't exist",
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
     * @Rest\Get("/users", name="users_list")
     * @IsGranted({"ROLE_ADMIN"}, statusCode=403, message="Access Denied")
     *
     */
    public function list(Request $request, UserService $userService, 
    ApiResponderService $apiResponder, UserListType $userListType): Response
    {
        try {
            $usersList = $userService->getList($request);
            return $apiResponder->getResponse([
                'users' => $usersList->getResult()], 200, $usersList->getMetadata(), $userListType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

      /**
     *
     * Get User information
     *
     * @SWG\Tag(name="User")
     *
     * @SWG\Response(
     *     response=200,
     *     description="User information successfully given",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=User::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="User doesn't exist",
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
     *     description="Id of the User",
     *     schema={},
     *     required=true
     * )
     *
     * @Rest\Get("/users/{id}", name="user_get")
     * @IsGranted({"ROLE_ADMIN"}, statusCode=403, message="Access Denied")
     *
     */
    public function read($id, UserRepository $userRepository, ApiResponderService $apiResponder, UserType $userType)
    {
        try {
            $user = $userRepository->findAsArray($id);
            return $apiResponder->getResponse($user, 200, null, $userType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    /**
     *
     * Create a User
     *
     * @SWG\Tag(name="User")
     *
     * @SWG\Response(
     *     response=201,
     *     description="User was successfully created",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=User::class)),
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
     *     description="The name of the User",
     *     schema={},
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="lastname",
     *     in="body",
     *     type="string",
     *     description="The lastname of the User",
     *     schema={},
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="email",
     *     in="body",
     *     type="string",
     *     description="email of the user",
     *     schema={},
     *     required=true
     * )
     * 
     * @SWG\Parameter(
     *     name="password",
     *     in="body",
     *     type="string",
     *     description="Password of the user",
     *     schema={},
     *     required=true
     * )
     * 
     *  @SWG\Parameter(
     *     name="roles",
     *     in="body",
     *     type="string[]",
     *     description="roles of the user",
     *     schema={},
     *     required=true
     * )
     * 
     * 
     * @Rest\Post("/users", name="user_create")
     * @IsGranted({"ROLE_ADMIN"}, statusCode=403, message="Access Denied")
     *
     */
    public function create(Request $request, UserService $userService, UserRepository $userRepository,
     UserCreateRequest $userCreateRequest, UserType $userType, ApiResponderService $apiResponder)
    {
        try {
            $user = $userService->create($userCreateRequest->submitData($request));
            $user = $userRepository->findAsArray($user->getId());
            return $apiResponder->getResponse($user, 201, null, $userType);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

     /**
     *
     * Update User
     *
     * @SWG\Tag(name="User")
     *
     * @SWG\Response(
     *     response=200,
     *     description="User information successfully updated",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=User::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="User doesn't exist",
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
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer",
     *     description="Id of the User",
     *     schema={},
     *     required=true
     *)
     * @SWG\Parameter(
     *     name="name",
     *     in="body",
     *     type="string",
     *     description="The name of the User",
     *     schema={},
     *     required=false
     * )
     *
     * @SWG\Parameter(
     *     name="lastname",
     *     in="body",
     *     type="string",
     *     description="The lastname of the User",
     *     schema={},
     *     required=false
     * )
     *
     * @SWG\Parameter(
     *     name="email",
     *     in="body",
     *     type="string",
     *     description="email of the user",
     *     schema={},
     *     required=false
     * )
     * 
     *  
     * @SWG\Parameter(
     *     name="password",
     *     in="body",
     *     type="string",
     *     description="Password of the user",
     *     schema={},
     *     required=false
     * )
     * 
     *  @SWG\Parameter(
     *     name="roles",
     *     in="body",
     *     type="string[]",
     *     description="roles of the user",
     *     schema={},
     *     required=false
     * )
     * 
     * 
     * @Rest\Put("/users/{id}", name="users_update")
     * @IsGranted({"ROLE_ADMIN"}, statusCode=403, message="Access Denied")
     *
     */
    public function update($id, Request $request, UserService $userService, UserRepository $userRepository, 
    UserUpdateRequest $userUpdateRequest, UserType $userType, ApiResponderService $apiResponder)
    {
        try {
            $userService->find($id);
            $userService->update($userUpdateRequest->submitData($request));
            $user = $userRepository->findAsArray($id);
        return $apiResponder->getResponse($user, 200, null, $userType); 
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

    /**
     *
     * Delete User
     *
     * @SWG\Tag(name="User")
     *
     * @SWG\Response(
     *     response=200,
     *     description="User successfully deleted",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="data", ref=@Model(type=User::class)),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="User doesn't exist",
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
     *     description="Id of the User",
     *     schema={},
     *     required=true
     * )
     *
     * @Rest\Delete("/users/{id}", name="users_delete")
     * @IsGranted({"ROLE_ADMIN"}, statusCode=403, message="Access Denied")
     *
     */
    public function deleteAction($id, UserService $userService, ApiResponderService $apiResponder)
    {
        try {
            $userService->find($id);
            $userService->delete();
            return $apiResponder->getResponse(null, 204);
        } catch (Exception $exception) {
            return $apiResponder->getResponse(null, $exception);
        }
    }

}
