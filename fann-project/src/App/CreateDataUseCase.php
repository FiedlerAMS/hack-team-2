<?php

namespace Fann\App;

use ArrayIterator;
use Fann\Config\ConfigBuilder;
use Fann\Data\DataSetExplorer;
use Fann\Network\DataSetBuilder;
use Fann\Network\NetworkConfig;
use Iterator;
use League\Csv\Reader;

class CreateDataUseCase
{

    private $configBuilder;

    public function __construct(ConfigBuilder $c)
    {
        $this->configBuilder = $c;
    }

    /**
     * @param $csvConfig ['colName' => Mappings::NUMERIC, 'col2' => Mappings::CATHEGORICAL]
     * @param $csvPath
     * @param $csvDelimiter ','
     * @param $outputColumns ['serviceIsChurn']
     * @param $outputConfigPath Where to save column config data
     * @param $outputDataPath Where to save data for fann
     */
    public function __invoke(
        array $csvConfig,
        string $csvPath,
        string $csvDelimiter,
        array $outputColumns,
        string $outputConfigPath,
        string $outputDataPath
    ): DataSetExplorer {

        $reader = Reader::createFromPath($csvPath);
        $reader->setDelimiter($csvDelimiter);
        $rows = $reader->fetchAssoc();

        // create config, save config and convert data
        $columnsConfig = $this->buildAndSaveColumnConfig($csvConfig, $outputConfigPath, $rows);
        $finalData = $this->convertValues($csvConfig, $columnsConfig, $rows);

        // data stats & network config
        $dataStats = new DataSetExplorer($finalData, $outputColumns);
        $networkConfig = new NetworkConfig(
            $dataStats->getCount(),
            $dataStats->getInputVectorSize(),
            $dataStats->getOutputVectorSize(),
            $outputColumns
        );

        // build data set, save data set
        @unlink($outputDataPath);
        $builder = new DataSetBuilder($networkConfig, $finalData);
        $builder->buildDataSet($outputDataPath);
        return $dataStats;
    }

    private function convertValues(
        array $csvConfig,
        array $columnsConfig,
        Iterator $rows
    ): ArrayIterator {
        $final = [];
        foreach (array_keys($csvConfig) as $column) {
            $i = 0;
            foreach ($rows as $row) {
                $final[$i][$column] = $columnsConfig[$column]->convertValue($row[$column], $column);
                $i++;
            }
        }
        return new ArrayIterator($final);
    }

    private function buildAndSaveColumnConfig(
        array $csvConfig,
        string $output,
        Iterator $rows
    ): array {
        // Create column config
        $columnsConfig = [];
        foreach ($csvConfig as $column => $mapping) {
            $columnsConfig[$column] = $this->configBuilder->buildConfig($mapping, $rows, $column);
        }

        // Save column config
        @unlink($output);
        file_put_contents($output, serialize($columnsConfig));
        return $columnsConfig;
    }
}
