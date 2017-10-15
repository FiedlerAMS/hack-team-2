<?php

namespace Fann\Data;

use PHPUnit\Framework\TestCase;

class ExtractorTest extends TestCase
{

    /** @dataProvider getDataSet */
    public function testExtractUniqueValues($dataset, $column, $output)
    {
        $values = Extractor::extractUniqueValues($dataset, $column);
        $this->assertSame($output, $values);
        $this->assertSameSize(array_unique($values), $values);
    }

    public function getDataSet()
    {
        return [
            'Regular input' => [
                [['col' => 'a'], ['col' => 'b'], ['col' => 'd']],
                'col',
                ['001' => 'a', '010' => 'b', '100' => 'd']
            ],
            'Regular multiple input' => [
                [['col' => 'a'], ['col' => 'a'], ['col' => 'd']],
                'col',
                ['01' => 'a', '10' => 'd']
            ],
            'Missing column' => [
                [['col' => 'a'], ['col' => 'b'], ['another' => 'd']],
                'col',
                ['01' => 'a', '10' => 'b']
            ],
            'Empty dataset' => [
                [['another' => 'd']],
                'col',
                []
            ],
        ];
    }
}
