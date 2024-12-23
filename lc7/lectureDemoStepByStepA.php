<?php

//1) Database connection parameters
//parameters Data Source Name, user and passwd
//MySQL server case

//Data Source Name : host, port, database name
define('PARAM_HOST', '127.0.0.1'); // Use localhost or 127.0.0.1 to connect via XAMPP
const PARAM_PORT=3306;
const PARAM_DB_NAME="lc7";


//dsn value => driver:infos
//driver is either mysql, or pgsql (for postgreSQL)
//mysql:host=localhost;port=3306;dbname=ufaz_php_pw

$dsn="mysql:host=".PARAM_HOST.";param=".PARAM_PORT.";dbname=".PARAM_DB_NAME;


echo "Data Source Name:<br>".$dsn."<br>";

//user for WampServer (Windows)
const PARAM_USER="root";
const PARAM_PASSWD="";

//3) error handling

/*
try {

Error reporting : https://www.php.net/manual/en/pdo.setattribute.php
method to set attribute for error handling
PDO::setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION)
PDO::exec() method to execute a query : SET NAMES 'utf8'
}
catch(PDOException $e){
//https://www.php.net/manual/en/class.pdoexception.php
methods for $e object: getMessage(), getCode	
}
*/

//LECTURE CODE TO USE
try {

    //2) Instance of PDO class
    //variable $pdo, object of class PDO with attributes (properties) and methods
    $pdo = new PDO($dsn, PARAM_USER, PARAM_PASSWD);

    //setting encoding characters using exec() method
    //SET NAMES 'utf8'
    //https://www.php.net/manual/en/pdo.exec.php
    //$pdo->
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'");

}
catch(PDOException $e){
    echo "error MSG: ".$e->getMessage()."<br>";
    echo "error Code: ".$e->getCode()."<br>";
    exit();
}



//4) Basic operations : simple query, method query()
//returned result is a PDOStatement object, no more a PDO object
//queryString property
//using try ... catch, and PDO::errorInfo() method

$query="SELECT * FROM employees";

//Lecture code to use

try {
    $stmt = $pdo->query($query);
    echo $stmt->queryString."<br />";
}
catch(PDOException $e){
    echo "error MSG: ".$e->getMessage()."<br>";
    echo "error Code: ".$e->getCode()."<br>";
    var_dump($pdo->errorInfo());
}




//5) Results processing
//PDOStatement::fecth() PDOStatement method: row by row, to use in a while loop
//PDOStatement::fetchAll() PDOStatement method: all rows returned in an array, tu use with a foreach loop
//cste : FETCH_ASSOC, FETCH_OBJ, FETCH_BOTH
//PDOStatement::setFetchMode() can be used 

/*
while ($var = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $var['lastname']."<br />";
}
*/

/*
while ($var = $stmt->fetch(PDO::FETCH_OBJ)) {
    echo "<pre>";
    var_dump($var);
    echo "</ pre>";
}
*/



//5b) fetchAll() alternative
//https://www.php.net/manual/en/pdostatement.fetchall.php
//https://www.php.net/manual/en/pdostatement.setfetchmode.php


$stmt->setFetchMode(PDO::FETCH_OBJ);
$result = $stmt->fetchAll();

echo "<pre>";
var_dump($result);
echo "</ pre>";





//6) Inserting data using PDO::exec(), the number of affected rows is returned, as an integer
//https://www.php.net/manual/en/pdo.exec.php
//use try... catch...
//data set
$lastname="Christoffel";
$firstname="Eric";
$phoneNumber="01.02.03.04.05";
$email="christof@unistra.fr";
$address="3 rue de l'université";
//$address="3 rue de l\"université";
$zipCode=67000;
$city="Strasbourg";
$query="INSERT INTO `employees`(`idEmployee`, `lastname`, `firstname`, `phoneNumber`, `email`, `address`, `zipCode`, `city`) VALUES ";

//case a) with \"\" for string data
//$query.="(null,\"$lastname\",\"$firstname\",\"$phoneNumber\",\"$email\",\"$address\",\"$zipCode\",\"$city\")";




//7) special characters to escape, use PDO::quote() method, but ... !
//and remove \"\" delimiters!
//https://www.php.net/manual/en/pdo.quote.php

//case b) with \"\" for string data and using queote() to escape special characters 
//$query.="(null,\"$lastname\",\"$firstname\",\"$phoneNumber\",\"$email\",\"".$pdo->quote($address)."\",\"$zipCode\",\"$city\")";


//case b) without \"\" if quote() is used!
//with concatenation
//$query.="(null,\"$lastname\",\"$firstname\",\"$phoneNumber\",\"$email\",".$pdo->quote($address).",\"$zipCode\",\"$city\")";
//substitution to concatenation , literals { }
//$query.="(null,\"$lastname\",\"$firstname\",\"$phoneNumber\",\"$email\",{$pdo->quote($address)},\"$zipCode\",\"$city\")";

echo $query."<br>";

/*
try {

$nbInsertedRows=$pdo->exec($query);
echo "Nb inserted rows: ".$nbInsertedRows."<br>";


}
catch(PDOException $e){
echo "error MSG: ".$e->getMessage()."<br>";
echo "error Code: ".$e->getCode()."<br>";
var_dump($pdo->errorInfo());	
}
*/



?>