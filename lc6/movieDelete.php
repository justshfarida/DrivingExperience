<?php
include("connectDB.inc.php");

if(array_key_exists('code',$_GET) and array_key_exists($_GET['code'],$_SESSION['code'])){
	$idMovie=intval($_SESSION['code'][$_GET['code']]);	
} else {
	exit();
}

//Update the validMovie column to 0, instead to delete a row in the table
$query="UPDATE movies SET validMovie=0 WHERE idMovie=$idMovie";
$mysqliResult=$mysqliObject->query($query);



//header redirection
header("Location: movieHomepage.php");


?>