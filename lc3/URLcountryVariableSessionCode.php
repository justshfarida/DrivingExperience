<?php
session_start();
session_destroy();
session_start();

//$_SESSION superglobale variable
$_SESSION['code']=array();

include("connectDB.inc.php");
include("securedData.inc.php");

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
//setting random_pw() code
$theCode=random_pw(8);

//setting $_SESSION with the code
$_SESSION['code'][$theCode]=$var['country'];

echo "<a href=\"URLcountryVariableSessionCode1.php?code=$theCode\">".$var['country']."</a><br>";
}//end while


?>

</body>
</html>