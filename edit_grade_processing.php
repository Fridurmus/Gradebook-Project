<?php
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/13/2017
 * Time: 11:23 AM
 */

require_once 'includes/database_functions.php';

$assignid = $_POST['assignid'];
$assignname = htmlspecialchars($_POST['assignname']);
$assigngrade = $_POST['assigngrade'];
$maxgrade = $_POST['maxgrade'];

$sql = "UPDATE gradebook 
        SET assign_name = :assignname, grade_earned = :assigngrade, grade_max = :maxgrade
        WHERE assign_id = :assignid";

$vars = array(':assignname'=>$assignname, ':assigngrade'=>$assigngrade, ':maxgrade'=>$maxgrade, ':assignid'=>$assignid);

pdoUpdate($sql, $vars);

header("Location: index.php"); // Redirect browser