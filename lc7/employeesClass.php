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
ini_set("display_errors",1);
//data set CSV format
$employeesData=array();
$employeesData[0]="Grimard;Catherine;03.21.39.60.34;catherineGrimard@armyspy.com;71, Rue St Ferréol;57070;Metz";
$employeesData[1]="Duperré;Jean;05.34.60.16.90;Jean.Duperre@jourrapide.com;75, rue Jean Vilar;24100;Bergerac";
$employeesData[2]="Givry;Denis;04.98.02.77.68;DenisGivry@teleworm.us;32, Avenue De Marlioz;74100;Annemasse";

echo "<pre>";
print_r($employeesData);
echo "</pre>";


//$employees array

//foreach employee, the employee class is instantiated

//list(), explode(), instance new
//$lastname, $firstname, $phoneNumber, $email, $address, $zipCode, $city   
//https://www.php.net/manual/fr/function.explode.php
//https://www.php.net/manual/en/function.list.php   








//Display of the object
echo "<pre>";
//print_r($employees);
echo "</pre>";



//reading a public attribute if public $lastname => change from private to public
//object->property
echo "<hr>";
echo "Direct reading of a public attribute "."<br>";



//reading a private attribute $firstname
//https://phpdelusions.net/articles/error_reporting#converting_errors_to_exceptions
echo "<hr>";
try {
echo "Direct reading of a private attribute "."<br>";
} catch (Throwable  $e){
echo "Case : Direct reading of a private attribute<br>";
echo "Error Code: ". $e->getCode() . "<br />";
echo "Exception Msg: " . $e->getMessage(). "<br />";
}


//reading a private attribute using a public method
//object->method() , get_employee_name()
echo "<hr>";
echo "Direct reading of a private attribute using a method :"."<br>";

//foreach loop over employees
echo "<hr>";





//setting a private attribute, $employees[0]->email
echo "<hr>";
try {

} catch (Throwable  $e){
echo "Case : Setting a private attribute<br>";
echo "Error Code: ". $e->getCode() . "<br />";
echo "Exception Msg: " . $e->getMessage(). "<br />";
}


//setting a private attribute using a setter function
//with validity checking : set_employee_email()
echo "<hr>";




//Display of the object
echo "<hr>";
echo "<pre>";
//print_r($employees);
echo "</pre>";




//****************************************

//create the class
//Restricting access to attributes using ‘access modifiers’ => public, private, protected
//lastname;firstname;phoneNumber;email;address;zipCode;city;
//all private, except lastname public
//__construct() built-in constructor
//$this points to the current object




//getter function to read a private attribute, get_employee_name()
//read lastname and firstname




//setter function to write a private attribute + checking for validity
/*
public function set_employee_email($email){
	$emailIsValid = false;
if (!empty($email))
        {
        // GET EMAIL PARTS
        $domain = ltrim(stristr($email, '@'), '@') . '.';
        $user   = stristr($email, '@', TRUE);
        // VALIDATE EMAIL ADDRESS
                if
                (
                    !empty($user) &&
                    !empty($domain) &&
                    checkdnsrr($domain)
                )
                {
                	$emailIsValid = true;
                	$this->email=$email;
                }
        }
    // RETURN RESULT
        return $emailIsValid;
}
*/

?>
</body>
</html>