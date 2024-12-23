<?php
$host="localhost:3306";//or default 3308 for MyMariaDB, 3306 default value, check PHP MyAdmin
$user="root";
$passwd="";
$db="lc3";//set BD name


//PHP version 8
//try ... catch... localhost
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);//N7
try {
$link = mysqli_connect($host, $user, $passwd, $db);
mysqli_set_charset($link,"utf8");
} catch (Exception $e) { 
echo "Case localhost, database : $db, Error Code: " . $e->getCode() . "<br />";
echo "Exception Msg: " . $e->getMessage();
exit();
}


/*
//PHP version 7
//@ to prevent a warning display
$link=@mysqli_connect($host,$user,$passwd,$db);

//if mysqli_connect_errno
if(mysqli_connect_errno()){
	echo "Error No :".mysqli_connect_errno()." , Msg : ".mysqli_connect_error()."<br>";
	exit();
}

//encoding mysqli_set_charset
mysqli_set_charset($link,'utf8');
*/

?>