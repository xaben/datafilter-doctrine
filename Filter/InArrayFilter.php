<?php

declare(strict_types=1);

namespace Xaben\DataFilterDoctrine\Filter;

use Xaben\DataFilter\Exception\InvalidValueException;
use Xaben\DataFilter\Filter\BaseFilter;
use Xaben\DataFilter\Filter\Filter;

class InArrayFilter extends BaseFilter implements Filter
{
    public function getFilter(mixed $value): array
    {
        if ($this->isEmpty($value)) {
            return [];
        }

        $values = $this->dataType->prepare($value);
        if (!$this->isValid($values)) {
            throw new InvalidValueException(
                sprintf(
                    'Invalid value passed to filter, passed values: "%s", allowed values: "%s"',
                    print_r($values, true),
                    print_r($this->options['allowedValues'], true)
                )
            );
        }

        $result[$this->name]['statement'] = sprintf('%s IN (:%s)', $this->columnName, $this->name);
        $result[$this->name]['parameters'][$this->name] = $values;

        return $result;
    }

    protected function isValid($values)
    {
        if (empty($this->options['allowedValues'])) {
            return true;
        }

        return count(array_diff($values, $this->options['allowedValues'])) === 0;
    }
}
