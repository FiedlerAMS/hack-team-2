<?php

namespace Fann\Network;

class FannDataConverter
{

    public static function convertToInputOutputFromFile(string $path, int $from = 1, int $lines = null): array
    {
        if (!file_exists($path)) {
            throw new \Exception("Fann data file does not exist");
        }
        $content = file_get_contents($path);
        return self::convertToInputOutputFromFile($content, $from, $lines);
    }

    /**
     * @param string $content Fann data string
     * @param int $from Start is 1 becaus of line with header
     * @param int $lines Number of lines. If null then returs all lines
     * @return array [Inputs, Outputs]
     */
    public static function convertToInputOutput(string $content, int $from = 1, int $lines = null): array
    {
        $data = explode("\n", $content);
        if ($lines === null) {
            $data = array_slice($data, $from);
        } else {
            $data = array_slice($data, $from, $lines);
        }
        $inputs = $outputs = [];
        // Even = input, odd = output
        for($i = 0; $i < count($data); $i++) {
            if ($i % 2 === 0) {
                $inputs[] = explode(" ", $data[$i]);
            } else {
                $outputs[] = explode(" ", $data[$i]);
            }
        }
        return [$inputs, $outputs];
    }
}
