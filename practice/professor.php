<?php
ini_set('display_errors', 1);

// Database connection
$host = 'localhost:3306';
$user = 'root';
$passwd = '';
$db = 'DrivingExperience';

$mysqli = new mysqli($host, $user, $passwd, $db);
$mysqli->set_charset('utf8');

// Check connection
if ($mysqli->connect_errno) {
    echo "Error: Code " . $mysqli->connect_errno . ", Msg: " . $mysqli->connect_error;
    exit();
}

// Query execution
$query = "SELECT * FROM Weather where weather_id = ?";
$statement = $mysqli->prepare($query);
$statement->bind_param("i", $weatherId);
$weatherId=1;
$statement->execute();
echo $mysqli->affected_rows;
$statement->bind_result($weather_id, $weather_condition);
while($statement->fetch())
{
     echo "<h2>Weather Conditions:</h2><p>".$weather_id."Weather condition: ". $weather_condition. "</p>";
}// Check if query succeeded
if (!$result) {
    echo "Query failed: " . $mysqli->error;
    exit();
}

// Define the Professor class
class Professor
{
    public $_firstName;
    public $_lastName;
    public $_listOfSubjects;

    public function __construct($firstName, $lastName)
    {
        $this->_firstName = $firstName;
        $this->_lastName = $lastName;
        $this->_listOfSubjects = array();
    }

    public function addSubject($subject)
    {
        $this->_listOfSubjects[] = $subject;
    }

    public function getProfessorData()
    {
        echo "<h2>List of subjects taught by " . $this->_firstName . " " . $this->_lastName . ":</h2>";
        if (!empty($this->_listOfSubjects)) {
            echo "<ul>";
            foreach ($this->_listOfSubjects as $valueSubject) {
                echo "<li>" . $valueSubject . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No subjects assigned yet.</p>";
        }
    }
}

// Create a Professor instance
$professor1 = new Professor("John", "Doe");
echo "<h3>Professor's First Name: " . $professor1->_firstName . "</h3>";

// Add subjects and display data
$professor1->addSubject("Mathematics");
$professor1->addSubject("Physics");
$professor1->addSubject("Computer Science");
$professor1->getProfessorData();
?>
