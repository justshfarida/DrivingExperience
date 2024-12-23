<?php
//sessions
session_start();
session_destroy();
session_start();

//$_SESSION superglobale variable
$_SESSION['country']=array();
var_dump($_SESSION);
include("connectDB.inc.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Use URL variables to pass data</title>
</head>
<body>
<h1>CD list from a given country</h1>
<p>Click for a country below:<br>
<?php
$query="SELECT country FROM catalog GROUP BY country";
$result=mysqli_query($link,$query);

while($var=mysqli_fetch_assoc($result)){
//setting $_SESSION
$_SESSION['country'][$var['country']]=$var['country'];
 echo "<a href=\"URLcountryVariableSession1.php?country=".$var['country']."\">".$var['country']."</a><br>";
}//end while


?>

</body>
</html>