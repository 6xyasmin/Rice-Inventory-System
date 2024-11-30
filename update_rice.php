<?php
session_start(); 

if (!isset($_COOKIE['session_generation_id'])) {
    $session_generation_id = bin2hex(random_bytes(16));
    setcookie('session_generation_id', $session_generation_id, time() + (30 * 24 * 60 * 60), '/');
}

require "db.php";

$rice_id = $_POST['rice_id'];
$name = $_POST['name'];
$stocks = $_POST['stocks'];
$kilograms = $_POST['kilograms'];
$price = $_POST['price'];
$expiration_date = $_POST['expiration_date'];

if (!isset($_COOKIE['session_generation_id']) || !isset($_SESSION['username'])) {
    
    header("Location: index.php");
    exit(); 
}

$sql = "UPDATE rice_inventory SET NAME='$name', STOCKS='$stocks', KILOGRAMS='$kilograms', PRICE='$price', EXPIRATION_DATE='$expiration_date' WHERE RICE_ID='$rice_id'";

if ($conn->query($sql) === TRUE) {
    header("Location: home.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>
