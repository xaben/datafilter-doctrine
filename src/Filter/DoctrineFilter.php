<?php

declare(strict_types=1);

namespace Xaben\DataFilterDoctrine\Filter;

use Xaben\DataFilter\Filter\BaseFilter;

abstract class DoctrineFilter extends BaseFilter
{
    protected function getParameterName(): string
    {
        $name = $this->getName();

        if (is_int($name)) {
            return 'column_' . $name;
        }

        return strtolower(preg_replace('/[\W]/', '_', $name));
    }
}
