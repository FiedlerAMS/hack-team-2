<?php

/* @var $container Nette\DI\Container */
$container = require __DIR__ . '/../../app/bootstrap.php';

/* @var $createData Fann\App\CreateDataUseCase */
$createData = $container->getService('createData');
$config = [
    'count1' => Fann\Data\Standardization\Mappings::NUMERIC,
    'count2' => Fann\Data\Standardization\Mappings::NUMERIC,
    'count3' => Fann\Data\Standardization\Mappings::NUMERIC,
    'count4' => Fann\Data\Standardization\Mappings::NUMERIC,
    'result' => Fann\Data\Standardization\Mappings::NUMERIC,
];

$result = $createData->__invoke(
    $config,
    __DIR__ . '/passenger-count.csv',
    ",",
    ['result'],
    __DIR__ . '/passenger-count.config',
    __DIR__ . '/passenger-count.data'
);
