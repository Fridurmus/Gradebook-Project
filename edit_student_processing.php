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
$deleteResult = pdoDelete($sqlxrefdel, $vars);

$insertResult = true;


foreach($classids as $classid){

    $sqlxref = "INSERT INTO student_class (class_id, student_id)
                VALUES(:classid, :studentid)";

    $vars = array(':classid'=>$classid, ':studentid'=>$studentid);

    $inputResult = pdoInsert($sqlxref, $vars);

    if($inputResult === false){
        $insertResult = false;
    }
};


$vars = array(':studentname'=>$studentname, ':studentid'=>$studentid);

$sql = "UPDATE student
        SET student_name = :studentname
        WHERE student_id = :studentid";

$updateResult = pdoUpdate($sql, $vars);

if($deleteResult && $updateResult && $insertResult){
    echo '|s';
}
else{
    echo '|e';
}