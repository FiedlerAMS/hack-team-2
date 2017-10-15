<?php

namespace Fann\Data;

class Extractor
{

    public static function extractUniqueValues($iterable, $column)
    {
        $values = [];
        foreach($iterable as $row)
        {
            if (!isset($row[$column])) {
                continue;
            }
            $value = $row[$column];
            if (in_array($value, $values)) {
                continue;
            }
            $values[$value] = $value;
        }
        return self::createNNEnum($values);
    }

    public static function createNNEnum($array)
    {
        $values = array_unique($array);
        $converted = [];
        $total = count($values);
        foreach($values as $value) {
            $pow = pow(2, count($converted));
            $key = \Nette\Utils\Strings::padLeft(decbin($pow), $total, '0');
            $converted[$key] = $value;
        }
        return $converted;
    }

    public static function extractMinMaxStddev($iterable, $column)
    {
        $values = [];
        foreach($iterable as $row)
        {
            if (!isset($row[$column]) || !is_numeric($row[$column])) {
                continue;
            }
            $value = $row[$column];
            $values[] = $value;
        }
        $avg = array_sum($values) / count($values);
        $stddev = stats_standard_deviation($values);
        return [min($values), max($values), $stddev, $avg];
    }
}
