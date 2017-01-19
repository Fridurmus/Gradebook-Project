<?php
session_start();
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/19/2017
 * Time: 11:15 AM
 */
$_SESSION['classid'] = $_GET['id'];
header("Location: view_grades.php"); /* Redirect browser */
?>