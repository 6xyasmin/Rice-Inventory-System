<?php
require "db.php";

if (isset($_GET['rice_id'])) {
    $rice_id = $_GET['rice_id'];

    $sql = "UPDATE rice_inventory SET status = '1' WHERE rice_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rice_id);

    if ($stmt->execute()) {
        header("Location: archived_rice.php");
    } else {
        echo "Error restoring record: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
