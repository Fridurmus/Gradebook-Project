<?php


require_once 'includes/database_functions.php';
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/31/2017
 * Time: 11:00 AM
 */
$csvfile = "testfile.csv";
$openfile = fopen($csvfile, "w");

$gradebooksql = "SELECT *
                 FROM gradebook";

$gradebookrows = pdoSelect($gradebooksql);

foreach($gradebookrows as $gradebookrow){
    fputcsv($openfile, $gradebookrow);
}

fclose($openfile);