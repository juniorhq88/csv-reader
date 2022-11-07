<?php
require_once('vendor/autoload.php');

$row = 1;
$recorridos = 1;
$tomados = 1;
$tomados_total = 1;
$time_start = microtime(true);

$fp_tiv = fopen('results/tiv.csv', 'w'); //segund archivo las sumatorias

fputcsv($fp_tiv, array('fecha', 'sum_capital', 'sum_intereses', 'sum_total'));

$array_sumas = array();
if (($handle = fopen("data/FL_insurance_sample.csv", "r")) !== FALSE) { //archivo a leer
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        $recorridos++;

        if ($data[3] != 0 && $data[10] < 3) { //PLAZOVENTA

            $tomados++;
            $capital = ($data[2] - $data[6]) / $data[3];
            //CAPITAL = (IMPORTEVENTA - ENGANCHE) / PLAZOVENTA

            $interes = $data[4] / $data[3];
            //INTERES = INTERESSOBRECOMPRA / PLAZOVENTA
            $total = $capital + $interes;

            for ($i = 1; $i <= $data[3]; $i++) {

                $tomados_total++;

                $dt = strtotime($data[1]);
                $date = date("Y-m-d", strtotime("+" . $i . " month", $dt));
                $dateI = date("Ymd", strtotime($date));


                if (array_key_exists($dateI, $array_sumas)) {
                    //si la fecha existe en mis indices


                    $array_sumas[$dateI] = array(
                        $date,
                        ($array_sumas[$dateI][1] + $capital),
                        ($array_sumas[$dateI][2] + $interes),
                        ($array_sumas[$dateI][3] + $total)
                    );
                } else { //agregar como nuevo indice

                    $array_sumas[$dateI] = array($date, $capital, $interes, $total);
                }
            }
        }
    }



    foreach ($array_sumas as $key => $dia) {
        fputcsv($fp_sum, $dia);
    }

    echo "Rows: $recorridos \n";
    echo "Taken: $tomados \n";
    echo "TakenX: $tomados_total \n";
    fclose($fp_tiv); //archivo de sumas
    fclose($handle); //archivo_origen





    $time_end = microtime(true);
    $time = $time_end - $time_start;
    $minutes = ($time_end - $time_start) / 60;

    echo "Duration: $time seconds\n";
    echo "Duration: $minutes seconds\n";
}
