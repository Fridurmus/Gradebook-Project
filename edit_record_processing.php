<?php
session_start();
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/27/2017
 * Time: 12:08 PM
 */

require_once 'includes/database_functions.php';

$gradeearned = $_POST['gradeearned'];
$assignid = $_POST['assignid'];
$studentid = $_SESSION['studentid'];
$sqlcheck = "SELECT * FROM grade
             WHERE assign_id = $assignid
             AND student_id = $studentid";

$sqlxrefdel = "DELETE FROM grade
           WHERE assign_id = :assignid AND student_id = :studentid";

$vars = array(':assignid'=>$assignid, ':studentid'=>$studentid);

$deleteResult = pdoDelete($sqlxrefdel, $vars);



$sql = "INSERT INTO grade
        VALUES (:assignid, :studentid, :gradeearned)";

$vars = array(':gradeearned'=>$gradeearned, ':assignid'=>$assignid, ':studentid'=>$studentid);

$insertResult = pdoInsert($sql, $vars);

if($insertResult !== false && $deleteResult !== false){
    echo '|s';
}
else {
    echo '|e';
}