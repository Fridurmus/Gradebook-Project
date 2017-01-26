<?php
    session_start();
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/13/2017
 * Time: 11:23 AM
 */

require_once 'includes/database_functions.php';

$assignid = $_POST['assignid'];
$assignname = htmlspecialchars($_POST['assignname']);
$maxgrade = $_POST['maxgrade'];
$classid = $_SESSION['classid'];

$sql = "UPDATE gradebook 
        SET assign_name = :assignname, grade_max = :maxgrade
        WHERE assign_id = :assignid";

$vars = array(':assignname'=>$assignname, ':maxgrade'=>$maxgrade, ':assignid'=>$assignid);

$updateResult = pdoUpdate($sql, $vars);

if($updateResult){
    echo '|s';
}
else {
    echo '|e';
}
//$gradesql = "UPDATE grade
//             SET grade_earned = :assigngrade
//             WHERE assign_id = :assignid";
//
//$vars = array(':assigngrade'=>$assigngrade, ':assignid'=>$assignid);
//
//pdoUpdate($gradesql, $vars);