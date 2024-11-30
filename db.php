<?php
$host = 'localhost';
$dbname = 'jasmin_rice';
$username = 'root';
$password = '';

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8");
} catch (Exception $e) {
    header("Location: ./home.php");
    exit(); 
}
?>
