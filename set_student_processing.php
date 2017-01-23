<?php
session_start();
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/19/2017
 * Time: 11:15 AM
 */
$_SESSION['studentid'] = $_GET['student'];
header("Location: view_record.php"); /* Redirect browser */
?>