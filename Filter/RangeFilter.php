<?php

declare(strict_types=1);

namespace Xaben\DataFilterDoctrine\Filter;

use Xaben\DataFilter\Filter\BaseFilter;
use Xaben\DataFilter\Filter\Filter;

class RangeFilter extends BaseFilter implements Filter
{
    public function getFilter(mixed $value): array
    {
        $separator = $this->options['separator'] ?? '..';
        $name = preg_replace('/[\W]/', '_', $this->name);

        if ($this->isEmpty($value) || $value === $separator || !str_contains($value, $separator)) {
            return [];
        }

        [$start, $end] = explode($separator, $value, 2);

        $result = [];
        if ($start !== '' && $end !== '') {
            $result[$name]['statement'] = "{$this->columnName} BETWEEN :{$name}min AND :{$name}max";
            $result[$name]['parameters'][$name . 'min'] = $this->dataType->prepare($start);
            $result[$name]['parameters'][$name . 'max'] = $this->dataType->prepare($end);
        } elseif ($start !== '') {
            $result[$name]['statement'] = "{$this->columnName} >= :{$name}";
            $result[$name]['parameters'][$name] = $this->dataType->prepare($start);
        } elseif ($end !== '') {
            $result[$name]['statement'] = "{$this->columnName} <= :{$name}";
            $result[$name]['parameters'][$name] = $this->dataType->prepare($end);
        }

        return $result;
    }
}
