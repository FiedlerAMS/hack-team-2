<?php

require __DIR__ . '/../../app/bootstrap.php';

$ann = fann_create_from_file(__DIR__ . '/passenger-count.net');

for ($i = 1; $i <= 4; $i++) {
    $_REQUEST["count{$i}"] = $_REQUEST["count{$i}"] ?? 0;
}

$data = [
    "count1" => $_REQUEST['count1'],
    "count2" => $_REQUEST['count2'],
    "count3" => $_REQUEST['count3'],
    "count4" => $_REQUEST['count4'],
];

$columnsConfig = unserialize(file_get_contents(__DIR__ . '/passenger-count.config'));
$final = [];
foreach($data as $column => $value) {
    $final[$column] = $columnsConfig[$column]->convertValue($value, $column);
}

$result = fann_run($ann, $final);

header('Content-Type: application/json');
$count = $columnsConfig['result']->reverse($result[0]);
echo json_encode([
    'expectedCount' => intval($count)
]);
