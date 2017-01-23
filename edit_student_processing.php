<?php
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/13/2017
 * Time: 11:23 AM
 */

require_once 'includes/database_functions.php';

$studentid = $_POST['studentid'];
$studentname = htmlspecialchars($_POST['studentname']);
$classids = explode(',', $_POST['classids']);

$sql = "UPDATE student
        SET student_name = :studentname
        WHERE student_id = :studentid";

foreach($classids as $class_id){
$sqlxref = "INSERT INTO student_class (class_id, student_id)
            VALUES($class_id, $studentid)";
            };

$vars = array(':studentname'=>$studentname, ':studentid'=>$studentid);

pdoUpdate($sql, $vars);
pdoInsert($sqlxref, $vars);