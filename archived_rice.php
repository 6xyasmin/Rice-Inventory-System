<?php
session_start();
require('db.php');
if (!isset($_SESSION["auth"]) || $_SESSION["auth"] == false || $_SESSION["role"] !== 'admin') {
    header("Location: index.php");
    exit();
}


if (isset($_GET['rice_id'])) {
    $rice_id = $_GET['rice_id'];

    // Update the status of the item to 'archived' to restore it
    $sql = "UPDATE rice_inventory SET status = '2' WHERE rice_id = $rice_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: home.php");
    } else {
        echo "Error restoring record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="img/ricelog.png"> 
    <link rel="stylesheet" href="/rice/css/archived-rice.css">
    <link rel="stylesheet" href="/rice/css/Style.css">
    <title>Archived Items</title>
    <style>
        
     .sidebar {
            width: calc(200px + <?php echo strlen($_SESSION['username']) * 8; ?>px);
        }
    </style>
</head>
<body>
<header>
<div class="open">
  <span onclick="openNav()">&#9776;</span>
</div>
    <h2>
        RICE INVENTORY SYSTEM
        <img src="\rice\img\rice.png" alt="rice" class="rice-image"> 
    </h2>
</header>
<br><br>
<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <?php if(isset($_SESSION['username'])): ?>
        <div class="user-name">
            <h2><img src="/rice/img/ricelogo.jpg" alt="Admin Logo" class="admin-logo">ADMIN: &nbsp;  
                <u><b><?php echo strtoupper(htmlspecialchars($_SESSION['username'])); ?></u></b></h2>
        </div>
    <?php endif; ?>
    <div class="nav-button" onclick="location.href='/rice/home.php'"><i class="fas fa-home"></i><button>Home</button></div>
    <div class="nav-button" onclick="confirmLogout()"><i class="fa fa-sign-out"></i><button class="logout">Logout</button></div>
    </div>
        
    
    <div id="main">
    <h1>Archived Rice</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
        <input type="search" id="search" name="search" placeholder="Search...">
        <input type="submit" value="Search"><br><br>
    </form>
    <div class="table-container">
    
    <table>
        <thead>
            <tr>
                <th>Rice ID</th>
                <th>Name</th>
                <th>Stocks</th>
                <th>Kilograms</th>
                <th>Price</th>
                <th>Expiration</th>
                <th>Action</th>
            </tr>
        </thead>
        
            <?php
            require "db.php";

            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $sql = "SELECT * FROM rice_inventory WHERE status = '2'";
            if (!empty($search)) {
                $sql .= " AND (rice_id LIKE '%$search%' OR name LIKE '%$search%' OR stocks LIKE '%$search%' OR kilograms LIKE '%$search%' OR price LIKE '%$search%' OR expiration_date LIKE '%$search%')";
            }
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["rice_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["stocks"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["kilograms"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["price"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["expiration_date"]) . "</td>";
                    echo "<td>"
                    ."<button class='restore' onclick=\"if (confirm('Are you sure you want to restore the rice?')) { location.href='restore.php?rice_id=" . htmlspecialchars($row['rice_id']) . "'; }\"><i class='fas fa-undo'></i></button>"
                    . "<button class='delete' onclick=\"return confirmDelete('" . htmlspecialchars($row['rice_id']) . "');\"><i class='fas fa-trash'></i></button>"
                    . "</td>";
                
                
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<script>function confirmDelete(rice_id) {
    var userConfirmed = confirm('Are you sure you want to delete the rice?');
    if (userConfirmed) {
        window.location.href = 'delete.php?rice_id=' + rice_id + '&from=archived_rice.php';
    }
    return false;
}
</script>
<script src="/rice/js/logout.js"></script>
<script src="/rice/js/sidebar.js"></script>

</body>
</html>
