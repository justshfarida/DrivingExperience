<?php
ini_set('display_errors', 1);
session_start();

include("connectDB.inc.php");

$db = Database::getInstance();
$pdo = $db->getConnection();

if (!isset($_GET['code']) || !isset($_SESSION['code'][$_GET['code']])) {
    exit("Invalid or missing code.");
}

$code = $_GET['code'];
$experience_id = $_SESSION['code'][$code]; 

if ($experience_id == 0) {
    exit("Invalid operation: Cannot delete a new entry.");
}

try {
    // Begin a transaction
    $pdo->beginTransaction();

    // Delete maneuvers associated with the experience
    $query_delete_maneuvers = "DELETE FROM Driving_Maneuvers WHERE experience_id = :experience_id";
    $stmt_delete_maneuvers = $pdo->prepare($query_delete_maneuvers);
    $stmt_delete_maneuvers->execute([':experience_id' => $experience_id]);

    // Delete the driving experience
    $query_delete_experience = "DELETE FROM Driving_Experience WHERE experience_id = :experience_id";
    $stmt_delete_experience = $pdo->prepare($query_delete_experience);
    $stmt_delete_experience->execute([':experience_id' => $experience_id]);

    // Commit the transaction
    $pdo->commit();

    // Redirect to the dashboard with success message
    header("Location: dashboard.php?status=deleted");
    exit();
} catch (PDOException $e) {
    // Roll back the transaction in case of error
    $pdo->rollBack();
    error_log("Error deleting data: " . $e->getMessage());
    header("Location: dashboard.php?status=error");
    exit();
}
?>
