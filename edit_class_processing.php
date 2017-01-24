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
$studentids = explode(',', $_POST['studentids']);

$sqlxrefdel = "DELETE FROM student_class
           WHERE class_id = :classid";

$vars = array(':classid'=>$classid);
pdoDelete($sqlxrefdel, $vars);




foreach($studentids as $studentid){

    $sqlxref = "INSERT INTO student_class (class_id, student_id)
            VALUES(:classid, :studentid)";

    $vars = array(':classid'=>$classid, ':studentid'=>$studentid);

    pdoInsert($sqlxref, $vars);
};

$sql = "UPDATE class 
        SET class_name = :classname
        WHERE class_id = :classid";

$vars = array(':classname'=>$classname, ':classid'=>$classid);

pdoUpdate($sql, $vars);