<?php
include("connectDB.inc.php");

/*
echo "<pre>";
var_dump($_POST);
var_dump($_SESSION);
echo "</pre>";
*/

//check if code exists form input type hidden, use $_POST, and $_SESSION exists?
if(array_key_exists('code',$_POST) and array_key_exists($_POST['code'],$_SESSION['code'])){
	$idMovie=intval($_SESSION['code'][$_POST['code']]);	
	} else {
		exit();
	}


if($idMovie==0){//create a new movie INSERT query
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
//mysqli class : https://www.php.net/manual/fr/book.mysqli.php
//https://www.php.net/manual/en/mysqli.prepare.php
//return a mysqli_stmt class => $stmt variable / object	
//prepare() query with question mark ? as parameter marker
$query="INSERT INTO movies VALUES (null,?,?,1)";
$stmt=$mysqliObject->prepare($query);

//mysqli_stmt class https://www.php.net/manual/en/class.mysqli-stmt.php
//https://www.php.net/manual/en/mysqli-stmt.bind-param.php
//bind_param()
$stmt->bind_param('si',$_POST['titleMovie'],$_POST['idCategory']);


//mysqli_stmt class https://www.php.net/manual/en/class.mysqli-stmt.php
//https://www.php.net/manual/en/mysqli-stmt.execute.php
//execute()
$stmt->execute();

} catch (Exception $e) { 
echo "MySQLi Error Code: " . $e->getCode() . "<br />";
echo "Exception Msg: " . $e->getMessage();
exit();
}

} else {//end if
//edit movie UPDATE query
//if using mysqli_ procedural functions, string data must be double quoted!
//parameter marker ?
$query="UPDATE movies SET titleMovie=? , idCategory=? WHERE idMovie=?";
$stmt=$mysqliObject->prepare($query);
$stmt->bind_param('sii',$_POST['titleMovie'],$_POST['idCategory'],$idMovie);
$stmt->execute();

}//end else if




//header redirection
header("Location: movieHomepage.php");

?>