<?php
namespace App\Services;
use App\ResourceStrategy\ResourceContext;
use App\Entity\User;
use App\ResourceStrategy\User\AdminCreateUserStrategy;
use Doctrine\ORM\EntityManagerInterface; 
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\ResourceStrategy\User\AdminUpdateUserStrategy;

class UserService extends ResourceService
{

    private $encoder;
    private $tokenStorage;
    private $currentUser;


    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager, 
    ValidatorInterface $validator, ObjectPopulatorService $populator, 
    SerializerInterface $serializer, Security $security, TokenStorageInterface $tokenStorage, 
    UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($logger, $entityManager, $validator, $populator, $serializer, $security);
        $this->encoder = $encoder;
        $this->tokenStorage = $tokenStorage;

    }

    public function findOneBy($filters)
    {
        $this->resource = $this->repository->findOneBy($filters);
        return $this->resource;
    }

    protected function getResourceClassName() : string 
    {
        return "User";
    }

    protected function getNewResource()
    {
        return new User();
    }

    public function existByEmail($email)
    {
        return $this->repository
            ->select('u')
            ->where('u.email', $email)
            ->getOneObject()
            ;
    }

    public function currentUser($setResource=true)
    {
        if($this->tokenStorage && $this->tokenStorage->getToken()) {
            if($this->tokenStorage->getToken()) {
                $this->currentUser = $this->tokenStorage->getToken()->getUser();
                if ($setResource) {
                    $this->resource = $this->currentUser;
                }
                return $this->currentUser;
            } else {
                $this->currentUser = null;
                return $this->currentUser;
            }
        } else {
            $this->currentUser = null;
            return $this->currentUser;
        }
    }

    public function setPassword() 
    {
        if($this->getData('password')) $this->setData('password', $this->encoder->encodePassword($this->resource, $this->getData('password')));
    }

    public function getList($request) {
        
        return $this->repository->select('user')
            ->setFilters($request)
            ->setOrdering($request)
            ->paginate($request)
        ;
    }

    public function create($data = array())
    {
        $this->setAllData($data);
        $resourceContext = new ResourceContext();
        $resourceContext
            ->setMultipleConditions(false)
            ->setContext([
                'userService' => $this,
            ])
            ->addStrategies([
                new AdminCreateUserStrategy()
            ])
            ->execute();
        /*TODO: enviar email con el usuario creado  */
        return $this->resource;
    }

    public function update($data)
    {  
        $this->setAllData($data);
        $password = $this->getData('password'); // lo tomo aqui sino me queda hasheado
      
        $resourceContext = new ResourceContext();
        $resourceContext
            ->setMultipleConditions(false)
            ->setContext([
                'userService' => $this
            ])
            ->addStrategies([
                new AdminUpdateUserStrategy()
            ])
            ->execute();
         
        /* TODO: enviar mail cuando cambia el password
        if ($password){
            $this->sendEmailPassword($password);
        }*/
        return $this->resource;
    }
    

}