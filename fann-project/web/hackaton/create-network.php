<?php

require __DIR__ . '/../../app/bootstrap.php';

$input_data = __DIR__ . '/passenger-count.data';
$num_input = 4;
$num_neurons_hidden = 5;
$num_output = 1;
$num_layers = 3;
$desired_error = 0.001;
$max_epochs = 999999;
$epochs_between_reports = 1;

echo "Desired error rate: {$desired_error}<br />\n";
echo "Data input: {$input_data}<br />\n";
$ann = fann_create_standard($num_layers, $num_input, $num_neurons_hidden, $num_output);


if ($ann) {

    fann_set_activation_function_hidden($ann, FANN_SIGMOID);
    fann_set_activation_function_output($ann, FANN_SIGMOID);
    $epochCounter = 0;
    $report = function($ann, $train, $maxExpochs, $epochsBetweenReport, $desiredError, $currentEpoch) use (&$epochCounter) {
        $mse = fann_get_MSE($ann);
        echo "$currentEpoch $mse<br />";
        $epochCounter = $currentEpoch;
        return true;
    };
    fann_set_callback($ann, $report);

    Tracy\Debugger::timer();
    if (fann_train_on_file($ann, $input_data, $max_epochs, 1000, $desired_error)) {
        $mse = fann_get_MSE($ann);
        echo "Network mse: {$mse}<br />\n";
        fann_save($ann, __DIR__ . '/passenger-count.net');
    } else {
        echo "Train on file failed\n";
    }
    $time = Tracy\Debugger::timer();
    echo "Learning {$epochCounter} epochs take: {$time}seconds\n";
    fann_destroy($ann);
} else {
    echo "Cannot create network\n";
}
