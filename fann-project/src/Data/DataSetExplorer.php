<?php

namespace Fann\Data;

class DataSetExplorer
{

    private $outputColumns = [];
    private $count = 0;
    private $inputVectorSize = 0;
    private $outputVectorSize = 0;

    public function __construct(\Iterator $data, $outputColumns)
    {
        $this->outputColumns = $outputColumns;
        $this->initialize($data);
    }

    private function initialize(\Iterator $data)
    {
        $data->rewind();
        $this->count = iterator_count($data);
        $data->rewind();
        $currentData = (array) $data->current();
        $this->outputVectorSize = $this->calculateOutputVectorSize($currentData);
        $this->inputVectorSize = $this->calculateVectorSize($currentData) - $this->outputVectorSize;
    }

    private function calculateVectorSize(array $columns): int
    {
        $count = 0;
        foreach($columns as $value) {
            if (is_scalar($value)) {
                $count++;
            } elseif (is_array($value)) {
                $count += count($value);
            }
        }
        return $count;
    }

    private function calculateOutputVectorSize(array $columns): int
    {
        $output = array_combine($this->outputColumns, $this->outputColumns);
        return $this->calculateVectorSize(array_intersect_key($columns, $output));
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getInputVectorSize(): int
    {
        return $this->inputVectorSize;
    }

    public function getOutputVectorSize(): int
    {
        return $this->outputVectorSize;
    }
}
