<?php
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/13/2017
 * Time: 10:37 AM
 */
require_once 'includes/database_functions.php';

$assignname = $_POST['assignname'];
$assigngrade = $_POST['assigngrade'];
$maxgrade = $_POST['maxgrade'];
$classid = $_POST['classid'];

$sql = "INSERT INTO gradebook (class_id, assign_name, grade_earned, grade_max)
        VALUES (:class_id, :assign_name, :grade_earned, :grade_max)";

$vars = array(':class_id'=>$classid, ':assign_name'=>$assignname, ':grade_earned'=>$assigngrade, ':grade_max'=>$maxgrade);

pdoInsert($sql, $vars);

header("Location: view_grades.php"); /* Redirect browser */