<?php

namespace Fann\Config;

class EnumConfiguration implements ColumnConfiguration
{
    private $replacements;

    public function __construct($replacements)
    {
        $this->replacements = array_flip($replacements);
    }

    public function convertValue($value, $column)
    {
        if (!isset($this->replacements[$value])) {
            $enums = implode(", ", array_keys($this->replacements));
            $columnInfo = $column ? " in column \"{$column}\"" : "";
            $err = "Value \"{$value}\" not in enums: {$enums}" . $columnInfo;
            throw new \Exception($err);
        }

        return str_split((string) $this->replacements[$value]);
    }
}
