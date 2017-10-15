<?php

namespace Fann\Config;

class NoConfiguration implements ColumnConfiguration
{
    public function convertValue($value, $column)
    {
        return $value;
    }
}
