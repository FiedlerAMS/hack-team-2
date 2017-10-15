<?php

namespace Fann\Network;

use Iterator;

class DataSetBuilder
{

    const FLOAT_PRECISION = 8;

    private $config;
    private $data;
    private $outputPath = null;

    public function __construct(NetworkConfig $config, Iterator $dataset)
    {
        $this->config = clone $config;
        $this->data = $dataset;
    }

    public function buildDataSet($outputPath = null)
    {
        $this->outputPath = $outputPath;
        $this->buildHeader();
        foreach ($this->data as $data)
        {
            $inputs = [];
            $outputs = [];
            foreach($data as $column => $vector) {
                $vector = $this->toArray($vector);
                if (in_array($column, $this->config->getOutputVectors())) {
                    $outputs = array_merge($outputs, $vector);
                } else {
                    $inputs = array_merge($inputs, $vector);
                }
            }
            $this->addRow($inputs);
            $this->addRow($outputs);
        }
    }

    private function  buildHeader()
    {
        $this->addRow([
            $this->config->getDataSets(),
            $this->config->getInputVectorCount(),
            $this->config->getOutputVectorCount()
        ]);
    }

    private function toArray($value)
    {
        if (is_scalar($value)) {
            return [$value];
        } elseif (is_array($value)) {
            return array_values($value);
        } else {
            $msg = "Cannot convert value \"" . json_encode($value) . "\" to array";
            throw new \Exception($msg);
        }
    }

    private function addRow(array $values)
    {
        $numberFormatedValues = array_map(function($value) {
            return is_float($value) ? number_format($value, self::FLOAT_PRECISION) : $value;
        }, $values);
        unset($values);
        $line = implode(" ", $numberFormatedValues) . "\n";
        if ($this->outputPath) {
            file_put_contents($this->outputPath, $line, FILE_APPEND);
        } else {
            echo str_replace("\n", "<br />", $line);
        }
    }
}
