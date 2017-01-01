<?php
//MySQL Details
$host = "localhost";
$user = "root";
$password = "1234";
$database = "movie-db";

$db = new mysqli($host,$user,$password,$database);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//Disk Containing Movies
define("MV_PATH",'Z:'.DIRECTORY_SEPARATOR.'movies');
//define("MV_PATH",'E:'.DIRECTORY_SEPARATOR.'Movies');
define("WEB_URL",'http://www.mdb.lk');
#################################
#		Folder Structure						#
#		Drive 				Z:						#
#		Movie Folder	movies				#
#		Language			english				#
#		Movie Name		ABCD [2013]		#
#################################
require_once("includes/imdb.class.php");
require_once("functions.php");
?>