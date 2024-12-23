<?php
include("connectDB.inc.php");

echo "<pre>";
print_r($_SESSION);
echo "</pre>";


//URL variable exists? $_SESSION with the URL variable value exists?
//retrieve idMovie from the code (anonymised URL variable)

if(array_key_exists('code',$_GET) and array_key_exists($_GET['code'],$_SESSION['code'])){
$idMovie=intval($_SESSION['code'][$_GET['code']]);	
} else {
	exit();
}



//reading the categories to write the <select> list
//query and query(), returns a mysqli_result object
//listCategories array
//while + fetch_assoc() or array assigned with fetch_all(MYSQLI_ASSOC), but index !=idCatagory!
$query="SELECT * FROM categories";
$mysqliResult=$mysqliObject->query($query);
$categoryList=array();
while($var=$mysqliResult->fetch_assoc()){
	$categoryList[$var['idCategory']]=$var['category'];
}
/*
echo "<pre>";
print_r($categoryList);
echo "</pre>";
*/

//editing a movie, if $idMovie > 0

if($idMovie!=0){
//query with parameter marker ?
$query="SELECT * FROM movies WHERE idMovie=?";
//prepare() returns mysqli_stmt object
$stmt=$mysqliObject->prepare($query);
//bind_param()
$stmt->bind_param('i',$idMovie);
//execute()
$stmt->execute();
//bind_result()
$stmt->bind_result($idMovie,$titleMovie,$idCategory,$validMovie);
//fetch(), no while why only one result set returned!
$stmt->fetch();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Movie web form</title>
<style>
	body {font-family: Verdana;font-size:14pt;}
</style>
</head>
<body>
<h1>Movie data</h1>
<form action="movieFormUpdate.php" method="post">
<input type="hidden" name="code" value="<?php echo $_GET['code']; ?>">
Movie title <input type="text" name="titleMovie" <?php if($idMovie>0) echo ' value="'.htmlspecialchars($titleMovie).'"';  ?>><br>
Category <select name="idCategory" size="1" required>
<option value="">-- Choose --</option>
<?php
//writing <option> from listCategories array
foreach($categoryList as $keyIdCategory=>$valueCategory){
	echo "<option value=\"$keyIdCategory\"";
	if($idMovie>0 and $idCategory==$keyIdCategory) echo " selected ";
	echo ">$valueCategory</option>";
}
?>
</select><br>
<button>Send</button>

</form>	
</body>
</html>