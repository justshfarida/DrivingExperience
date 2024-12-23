<?php
include("connectDB.inc.php");
include("securedData.inc.php");

if(array_key_exists('country', $_GET)) {
//reading $_GET
//solution 1
$country=$_GET['country'];
//solution 2
//using mysqli_real_escape_string($link,)
//$country=mysqli_real_escape_string($link,$_GET['country']);
//solution 3
//using securite_bdd()
$country=securite_bdd($_GET['country']);

} else {
	exit();
}

$query="SELECT * FROM catalog WHERE country=\"$country\"";
echo $query."<br>";
//SQL injection
//URLcountryVariable1.php?country=" or ""="
//URLcountryVariable1.php?country=" or ""="
//SQL injectio, add : " or ""="

$result=mysqli_query($link,$query) or die($query.' '.mysqli_error($link));


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
<?php
echo "Number of rows ".mysqli_num_rows($result)."<br>";

?>	
</body>
</html>