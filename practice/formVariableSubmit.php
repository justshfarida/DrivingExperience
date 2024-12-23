<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $name = $_GET['name'];
    echo "Hello, " . htmlspecialchars($name);
}
?>