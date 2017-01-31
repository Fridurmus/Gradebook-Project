<?php
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/31/2017
 * Time: 11:14 AM
 */

$csvfile = "testfile.csv";
$openfile = fopen($csvfile, "r");
$output = [];

while(!feof($openfile)){
    array_push($output, explode(',', fgets($openfile)));
}

echo "<pre>";
print_r($output);

fclose($openfile);