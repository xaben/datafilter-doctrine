<?php

declare(strict_types=1);

namespace Xaben\DataFilterDoctrine\Filter;

use Xaben\DataFilter\Exception\InvalidValueException;
use Xaben\DataFilter\Filter\Filter;

class InArrayFilter extends DoctrineFilter implements Filter
{
    public function getFilter(mixed $value): array
    {
        if ($this->isEmpty($value)) {
            return [];
        }

        $parameterName = $this->getParameterName();
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


        return [
            $parameterName => [
                'statement' => sprintf('%s IN (:%s)', $this->columnName, $parameterName),
                'parameters' => [
                    $parameterName => $values
                ]
            ]
        ];
    }

    protected function isValid(mixed $values): bool
    {
        if (!is_array($values)) {
            return false;
        }

        if (empty($this->options['allowedValues'])) {
            return true;
        }

        return count(array_diff($values, $this->options['allowedValues'])) === 0;
    }
}
