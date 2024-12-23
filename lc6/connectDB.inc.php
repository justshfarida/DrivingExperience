<?php
//start session
session_start();

$host="localhost:3306";
$user="root";
$passwd="";
$db="lc6";

//try ... catch ...
//https://www.php.net/manual/fr/class.mysqli.php
//https://www.php.net/manual/fr/mysqli.construct.php
//https://phpdelusions.net/mysqli/error_reporting
//enable error reporting for mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
	$mysqliObject=new mysqli($host,$user,$passwd,$db);
	$mysqliObject->set_charset('utf8');

} catch (Exception $e) { 
echo "MySQLi Error Code: " . $e->getCode() . "<br />";
echo "Exception Msg: " . $e->getMessage();
exit();
}

//****************************************************
function random_pw($pw_length) {
    $pass = NULL;
    $charlist = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz023456789';
    $ps_len = strlen($charlist);
    mt_srand((double)microtime()*1000000);

    for($i = 0; $i < $pw_length; $i++) {
        $pass .= $charlist[mt_rand(0, $ps_len - 1)];
    }
    return ($pass);
}
//******************************************

?>