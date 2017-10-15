<?php

namespace Fann\Config;

use PHPUnit\Framework\TestCase;

class NumericConfigurationTest extends TestCase
{

    /** @dataProvider getMapToRangeData */
    public function testMapToRange($value, $min, $max, $rangeMin, $rangeMax, $expected)
    {
        $this->assertEquals(
            $expected,
            NumericConfiguration::mapToRange($value, $min, $max, $rangeMin, $rangeMax),
            'Map to range fail'
        );
    }

    public function getMapToRangeData()
    {
        return [
            'Regular input' => [
                'value' => 50,
                'min' => 0,
                'max' => 100,
                'rangeMin' => 0,
                'rangeMax' => 1,
                'expected' => 0.5
            ],
            'Regular input max' => [
                'value' => 20,
                'min' => 10,
                'max' => 30,
                'rangeMin' => 1,
                'rangeMax' => 2,
                'expected' => 1.5,
            ],
            'Input over max' => [
                'value' => 12,
                'min' => 0,
                'max' => 10,
                'rangeMin' => 0,
                'rangeMax' => 1,
                'expected' => 1.2,
            ]
        ];
    }
}
