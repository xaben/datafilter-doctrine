<?php

declare(strict_types=1);

namespace Xaben\DataFilterDoctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Xaben\DataFilterDoctrine\Definition\DoctrineORMFilterDefinition;
use Xaben\DataFilter\Filter\CollectionFilter;
use Xaben\DataFilter\Filter\Result;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

trait DoctrineORMRepositoryFilter
{
    public function findFiltered(CollectionFilter $filter): Result
    {
        return new Result(
            $filter,
            $this->getTotalCount($filter),
            $this->getFilteredCount($filter),
            $this->getData($filter)
        );
    }

    public function getTotalCount(CollectionFilter $filter): int
    {
        return $this->getCount(
            $this->getFilterQueryBuilder($filter),
            $filter->getPredefinedCriteria()
        );
    }

    protected function getCount(QueryBuilder $qb, array $criteria): int
    {
        foreach ($criteria as $filter) {
            $qb->andWhere($filter['statement']);
            if (isset($filter['parameters'])) {
                foreach ($filter['parameters'] as $key => $value) {
                    $qb->setParameter($key, $value);
                }
            }
        }

        $qb->select('COUNT(1)');

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    private function getFilterQueryBuilder(CollectionFilter $filter): QueryBuilder
    {
        if (!$this instanceof EntityRepository) {
            throw new \Exception('Repository expected to be a Doctrine Repository');
        }

        $filterDefinition = $filter->getDefinition();

        if ($filterDefinition instanceof DoctrineORMFilterDefinition) {
            return $filterDefinition->getQueryBuilder($this);
        }

        return $this
            ->createQueryBuilder('object')
            ->select('object')
            ->where('1 = 1');
    }

    public function getFilteredCount(CollectionFilter $filter): int
    {
        return $this->getCount(
            $this->getFilterQueryBuilder($filter),
            $filter->getAllCriteria()
        );
    }

    protected function getData(CollectionFilter $filter): iterable
    {
        $qb = $this->getFilterQueryBuilder($filter);

        // sorting
        foreach ($filter->getSortOrder() as $sortKey => $sortOrder) {
            $qb->addOrderBy($sortKey, $sortOrder);
        }

        // filtering
        foreach ($filter->getAllCriteria() as $criteria) {
            $qb->andWhere($criteria['statement']);
            if (isset($criteria['parameters'])) {
                foreach ($criteria['parameters'] as $key => $value) {
                    $qb->setParameter($key, $value);
                }
            }
        }

        // pagination
        $qb->setFirstResult($filter->getOffset());
        $qb->setMaxResults($filter->getLimit());

        return new ArrayCollection($qb->getQuery()->getResult());
    }
}
