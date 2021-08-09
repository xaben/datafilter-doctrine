<?php

declare(strict_types=1);

namespace Xaben\DataFilterDoctrine\Filter;

use Xaben\DataFilter\Filter\AbstractFilter;
use Xaben\DataFilter\Filter\FilterInterface;

class LikeFilter extends AbstractFilter implements FilterInterface
{
    public function getFilter($value): array
    {
        if ($this->isEmpty($value)) {
            return [];
        }

        $result[$this->name]['statement'] = sprintf("%s LIKE :%s ESCAPE '!'", $this->columnName, $this->name);
        $result[$this->name]['parameters'][$this->name] = $this->dataType->prepare($value);

        return $result;
    }
}
