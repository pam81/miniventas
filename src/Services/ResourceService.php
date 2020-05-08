<?php

namespace App\Services;

use App\Exception\ResourceNotFoundException;
use App\Exception\ResourceNotValidException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface; 
use Exception;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class ResourceService
{
    public $repository;
    protected $resource;
    protected $logger;
    protected $em;
    protected $validator;
    protected $populator;
    protected $data;
    protected $serializer;
    protected $security;

    protected $metadata;
    protected $resourceList;
    protected $resourceArray;
    protected $resourceType;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager, ValidatorInterface $validator, ObjectPopulatorService $populator, SerializerInterface $serializer, Security $security)
    {
        $this->resource = null;
        $this->logger = $logger;
        $this->em = $entityManager;
        $this->repository = $this->em->getRepository('App:' . $this->getResourceClassName());
        $this->validator = $validator;
        $this->populator = $populator;
        $this->serializer = $serializer;
        $this->security = $security;

        $this->resourceList = [];
        $this->metadata = null;
        $this->resourceType = null;
        $this->resourceArray = null;
    }

    public function getResourceList()
    {
        return $this->resourceList;
    }

    public function setResourceList($resourceList)
    {
        $this->resourceList = $resourceList;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    public function getResourceArray()
    {
        return $this->resourceArray;
    }

    public function setResourceArray($resourceArray)
    {
        $this->resourceArray = $resourceArray;
    }

    public function getResourceType()
    {
        return $this->resourceType;
    }

    public function setResourceType($resourceType)
    {
        $this->resourceType = $resourceType;
    }

    abstract protected function getResourceClassName(): string;

    protected function beforeUpdatePersisting()
    {
    }

    protected function afterUpdate()
    {
    }

    public function newResource()
    {
        $this->resource = $this->getNewResource();
    }

    abstract protected function getNewResource();

    public function populateResource()
    {
        $this->populate();
    }

    protected function populate()
    {
        $this->checkIfResourceWasSet();
        $metadata = $this->em->getClassMetadata('App:' . $this->getResourceClassName());
        $this->populator->populateObject($this->data, [], $this->resource, $metadata);
    }

    public function checkIfResourceWasSet()
    {
        if (!$this->resource) {
            throw new ResourceNotFoundException($this->getResourceClassName() . " doesn't exist", 404, 404);
        }
    }

    public function create($data = array())
    {
        $this->data = $data;
        if ($this->createCondition()) {
            $this->resource = $this->getNewResource();
            $this->beforeCreatePopulation();
            $this->populate();
            $this->beforeCreateValidation();
            $this->validateResource();
            $this->beforeCreatePersisting();
            $this->persist();
            $this->afterCreate();
            return $this->resource;
        }
        return $this->ifResourceCouldNotBeCreated();
    }

    protected function createCondition()
    {
        return true;
    }

    protected function beforeCreatePopulation()
    {
    }

    protected function beforeCreateValidation()
    {
    }

    public function validateResource()
    {
        $this->checkIfResourceWasSet();

        $errors = $this->validator->validate($this->resource);
        if (count($errors) > 0) {
            $errorList = [];
            foreach ($errors as $error) {
                $errorList[] = $error->getMessage();
            }

            $errorList = implode("\n", $errorList);

            throw new ResourceNotValidException((string)$errorList, 400, 400);
        }
    }

    protected function beforeCreatePersisting()
    {
    }

    public function persist($flush = true)
    {
        $this->checkIfResourceWasSet();

        if (method_exists($this->resource, 'setUpdatedAt')) {
            $this->resource->setUpdatedAt(new DateTime());
        }

        $this->em->persist($this->resource);
        if ($flush) {
            $this->em->flush();
        }
        if($this instanceof Historiable) {
            $this->registerHistory();
        }
        return $this->resource;
    }

    protected function afterCreate()
    {
    }

    protected function ifResourceCouldNotBeCreated()
    {
        return null;
    }

    public function update($data)
    {
        $this->data = $data;
        if ($this->updateCondition()) {
            $this->beforeUpdatePopulation();
            $this->populate();
            $this->beforeUpdateValidation();
            $this->validateResource();
            $this->beforeUpdatePersisting();
            $this->afterUpdate();
            $this->persist();
            return $this->resource;
        }
        return $this->ifResourceCouldNotBeUpdated();
    }

    protected function updateCondition()
    {
        return true;
    }

    protected function beforeUpdatePopulation()
    {
    }

    protected function beforeUpdateValidation()
    {
    }

    public function delete()
    {
        if ($this->deleteCondition()) {
            $this->checkIfResourceWasSet();
            $this->beforeDelete();
            $this->destroy();
            $this->afterDelete();
            return true;
        }
        return $this->ifResourceCouldNotBeDeleted();
    }

    protected function deleteCondition()
    {
        return true;
    }

    protected function beforeDelete()
    {
    }

    protected function destroy()
    {
        $this->em->remove($this->resource);
        $this->em->flush();
    }

    protected function afterDelete()
    {
    }

    protected function ifResourceCouldNotBeDeleted()
    {
        return null;
    }

    public function findAsArray($id)
    {
        return $this->repository
            ->select('resource')
            ->where('resource.id', $id)
            ->getOneResult();
    }

    public function _find($id)
    {
        $this->find($id);
        if (!$this->resource) throw new Exception("Resource with id $id not found.");
        return $this->resource;
    }

    public function find($id)
    {
        if ($id) {
            $this->resource = $this->repository->find($id);
            $this->checkIfResourceWasSet();
        }
        return $this->resource;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function _getResource()
    {
        if (!$this->resource) throw new Exception("Resource not set.");
        return $this->resource;
    }

    public function setResource($resource)
    {
        $this->resource = $resource;
        return $this->resource;
    }

    public function getData($key = null)
    {
        if (!$this->data) return null;
        if (!$key) return $this->data;
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }

    public function _getData($key = null)
    {
        if (empty($this->data)) throw new Exception("Empty data.");
        if (!$key) return $this->data;

        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        throw new Exception("Data field not found ('$key').");
    }

    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function getAllData()
    {
        return $this->data;
    }

    public function setAllData($data)
    {
        $this->data = $data;
    }

    public function all($filters = array(), $orderBy = array(), $limit = null, $offset = null)
    {
        return $this->repository->findBy($filters, $orderBy, $limit, $offset);
    }

    public function findOneBy($filters)
    {
        $this->resource = $this->repository->findOneBy($filters);
        return $this->resource;
    }

    public function _findOneBy($filters)
    {
        $this->resource = $this->repository->findOneBy($filters);
        if (!$this->resource) throw new Exception("Resource not found. Filters: " . print_r($filters, true));
        return $this->resource;
    }

    public function flush()
    {
        $this->em->flush();
    }

    public function permitParams(&$data, $allowedParams)
    {
        foreach ($data as $param => $value) {
            if (!in_array($param, $allowedParams)) {
                unset($data[$param]);
            }
        }
    }

    protected function ifResourceCouldNotBeUpdated()
    {
        return null;
    }

}
