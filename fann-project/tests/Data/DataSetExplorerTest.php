<?php

namespace Fann\Data;

use PHPUnit\Framework\TestCase;

class DataSetExplorerTest extends TestCase
{

    /** @dataProvider getCountData */
    public function testGetCount($input, $count)
    {
        $data = new \ArrayIterator($input);
        $explorer = new DataSetExplorer($data, ['irrelevant']);
        $this->assertEquals($count, $explorer->getCount());
    }

    public function getCountData()
    {
        return [
            'Regular input' => [
                [['col' => 'foo'], ['col' => 'bar'], ['col' => 'baz']],
                3
            ],
            'Empty input' => [
                [],
                0
            ]
        ];
    }

    /** @dataProvider getData */
    public function testInputOutputVectorSize($input, $outputCols, $inputSize, $outputSize)
    {
        $data = new \ArrayIterator($input);
        $explorer = new DataSetExplorer($data, $outputCols);
        $this->assertEquals(
            $inputSize,
            $explorer->getInputVectorSize(),
            "Invalid input vector size"
        );
        $this->assertEquals(
            $outputSize,
            $explorer->getOutputVectorSize(),
            "Invalid output vector size"
        );
    }

    public function getData()
    {
        $row = ['foo' => [1, 2, 3, 4], 'bar' => 'three', 'baz' => ['0', '1', '0'], 'qux' => 'string'];
        return [
            'regularCase' => [
                [$row],
                ['baz', 'qux'],
                5,
                4
            ]
        ];
    }

}
