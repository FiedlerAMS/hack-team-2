<?php

namespace Fann\Config;

interface ColumnConfiguration
{
    public function convertValue($value, $column);
}
