<?php

declare(strict_types=1);

namespace Xaben\DataFilterDoctrine\Filter;

use Xaben\DataFilter\Filter\BaseFilter;
use Xaben\DataFilter\Filter\Filter;

class LikeFilter extends BaseFilter implements Filter
{
    public function getFilter(mixed $value): array
    {
        if ($this->isEmpty($value)) {
            return [];
        }

        $result[$this->name]['statement'] = sprintf("%s LIKE :%s ESCAPE '!'", $this->columnName, $this->name);
        $result[$this->name]['parameters'][$this->name] = $this->dataType->prepare($value);

        return $result;
    }
}
