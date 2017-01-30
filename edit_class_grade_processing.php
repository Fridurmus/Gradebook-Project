<?php
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/30/2017
 * Time: 10:57 AM
 */

require_once 'includes/database_functions.php';

$gradeearned = $_POST['gradeearned'];
$assignid = $_POST['assignid'];
$studentid = $_POST['studentid'];

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