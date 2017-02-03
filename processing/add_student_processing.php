<?php
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/23/2017
 * Time: 1:20 PM
 */
require_once '../includes/database_functions.php';

$studentname = $_POST['student'];

$sql = "INSERT INTO student (student_name)
        VALUES (:student_name)";

$vars = array(':student_name'=>$studentname);

$insertResult = pdoInsert($sql, $vars);

if($insertResult){
    echo '|s';
}
else{
    echo '|e';
}