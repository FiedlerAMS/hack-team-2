<?php

namespace Fann\Network;

use PHPUnit\Framework\TestCase;

class FannDataConverterTest extends TestCase
{

    public function testConvertToInputOutput()
    {
        $content = <<<DTA
5 4 3
0.625000 0.067797 0.222222 0.041667
0 0 1
0.416667 0.067797 0.166667 0.041667
0 0 1
0.500000 0.050847 0.111111 0.041667
0 0 1
0.625000 0.067797 0.222222 0.041667
0 0 1
0.625000 0.067797 0.222222 0.041667
1 0 1
DTA;
        list($inputs, $outputs) = FannDataConverter::convertToInputOutput($content);
        $this->assertEquals(5, count($inputs), "Num of inputs doesnt match");
        $this->assertEquals(4, count($inputs[0]), "Number of elements in first input row doesnt match");
        $this->assertEquals(0.166667, $inputs[1][2]);

        $this->assertEquals(5, count($outputs), "Num of outputs doesnt match");
        $this->assertEquals(3, count($outputs[0]), "Number of elements in first output row doesnt match");
        $this->assertTrue(['1', '0', '1'] === $outputs[4]);
    }
}
