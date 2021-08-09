<?php

declare(strict_types=1);

namespace Xaben\DataFilterDoctrine\Filter;

use Xaben\DataFilter\Filter\Filter;

class RangeFilter extends DoctrineFilter implements Filter
{
    public function getFilter(mixed $value): array
    {
        $separator = $this->options['separator'] ?? '..';
        $parameterName = $this->getParameterName();

        if ($this->isEmpty($value) || $value === $separator || !str_contains($value, $separator)) {
            return [];
        }

        [$start, $end] = explode($separator, $value, 2);

        $result = [];
        if ($start !== '' && $end !== '') {
            $result[$parameterName]['statement'] = "{$this->columnName} BETWEEN :{$parameterName}min AND :{$parameterName}max";
            $result[$parameterName]['parameters'][$parameterName . 'min'] = $this->dataType->prepare($start);
            $result[$parameterName]['parameters'][$parameterName . 'max'] = $this->dataType->prepare($end);
        } elseif ($start !== '') {
            $result[$parameterName]['statement'] = "{$this->columnName} >= :{$parameterName}";
            $result[$parameterName]['parameters'][$parameterName] = $this->dataType->prepare($start);
        } elseif ($end !== '') {
            $result[$parameterName]['statement'] = "{$this->columnName} <= :{$parameterName}";
            $result[$parameterName]['parameters'][$parameterName] = $this->dataType->prepare($end);
        }

        return $result;
    }
}
