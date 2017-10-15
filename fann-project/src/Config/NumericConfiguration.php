<?php

namespace Fann\Config;

class NumericConfiguration implements ColumnConfiguration
{

    const RANGE_FROM = 0;
    const RANGE_TO = 1;

    private $min;
    private $max;
    private $stddev;
    private $avg;

    public function __construct($min, $max, $stddev, $avg)
    {
        $this->min = $min;
        $this->max = $max;
        $this->stddev = $stddev;
        $this->avg = $avg;
    }

    public function convertValue($value, $column)
    {
        if (!is_numeric($value)) {
            $value = $this->avg;
        }elseif ($value < $this->min) {
            $value = $this->min;
        } elseif($value > $this->max) {
            $value = $this->max;
        }
        return self::mapToRange(
            $value,
            $this->min,
            $this->max,
            self::RANGE_FROM,
            self::RANGE_TO
        );
    }

    public function reverse($value)
    {
        return self::mapFromRange(
            $value,
            $this->min,
            $this->max,
            self::RANGE_FROM,
            self::RANGE_TO
        );
    }

    public function mapFromRange($value, $min, $max, $rangeMin, $rangeMax)
    {
        $scaleFactor = self::scaleFactor($min, $max, $rangeMin, $rangeMax);
        $tmpValue = ($value - $rangeMin) / $scaleFactor;
        return $tmpValue + $min;
    }

    public static function mapToRange($value, $min, $max, $rangeMin, $rangeMax)
    {
        $scaleFactor = self::scaleFactor($min, $max, $rangeMin, $rangeMax);
        $tmpValue = ($value - $min) * $scaleFactor;
        return $tmpValue + $rangeMin;
    }

    private static function scaleFactor($min, $max, $rangeMin, $rangeMax)
    {
        $fromRange = $max - $min;
        $toRange = $rangeMax - $rangeMin;
        return $toRange / $fromRange;
    }
}
