<?php
require "db.php"; 

if (!isset($_POST['name'], $_POST['stocks'], $_POST['kilograms'], $_POST['price'], $_POST['expiration_date'])) {
    echo "Error: Missing required field.";
    exit();
}

$name = htmlspecialchars($_POST['name']);
$stocks = intval($_POST['stocks']);
$kilograms = intval($_POST['kilograms']);
$price = floatval($_POST['price']);
$expiration_date = htmlspecialchars($_POST['expiration_date']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO rice_inventory (NAME, STOCKS, KILOGRAMS, PRICE, EXPIRATION_DATE, STATUS) VALUES (?, ?, ?, ?, ?, '1')";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "Error: Unable to prepare statement.";
    exit();
}

$stmt->bind_param("siiis", $name, $stocks, $kilograms, $price, $expiration_date);
if ($stmt->execute()) {
    header("Location: home.php");
    exit();
} else {
    echo "Error: Unable to execute statement. " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
