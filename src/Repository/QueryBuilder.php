<?php

namespace App\Repository;

use App\Services\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Exception;
use Psr\Log\LoggerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class QueryBuilder extends ServiceEntityRepository
{
    protected $logger;
    protected $qb;
    protected $primaryAlias;
    private $page;
    private $size;

    /**
     * @param string $entityClass The class name of the entity this repository manages
     */
    public function __construct(String $className, ManagerRegistry $registry, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->qb = null;
        $this->primaryAlias = "";
        $this->className = $className;
        $this->paginator = new Paginator();
        $this->page = null;
        $this->size = null;

        parent::__construct($registry, $className);
    }

    public function queryBuilder()
    {
        $this->qb = $this->_em->createQueryBuilder();
        return $this;
    }

    public function getQueryBuilder($primaryAlias) {
        $this->qb = $this->createQueryBuilder($primaryAlias);
        return $this->qb;
    }

    public function select($selectExpresion)
    {

        $split = explode(".", $selectExpresion);
        if (count($split) == 2) {
            $this->primaryAlias = $split[0];
            $this->qb = $this->createQueryBuilder($this->primaryAlias);
            $this->qb->select($selectExpresion);
        } else {
            $this->primaryAlias = $selectExpresion;
            $this->qb = $this->createQueryBuilder($this->primaryAlias);
        }

        return $this;
    }

    public function addSelect($select)
    {
        $this->qb
            ->addSelect($select);

        return $this;
    }

    public function distinct($field)
    {
        $this->qb->distinct($field);
        return $this;
    }

    public function groupBy($field)
    {
        $this->qb->groupBy($field);
        return $this;
    }

    public function delete($primaryAlias)
    {
        $this->primaryAlias = $primaryAlias;
        $this->qb = $this->_em->createQueryBuilder();
        $this->qb->delete($this->className, $primaryAlias);
        return $this;
    }

    public function update($primaryAlias)
    {
        $this->primaryAlias = $primaryAlias;
        $this->qb = $this->_em->createQueryBuilder();
        $this->qb->update($this->className, $primaryAlias);
        return $this;
    }

    public function set($field, $value, $literal = true)
    {
        if ($value) {
            if ($literal) {
                $this->qb->set($field, $this->qb->expr()->literal($value));
            } else {
                $this->qb->set($field, $value);
            }
        } else {
            $this->qb->set($field, ':nullValue');
            $this->qb->setParameter('nullValue', null);
        }
        return $this;
    }

    public function execute()
    {
        $q = $this->qb->getQuery();

        //$this->logger->info("AXEL: {$q->getDql()}");

        return $q->execute();
    }


    public function joins($join, $aliasJoin = null, $condition = null, $joinType = "left")
    {
        if (!$aliasJoin) {
            $split = explode(".", $join);
            $aliasJoin = $split[1];
        }

        if($condition) {
            if ($joinType == "inner") {
                $this->qb->innerJoin("{$join}", $aliasJoin, 'WITH', $condition);
            } elseif ($joinType == "left") {
                $this->qb->leftJoin("{$join}", $aliasJoin, 'WITH', $condition);
            } else {
                $this->qb->rightJoin("{$join}", $aliasJoin, 'WITH', $condition);
            }
        } else {
            if ($joinType == "inner") {
                $this->qb->innerJoin("{$join}", $aliasJoin);
            } elseif ($joinType == "left") {
                $this->qb->leftJoin("{$join}", $aliasJoin);
            } else {
                $this->qb->rightJoin("{$join}", $aliasJoin);
            }
        }

        return $this;
    }

    public function findAsArray($id)
    {
      
        $entity = $this->select('entity')
                ->where('entity.id', $id)
                ->getOneResult();
        return $entity;
    }

    public function includes($join, $aliasJoin = null, $condition = null, $joinType = "left")
    {
        if (!$aliasJoin) {
            $split = explode(".", $join);
            $aliasJoin = $split[1];
        }

        if($condition) {
            if ($joinType == "inner") {
                $this->qb->innerJoin("{$join}", $aliasJoin, 'WITH', $condition);
            } elseif ($joinType == "left") {
                $this->qb->leftJoin("{$join}", $aliasJoin, 'WITH', $condition);
            } else {
                $this->qb->rightJoin("{$join}", $aliasJoin, 'WITH', $condition);
            }
        } else {
            if ($joinType == "inner") {
                $this->qb->innerJoin("{$join}", $aliasJoin);
            } elseif ($joinType == "left") {
                $this->qb->leftJoin("{$join}", $aliasJoin);
            } else {
                $this->qb->rightJoin("{$join}", $aliasJoin);
            }
        }

        $this->qb->addSelect($aliasJoin);

        return $this;

    }

    public function where($field, $data)
    {
        $parameter = $this->getParameterName($field);
        if (!is_array($data)) {
            $this->qb->andWhere("{$field} = :{$parameter}")
                ->setParameter($parameter, $data);
        } else {
            $operator = strtoupper(key($data));
            $value = current($data);

            if ($operator == 'IN') {
                $this->qb->andWhere("{$field} IN (:{$parameter})")
                    ->setParameter($parameter, $value);
            } elseif ($operator == 'NOT IN') {
                $this->qb->andWhere("{$field} NOT IN (:{$parameter})")
                    ->setParameter($parameter, $value);
            } elseif ($operator == 'IS' && ($value == NULL || strtoupper($value) == "NULL")) {
                $this->qb->andWhere("{$field} IS NULL");
            } elseif ($operator == 'IS NOT' && ($value == NULL || strtoupper($value) == "NULL")) {
                $this->qb->andWhere("{$field} IS NOT NULL");
            } else {
                $this->qb->andWhere("{$field} {$operator} :{$parameter}")
                    ->setParameter($parameter, $value);
            }
        }

        return $this;
    }

    private function getParameterName($field)
    {
        $arr = explode(".", $field);
        if (count($arr) !== 2) throw new \Exception("Alias name is required ('$field')");
        $parameterName = "{$arr[0]}_{$arr[1]}_" . rand(0,100);
        return $parameterName;
    }

    public function andWhere($dqlCondition)
    {
        $this->qb->andWhere($dqlCondition);
        return $this;
    }

    public function setParameter($parameter, $value)
    {
        $this->qb->setParameter($parameter, $value);
        return $this;
    }

    public function offset($offset)
    {
        $this->qb->setFirstResult($offset);
        return $this;
    }

    public function limit($limit)
    {
        $this->qb->setMaxResults($limit);
        return $this;
    }

    public function paginate($arg1, $arg2 = null)
    {
        if ($arg2) {
            $metadata = $this->paginator->paginatePage($this, $arg1, $arg2);
        } else {
            $metadata = $this->paginator->paginateFromRequest($this, $arg1);
        }

        $this->page = $metadata['page'];
        $this->size = $metadata['size'];

        return $this;
    }

    public function setOrdering($ordering)
    {
        if ($ordering instanceof Request) {
            $ordering = $ordering->query->get('ordering');
        }

        if (is_array($ordering)) {
            foreach ($ordering as $field => $direction) {
                $field = $this->snakeToCamelCase($field);
                $this->qb->addOrderBy($field, $direction);
            }
        }

        return $this;
    }

    public function setFilters($filters)
    {

        if ($filters instanceof Request) {
            $filters = $filters->query->get('filters');
        }

        if (is_array($filters)) {

            $key = key($filters);

            foreach ($filters as $field => $data) {
                if ($field == 'OR') {
                    $orClauses = $data;

                    $orQuery = [];
                    foreach ($orClauses as $field => $data) {
                        $field = $this->snakeToCamelCase($field);
                        $parameter = $this->getParameterName($field);

                        if (!is_array($data)) {
                            $orQuery[] = "{$field} = :{$parameter}";
                            $this->qb->setParameter($parameter, $data);
                        } else {
                            $operator = strtoupper(key($data));
                            $value = current($data);
                            if ($operator == 'IN') {
                                $orQuery[] = "{$field} IN (:{$parameter})";
                                $this->qb->setParameter($parameter, $value);
                            } elseif ($operator == 'IS') {
                                if (strtoupper($value) == "NULL") {
                                    $orQuery[] = "{$field} IS NULL";
                                }

                            } elseif ($operator == 'IS NOT') {
                                if (strtoupper($value) == "NULL") {
                                    $orQuery[] = "{$field} IS NOT NULL";
                                }
                            } else {
                                $orQuery[] = "{$field} {$operator} :{$parameter}";
                                $this->qb->setParameter($parameter, $value);
                            }
                        }
                    }

                    $orQueryDql = implode(' OR ', $orQuery);

                    $this->qb->andWhere("({$orQueryDql})");

                } else {
                    $this->addFilter($field, $data);
                }
            }
        }

        return $this;
    }

    private function snakeToCamelCase($prop)
    {
        $alias = '';
        $components = explode('.',$prop);
        if(count($components) === 2){
            $alias = $components[0].'.';
            $prop =  $components[1];
        }
        return $alias.lcfirst(str_replace('_', '', ucwords($prop, '_')));
    }

    private function addFilter($field, $data)
    {
        $field = $this->snakeToCamelCase($field);
        $parameter = $this->getParameterName($field);
        if (!is_array($data)) {
            $dateFrom = date_create_from_format('Y-m-d h:i:s', $data . ' 00:00:00');
            if ($dateFrom) {
                $dateToCopy = date_create_from_format('Y-m-d h:i:s', $data . ' 00:00:00');;
                $dateTo = date_add($dateToCopy, date_interval_create_from_date_string('1 day'));;
                $this->qb->andWhere("{$field} between :dateFilterFrom and :dateFilterTo")
                    ->setParameter('dateFilterFrom', $dateFrom)
                    ->setParameter('dateFilterTo', $dateTo);
            } else {
                $this->qb->andWhere("{$field} = :{$parameter}")
                    ->setParameter($parameter, $data);
            }
        } else {

            $operator = strtoupper(key($data));
            $value = current($data);
            if ($operator == 'IN') {
                $this->qb->andWhere("{$field} IN (:{$parameter})")
                    ->setParameter($parameter, $value);
            } elseif ($operator == 'IS') {
                if (strtoupper($value) == "NULL") {
                    $this->qb->andWhere("{$field} IS NULL");
                }
            } elseif ($operator == 'IS NOT') {
                if (strtoupper($value) == "NULL") {
                    $this->qb->andWhere("{$field} IS NOT NULL");
                }
            } else {
                $this->qb->andWhere("{$field} {$operator} :{$parameter}")
                    ->setParameter($parameter, $value);
            }
        }
    }

    public function getMetadata()
    {
        $metadata = [];

        if ($this->page && $this->size) {
            $metadata['pagination']['page'] = $this->page;
            $metadata['pagination']['size'] = $this->size;
        }

        $this->qb->resetDQLPart("select");
        $this->qb->setFirstResult(0);
        $this->qb->setMaxResults(null);
        $this->qb->select("count(distinct({$this->primaryAlias}.id))");
        //$this->logger->info("AXEL: {$this->qb->getQuery()->getDql()}");
        $arr = $this->qb->getQuery()->getArrayResult();
        $metadata['pagination']['total'] = current($arr[0]);

        return $metadata;
    }

    public function getCount()
    {
        $this->qb->resetDQLPart("select");
        $this->qb->select("count({$this->primaryAlias}.id)");
        //$this->logger->info("AXEL: {$this->qb->getQuery()->getDql()}");
        $arr = $this->qb->getQuery()->getArrayResult();
        return current($arr[0]);
    }

    public function getSum($fieldToSum)
    {
        $this->qb->resetDQLPart("select");
        $this->qb->select("sum({$fieldToSum})");
        //$this->logger->info("AXEL: {$this->qb->getQuery()->getSql()}");
        $arr = $this->qb->getQuery()->getArrayResult();
        return floatval(current($arr[0]));
    }

    public function getAverage($fieldToCalculate)
    {
        $this->qb->resetDQLPart("select");
        $this->qb->select("avg({$fieldToCalculate})");
        //$this->logger->info("AXEL: {$this->qb->getQuery()->getSql()}");
        $arr = $this->qb->getQuery()->getArrayResult();
        return floatval(current($arr[0]));
    }

    public function getOneObject()
    {
        return $this->getObjects(true);
    }

    public function getObjects($forceOneResultOrNull = false)
    {
        //$this->logger->info("AXEL: {$this->qb->getQuery()->getDql()}");
        if (!$forceOneResultOrNull) {
            $result = $this->qb
                ->getQuery()
                ->getResult();
        } else {
            $result = $this->qb
                ->getQuery()
                ->setMaxResults(1)
                ->getOneOrNullResult();
        }

        return $result;
    }

    public function _getOneObject()
    {
        $resource = $this->getObjects(true);
        if (!$resource) throw new Exception("Resource not exist.");
        return $resource;
    }

    public function getOneResult()
    {
        return $this->getResult(true);
    }

    public function getResult($forceOneResultOrNull = false)
    {
        //$this->logger->info("AXEL: {$this->qb->getQuery()->getDql()}");
        if (!$forceOneResultOrNull) {
            $result = $this->qb
                ->getQuery()
                ->getArrayResult();
        } else {
            $result = $this->qb
                ->getQuery()
                ->setMaxResults(1)
                ->getArrayResult();
            if (count($result) > 0) {
                $result = $result[0];
            } else {
                $result = null;
            }
        }

        return $result;
    }

    public function _getOneResult()
    {
        $resource = $this->getOneResult(true);
        if (!$resource) throw new Exception("Resource not exist.");
        return $resource;
    }

    public function _getResult()
    {
        $resources = $this->getResult(true);
        if (empty($resources)) throw new Exception("Resource not exist.");
        return $resources;
    }

    public function order($orderBy, $direction = 'ASC')
    {
        $this->qb
            ->addOrderBy($orderBy, $direction);
        return $this;
    }

}