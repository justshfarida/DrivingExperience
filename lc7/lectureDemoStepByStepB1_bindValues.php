<?php

define('PARAM_HOST','localhost');
const PARAM_PORT=3306;
const PARAM_DB_NAME='lc7';
$dsn="mysql:host=".PARAM_HOST.";port=".PARAM_PORT.";dbname=".PARAM_DB_NAME;
const PARAM_USER='root';
const PARAM_PASSWD='';

try {
$pdo=new PDO($dsn,PARAM_USER,PARAM_PASSWD);
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$pdo->exec("SET NAMES 'utf8'");
}
catch(PDOException $e){
echo "error MSG: ".$e->getMessage()."<br>";
echo "error Code: ".$e->getCode()."<br>";
}

//data set
$employees=array();
$employees[0]="Grimard;Catherine;03.21.39.60.34;catherineGrimard@armyspy.com;71, Rue St Ferréol;57070;Metz";
$employees[1]="Duperré;Jean;05.34.60.16.90;Jean.Duperre@jourrapide.com;75, rue Jean Vilar;24100;Bergerac";
$employees[2]="Givry;Denis;04.98.02.77.68;DenisGivry@teleworm.us;32, Avenue De Marlioz;74100;Annemasse";

echo "<pre>";
print_r($employees);
echo "</pre>";


//*********** Case 1 **********************
//positional or unnamed placeholder AND bindValue
//*****************************************

//8) Method PDO::prepare method with positional or unnamed placeholder
//syntax  ?  question mark
//result is a PDOStatement object
//column idEmployee is auto incremented

$query="INSERT INTO employees (lastname,firstname,phoneNumber,email,address,zipCode,city) VALUES (?,?,?,?,?,?,?)";
$stmt=$pdo->prepare($query);





//9) Class PDOStatement::bindValue method
//variables are passed by value to the corresponding placeholder, the type is String: PARAM::STR 
//example with positional placeholder
//foreach loop on employees elements
//query is execute with PDOStatement::execute() method


foreach($employees as $keyEmployee=>$valueEmployee){

list($lastname, $firstname, $phoneNumber, $email, $address, $zipCode, $city)=explode(';',$valueEmployee);

$stmt->bindValue(1,$lastname,PDO::PARAM_STR);
$stmt->bindValue(2,$firstname,PDO::PARAM_STR);
$stmt->bindValue(3,$phoneNumber,PDO::PARAM_STR);
$stmt->bindValue(4,$email,PDO::PARAM_STR);
$stmt->bindValue(5,$address,PDO::PARAM_STR);
$stmt->bindValue(6,$zipCode,PDO::PARAM_STR);
$stmt->bindValue(7,$city,PDO::PARAM_STR);

$stmt->execute();

}//end foreach



//*********** Case 2 **********************
//named placeholder AND bindParam
//*****************************************

//10) Method PDO::prepare method with a named palceholder
//syntax  :name
//result is a PDOStatement object








//11) Class PDOStatement::bindParam method
//variables are called by reference to the corresponding placeholder, the type is String: PARAM::STR
//==> dynamic change of the variable occurs during execute() 
//example with named placeholder
//bindParam before the loop!!!!!!!!!!!!!!




?>