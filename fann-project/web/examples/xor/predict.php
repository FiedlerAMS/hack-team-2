<?php

require __DIR__ . '/../../../app/bootstrap.php';

$ann = fann_create_from_file(__DIR__ . '/xor.net');

$prediction = fann_run($ann, [1, 0]);

echo "Prediction of input [1, 0] is " . round($prediction[0]);