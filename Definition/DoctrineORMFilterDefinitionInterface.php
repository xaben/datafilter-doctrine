<?php

declare(strict_types=1);

namespace Xaben\DataFilterDoctrine\Definition;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Xaben\DataFilter\Definition\FilterDefinitionInterface;

interface DoctrineORMFilterDefinitionInterface extends FilterDefinitionInterface
{
    public function getQueryBuilder(EntityRepository $repository): QueryBuilder;
}
