<?php 

/* @var $container Nette\DI\Container */
$container = require __DIR__ . '/../../../app/bootstrap.php';

/* @var $createData Fann\App\CreateDataUseCase */
$createData = $container->getService('createData');
$config = [
    'x' => Fann\Data\Standardization\Mappings::BINARY,
    'y' => Fann\Data\Standardization\Mappings::BINARY,
    'output' => Fann\Data\Standardization\Mappings::BINARY,
];

$result = $createData->__invoke(
    $config,
    __DIR__ . '/xor.csv',
    ",",
    ['output'],
    __DIR__ . '/xor.config',
    __DIR__ . '/xor.data'
);

dump($result);