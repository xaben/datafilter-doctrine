<?php

declare(strict_types=1);

namespace Xaben\DataFilterDoctrine\Filter;

use Xaben\DataFilter\Filter\AbstractFilter;
use Xaben\DataFilter\Filter\FilterInterface;

class RangeFilter extends AbstractFilter implements FilterInterface
{
    /**
     * @param mixed $value
     * @return array
     */
    public function getFilter($value): array
    {
        $separator = $this->options['separator'] ?? '..';

        if ($this->isEmpty($value) || $value === $separator || !str_contains($value, $separator)) {
            return [];
        }

        [$start, $end] = explode($separator, $value, 2);

        $result = [];
        if ($start !== '' && $end !== '') {
            $result[$this->name]['statement'] = "{$this->columnName} BETWEEN :{$this->name}min AND :{$this->name}max";
            $result[$this->name]['parameters'][$this->name . 'min'] = $this->dataType->prepare($start);
            $result[$this->name]['parameters'][$this->name . 'max'] = $this->dataType->prepare($end);
        } elseif ($start !== '') {
            $result[$this->name]['statement'] = "{$this->columnName} >= :{$this->name}";
            $result[$this->name]['parameters'][$this->name] = $this->dataType->prepare($start);
        } elseif ($end !== '') {
            $result[$this->name]['statement'] = "{$this->columnName} <= :{$this->name}";
            $result[$this->name]['parameters'][$this->name] = $this->dataType->prepare($end);
        }

        return $result;
    }
}
