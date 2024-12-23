<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<style>
    body {font-family: Verdana;font-size:16pt;}
</style>
</head>
<body>
<?php


//from Procedural to Object Oriented Programming
//set Timezone (see lecture N2)

$Paris='Europe/Paris';
$Baku='Asia/Baku';
date_default_timezone_set($Baku);


//procedural, date('format') fct
$dateProcedural=date('Y-m-d H:i:s');

echo "Today, we are the $dateProcedural <br>";

//DateTime object, new instance, format() method
$dateOOP=new DateTime();();
//$dateOOP="";
echo "Formatting DateTime object ".$dateOOP."<br>";


//From Date functions to DateTime method
//example : timestamp

//procedural strtotime() fct
echo "Timestamp Procedural : ".strtotime("+1 month", %dateProcedural)"<br>";

//OOP getTimestamp() method
echo "Timestamp DateTime method() : ".."<br>";


?>
</body>
</html>