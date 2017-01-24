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

$sqlxrefdel = "DELETE FROM student_class
           WHERE student_id = :studentid";

$vars = array(':studentid'=>$studentid);
pdoDelete($sqlxrefdel, $vars);




foreach($classids as $classid){

$sqlxref = "INSERT INTO student_class (class_id, student_id)
            VALUES(:classid, :studentid)";

$vars = array(':classid'=>$classid, ':studentid'=>$studentid);

pdoInsert($sqlxref, $vars);
            };


$vars = array(':studentname'=>$studentname, ':studentid'=>$studentid);

$sql = "UPDATE student
        SET student_name = :studentname
        WHERE student_id = :studentid";

pdoUpdate($sql, $vars);