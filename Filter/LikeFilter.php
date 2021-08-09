<?php

declare(strict_types=1);

namespace Xaben\DataFilterDoctrine\Filter;

use Xaben\DataFilter\Filter\Filter;

class LikeFilter extends DoctrineFilter implements Filter
{
    public function getFilter(mixed $value): array
    {
        if ($this->isEmpty($value)) {
            return [];
        }

        $parameterName = $this->getParameterName();
        $result[$parameterName]['statement'] = sprintf("%s LIKE :%s ESCAPE '!'", $this->columnName, $parameterName);
        $result[$parameterName]['parameters'][$parameterName] = $this->dataType->prepare($value);

        return $result;
    }
}
