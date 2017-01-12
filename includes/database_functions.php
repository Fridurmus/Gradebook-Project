<?php
/*
Page: database functions - functions needed to interact with the database
Notes: each function currently echos any errors, in a production environment this shold be changed to send an email to the admin
Version: 0.0.9
Author: CMB
Contributors: CMB, JJL, NWF
Created: 05/11/14
Last Edit: 06/26/15
Last Edited By: NWF
*/

require_once 'C:\xampp\htdocs\whatever/includes/database_config.php'; //this is an issue frontend

function pdoSelect( $sql, $vars='' ) {
	global $siteMode; //$dbHost, $dbName, $dbUser, $dbPass;

	try {
	    $conn = new PDO( "mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS );
	    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	    if( empty( $vars ) )	$stmt = $conn->query( $sql );
	    else{
				$stmt = $conn->prepare( $sql );
				$stmt->execute( $vars );
	    }
			return $stmt->fetchAll( PDO::FETCH_ASSOC );
	} catch( PDOException $e ) {
			if( $siteMode != 'l' ) echo 'ERROR: ' . $e->getMessage();
			//else mail('errors@furrylogic.net','PDO Select Error', $e->getMessage());
			return false;
	}
}


function pdoInsert( $sql, $vars ) {
	global $siteMode; //$dbHost, $dbName, $dbUser, $dbPass;

	try {
	    $conn = new PDO( "mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS );
	    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	    $stmt = $conn->prepare( $sql );
			if( $stmt->execute( $vars ) ) return $conn->lastInsertId();
			else return false;
	} catch( PDOException $e ) {
			if( $siteMode != 'l' ) echo 'ERROR: ' . $e->getMessage();
			/*else mail('errors@furrylogic.net','PDO Insert Error', $e->getMessage());*/
			return false;
	}
}

function pdoUpdate( $sql, $vars ){
	global $siteMode; //$dbHost, $dbName, $dbUser, $dbPass;

	try {
	    $conn = new PDO( "mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS );
	    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	    $stmt = $conn->prepare( $sql );
			return $stmt->execute( $vars );
	} catch( PDOException $e ) {
			if( $siteMode != 'l' ) echo 'ERROR: ' . $e->getMessage();
			//else mail('errors@furrylogic.net','PDO Update Error', $e->getMessage());
			return false;
	}
}


function pdoDelete( $sql, $vars ) {
	global $siteMode; //$dbHost, $dbName, $dbUser, $dbPass;

	try {
	    $conn = new PDO( "mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS );
	    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	    $stmt = $conn->prepare( $sql );
			return $stmt->execute( $vars );
	} catch( PDOException $e ) {
			if( $siteMode != 'l' ) echo 'ERROR: ' . $e->getMessage();
			//else mail('errors@furrylogic.net','PDO Delete Error', $e->getMessage());
			return false;
	}
}

/*
**********************************************
/////////////////CHANGE LOG//////////////////

0.0.8 -- 06/26/15 -- NWF -- Updated the emails to use new errors@furrylogic.net address instead of support.
0.0.8 -- 02/02/15 -- NWF -- commented out the emails. 
0.0.7 -- 12/15/14 -- CMB -- change confige require to include to temporairly deal with a frontend issue
0.0.6 -- 10/15/14 -- JJL -- removed whitespace and unncessary open and close php tags
0.0.5 -- 07/28/14 -- JJL -- removed base constant
0.0.4 -- 07/27/14 -- JJL -- added base constant
0.0.3 -- 05/29/14 -- CMB -- edited to use $base setting
0.0.2 -- 05/16/14 -- JJL -- updated comments to php comments
0.0.1 -- 05/11/14 -- CMB -- crated file while templating theme

**********************************************
*/
?>
