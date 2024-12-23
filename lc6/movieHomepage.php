<?php
include("connectDB.inc.php");

//$_SESSION to anonymise data
$_SESSION['code']=array();

//try error with FROM movie instead of FROM movies
$query="SELECT movies.idMovie, movies.titleMovie, categories.category 
FROM `movies`
JOIN categories USING (idCategory)
WHERE movies.validMovie=1;";

//execute the query with query() method
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
	$mysqliResult=$mysqliObject->query($query);
} catch (Exception $e) { 
echo "MySQLi Error Code: " . $e->getCode() . "<br />";
echo "Exception Msg: " . $e->getMessage();
exit();
}	

//create an array movieList, each array element is an instance of a movie class
//while loop  on mysqli result object, with fetch_assoc() method
$movieList=array();
while($var=$mysqliResult->fetch_assoc()){
$movieList[$var['idMovie']]=new movie($var['titleMovie'],$var['category']);
}


/*
echo "<pre>";
print_r($movieList);
echo "</pre>";
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Movie Homepage</title>
<style>
	body {font-family: Verdana;font-size:14pt;}
	table {table-layout: auto;width: 800px;border-collapse: collapse;border:1px black solid;}
	th, td {border:1px black solid;}
</style>
</head>
<body>
<nav>
<?php
//code for a new movie
$code=random_pw(10);
$_SESSION['code'][$code]=0;//idMovie=0 for a new movie

//URL variable
?>
<a href="movieForm.php?code=<?php echo $code; ?>">New movie</a>
</nav>

<h1>Movie List</h1>

<table>
<thead><tr><th>Title</th><th>Category</th><th>Edit</th><th>Del.</th></tr></thead>
<tbody>
<?php
foreach($movieList as $keyIdMovie=>$valueMovie){

	$code=random_pw(10);
	$_SESSION['code'][$code]=$keyIdMovie;

	echo "<tr>";
	echo "<td>{$valueMovie->titleMovie}</td>";
	echo "<td>".$valueMovie->category."</td>";
	echo "<td><a href=\"movieForm.php?code=$code\">[ Edit ]</a></td>";
	echo "<td><a href=\"movieDelete.php?code=$code\">[ Supp. ]</a></td>";
	echo "</tr>";
}

?>
</tbody>
</table>
	
</body>
</html>
<?php
//class
class movie {
	public $titleMovie;
	public $category;

	public function __construct($titleMovie,$category){
		$this->titleMovie=$titleMovie;
		$this->category=$category;
	}
}

?>