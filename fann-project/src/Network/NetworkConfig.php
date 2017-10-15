<?php

namespace Fann\Network;

class NetworkConfig
{
    private $dataSets;
    private $inputVectorCount;
    private $outputVectorCount;
    private $outputVectors;

    public function __construct($dataSets, $inputVectorCount, $outputVectorCount, $outputVectors)
    {
        $this->dataSets = $dataSets;
        $this->inputVectorCount = $inputVectorCount;
        $this->outputVectorCount = $outputVectorCount;
        $this->outputVectors = $outputVectors;
    }

    public function getDataSets()
    {
        return $this->dataSets;
    }

    public function getInputVectorCount()
    {
        return $this->inputVectorCount;
    }

    public function getOutputVectorCount()
    {
        return $this->outputVectorCount;
    }

    public function getOutputVectors()
    {
        return $this->outputVectors;
    }
}
