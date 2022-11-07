<?php

use App\Helper\Utils;

require_once('vendor/autoload.php');

$recorridos = 1;
$time_start = microtime(true);

$total = 0;
$sum = 0;
$sum2 = 0;

$fp_tiv = fopen('results/tiv.csv', 'w'); //segund archivo las sumatorias

fputcsv($fp_tiv, array('county', 'tiv_2012', 'line', 'total'));

$csvPath = "data/FL_insurance_sample2.csv";

if (($handle = fopen($csvPath, "r")) !== FALSE) { //archivo a leer
    $data = fgetcsv($handle, 10000, ",");
    $data = array_shift($data);

    $lastCounty = '';
    $lastLine = 'Residential';

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        $recorridos++;

        $county = $data[2];
        $tiv2012 = $data[8];
        $line = $data[15];

        if ($lastCounty != $county && $lastCounty == '') {
            $lastCounty = $county;
        }

        if ($lastLine != $line) {
            $lastLine = $line;
        }

        if ($lastCounty == $county) {
            $lastCounty = $county;
            $lastLine = $line;

            if ($lastLine == 'Residential') {
                $sum = $sum + $tiv2012;
                $total = $sum;
            } else {
                $sum2 = $sum2 + $tiv2012;
                $total = $sum2;
            }
            fputcsv($fp_tiv, array($county, $tiv2012, $line, $total));
        } else {
            $lastCounty = $county;
            $lastLine = $line;
            $sum = 0;
            $sum2 = 0;
        }
    }


    echo "Rows: $recorridos \n";
    fclose($fp_tiv); //archivo de result
    fclose($handle); //archivo_origen


    $time_end = microtime(true);
    $time = $time_end - $time_start;
    $minutes = ($time_end - $time_start) / 60;

    echo "Duration: $time seconds\n";
    echo "Duration: $minutes seconds\n";
}
