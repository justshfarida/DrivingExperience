<?php
session_start();

//var_dump for debug
echo "<pre>";
var_dump($_SESSION); 
echo "</pre>";
include("connectDB.inc.php");
include("securedData.inc.php");

if(array_key_exists('country', $_GET) and array_key_exists($_GET['country'], $_SESSION['country'])) {
//$country=securite_bdd($_GET['country']);
$country=$_SESSION['country'][$_GET['country']];

} else {
	exit();
}

$query="SELECT * FROM catalog WHERE country=\"$country\"";
echo $query."<br>";
//SQL injection
//URLcountryVariable1.php?country=" or ""="
//URLcountryVariable1.php?country=USA" or ""="
//SQL injectio, add : " or ""="

$result=mysqli_query($link,$query) or die($query.' '.mysqli_error($link));

echo "Number of rows ".mysqli_num_rows($result)."<br>";

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	
</body>
</html>