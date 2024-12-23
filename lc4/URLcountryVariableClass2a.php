<?php
include("connectDBclass.inc.php");

if(!array_key_exists('country', $_GET)){
	exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
<h1>CD catalog, country :<?php echo $_GET['country']; ?></h1>
<?php


//alternative
//mysqli class : https://www.php.net/manual/fr/book.mysqli.php
//https://www.php.net/manual/en/mysqli.query.php
//query()
//real_escape_string() https://www.php.net/manual/en/mysqli.real-escape-string.php





//mysqli_result class https://www.php.net/manual/en/class.mysqli-result.php
//procedural : mysqli_fetch_assoc()
//https://www.php.net/manual/en/mysqli-result.fetch-assoc.php
//fetch_assoc()

/*
while(){
	echo $var['artist'].','.$var['title'].','.$var['country']."<br>";
}
	*/
?>	
</body>
</html>