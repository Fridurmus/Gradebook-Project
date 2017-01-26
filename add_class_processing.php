<?php
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/18/2017
 * Time: 12:04 PM
 */
require_once 'includes/database_functions.php';

$classname = $_POST['classname'];

$sql = "INSERT INTO class (class_name)
        VALUES (:class_name)";

$vars = array(':class_name'=>$classname);

;$insertResult = pdoInsert($sql, $vars);

if($insertResult){
    echo '|s';
}
else {
    echo '|e';
}