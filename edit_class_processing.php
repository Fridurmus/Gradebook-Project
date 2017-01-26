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

$deleteResult = pdoDelete($sqlxrefdel, $vars);

$insertResult = true;


foreach($studentids as $studentid){
    $sqlxref = "INSERT INTO student_class (class_id, student_id)
            VALUES(:classid, :studentid)";

    $vars = array(':classid'=>$classid, ':studentid'=>$studentid);

    $inputResult = pdoInsert($sqlxref, $vars);

    if($inputResult === false){
        $insertResult = false;
    }
};

$sql = "UPDATE class 
        SET class_name = :classname
        WHERE class_id = :classid";

$vars = array(':classname'=>$classname, ':classid'=>$classid);

$updateResult = pdoUpdate($sql, $vars);

if($deleteResult && $updateResult && $insertResult){
    echo '|s';
}
else{
    echo '|e';
}