<?php
ini_set('display_errors', 1);
$host='localhost:3306';//or default 3306
$user='root';
$passwd='';
$db='lc3';

echo 'PHP ' .phpversion()."<br/>";
//mysqli class https://www.php.net/manual/en/class.mysqli.php

//procedural : mysqli_connect();
//instance mysqli() class => $mysqliObject object
//https://www.php.net/manual/en/mysqli.construct.php

$mysqliObject=new mysqli($host, $user, $passwd, $db);

//procedural : mysqli_connect_errno(), mysqli_connect_error()
//mysqli class https://www.php.net/manual/en/class.mysqli.php
//connect_errno, connect_error, if()
//https://www.php.net/manual/en/mysqli.connect-errno.php
//https://www.php.net/manual/en/mysqli.connect-error.php

if($mysqliObject->connect_errno)
{
	echo "Error N° ".$mysqliObject->connect_errno.", Msg : ".$mysqliObject->connect_error."<br>";
    exit();
}
//procedural : mysqli_set_charset()
//mysqli class https://www.php.net/manual/en/class.mysqli.php
//set_charset()
//https://www.php.net/manual/en/mysqli.set-charset.php



//PHP version 8
//try ... catch... localhost
/*
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);//N7
try {


} catch (Exception $e) { 
echo "Case localhost, database : $bd, Error Code: " . $e->getCode() . "<br />";
echo "Exception Msg: " . $e->getMessage();
exit();
}
*/
?>