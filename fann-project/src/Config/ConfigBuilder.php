<?php

namespace Fann\Config;

use Fann\Data\Extractor;
use Fann\Data\Standardization\Mappings;

class ConfigBuilder
{
    public function __construct()
    {
        $this->config = $this->getConfigBuilderForType();
    }

    /**
     * @throws \InvalidArgumentException
     * @return ColumnConfiguration
     */
    public function buildConfig($type, $data, $fromColumn)
    {
        if (!isset($this->config[$type])) {
            throw new \InvalidArgumentException("No config builder for type {$type}");
        }
        $configBuilder = $this->config[$type];
        $configuration = $configBuilder($data, $fromColumn);
        if (!$configuration instanceof ColumnConfiguration) {
            throw new \RuntimeException('Configuration should implement ColumnConfiguration');
        }
        return $configuration;
    }

    private function getConfigBuilderForType()
    {
        return [
            Mappings::CATHEGORICAL => function($data, $column) {
                $replacements = Extractor::extractUniqueValues($data, $column);
                return new EnumConfiguration($replacements);
            },
            Mappings::NUMERIC => function($data, $column) {
                list($min, $max, $stddev, $avg) = Extractor::extractMinMaxStddev($data, $column);
                return new NumericConfiguration($min, $max, $stddev, $avg);
            },
            Mappings::BINARY => function($data, $column) {
                return new NoConfiguration();
            },
        ];
    }
}
