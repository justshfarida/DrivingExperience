<?php
include("connectDBclass.inc.php");

//https://www.php.net/manual/fr/book.mysqli.php
//mysqli class

$query="SELECT country FROM catalog GROUP BY country ORDER BY country ASC";

//procedural : mysqli_query()
//https://www.php.net/manual/en/mysqli.query.php
//return mysqli_result class ==> $resultObject var

$resultObject=$mysqliObject->query($query);
//procedural : mysqli_num_rows($result)
//mysqli_result class https://www.php.net/manual/en/class.mysqli-result.php
//https://www.php.net/manual/en/mysqli-result.num-rows.php
$nbRows=$resultObject->num_rows;

echo "Your query returned $nbRows rows<br>";

//procedural : mysqli_affected_rows($link)
//mysqli class : https://www.php.net/manual/fr/book.mysqli.php
//https://www.php.net/manual/en/mysqli.affected-rows.php
$nbRows=$mysqliObject->affected_rows;

echo "Your query returned $nbRows rows<br>";

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>

<h1>Select a country</h1>
<?php
//procedural : mysqli_fetch_assoc()
//mysqli_result class https://www.php.net/manual/en/class.mysqli-result.php
//https://www.php.net/manual/en/mysqli-result.fetch-assoc.php
//fetch_assoc() and while loop
while($var=$resultObject->fetch_assoc())
{
	echo "<a href=\"URLcountryVariableClass2.php?country=".$var['country']."\">".$var['country']."</a><br>\n";
}
/*
while(){
echo "<a href=\"URLcountryVariableClass2.php?country=".$var['country']."\">".$var['country']."<a><br>\n";	
}
*/
?>

</body>
</html>