<?php
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/13/2017
 * Time: 11:23 AM
 */

require_once 'includes/database_functions.php';

$classid = $_POST['classid'];
$classname = htmlspecialchars($_POST['classname']);

$sql = "UPDATE class 
        SET class_name = :classname
        WHERE class_id = :classid";

$vars = array(':classname'=>$classname, ':classid'=>$classid);

pdoUpdate($sql, $vars);

header("Location: index.php"); // Redirect browser