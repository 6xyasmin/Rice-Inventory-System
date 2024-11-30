<?php
require "db.php";

$rice_id = $_GET['rice_id'];

$sql = "UPDATE rice_inventory SET STATUS='3' WHERE RICE_ID='$rice_id'";
if ($conn->query($sql) === TRUE) {
    header("Location: archived_rice.php");
    exit(); 
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>
