<?php

namespace App\Helper;

class Utils
{
    // php function to convert csv to json format
    public static function csvToJson($fname)
    {
        // open csv file
        if (!($fp = fopen($fname, 'r'))) {
            die("Can't open file...");
        }

        //read csv headers
        $key = fgetcsv($fp, "1024", ",");

        // parse csv rows into array
        $json = array();
        while ($row = fgetcsv($fp, "1024", ",")) {
            $json[] = array_combine($key, $row);
        }

        // release file handle
        fclose($fp);

        // encode array to json
        return json_encode($json);
    }

    public static function readCSV($file)
    {
        $row      = 0;
        $csvArray = array();
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
                $num = count($data);
                for ($c = 0; $c < $num; $c++) {
                    $csvArray[$row][] = $data[$c];
                }
                $row++;
            }
        }
        if (!empty($csvArray)) {
            return array_splice($csvArray, 1); //cut off the first row (names of the fields)
        } else {
            return false;
        }
    }
}
