<?php
session_start();
require('db.php');

if (!isset($_SESSION["auth"]) || $_SESSION["auth"] == false || $_SESSION["role"] !== 'user') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="img/ricelog.png"> 
    <link rel="stylesheet" href="/rice/css/footer.css">
    <link rel="stylesheet" href="/rice/css/user.css">
    <title>Rice Inventory System</title>
</head>
<style>
     .sidebar {
            width: calc(200px + <?php echo strlen($_SESSION['username']) * 8; ?>px);
        }
</style>
<body>
<header>
    <div class="open">
  <span class="openNav" onclick="openNav()">&#9776;</span>
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
    <h2><img src="/rice/img/ricelogo.jpg" alt="Admin Logo" class="admin-logo">
     USER: &nbsp; 
    <u><b><?php echo strtoupper(htmlspecialchars($_SESSION['username'])); ?></u></b></h2>
</div>
<?php endif; ?>
        <div class="nav-button" onclick="confirmLogout()"><i class="fa fa-sign-out"></i><button class="logout">Logout</button></div>
    </div>

    <div id="main">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
        <input type="search" id="search" name="search" placeholder="Search...">
        <input type="submit" value="Search"><br><br>
    </form>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Stocks</th>
                    <th>Kilograms</th>
                    <th>Price</th>
                    <th>Expiration</th>
                </tr>
            </thead>
            <?php
            require "db.php";
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $sql = "SELECT * FROM rice_inventory WHERE status = '1'";
            if (!empty($search)) {
                $search = mysqli_real_escape_string($conn, $search);
                $sql .= " AND (name LIKE '%$search%' OR stocks LIKE '%$search%' OR kilograms LIKE '%$search%' OR price LIKE '%$search%' OR expiration_date LIKE '%$search%')";
            }

            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["stocks"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["kilograms"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["price"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["expiration_date"]) . "</td>"; 
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No records found</td></tr>";
            }
            ?>
        </table>
        <footer>
        <div class="footer-container">
            <div class="footer-icon1"><i class="fas fa-map-marker-alt"></i><b>San Vicente Gapan, Nueva Ecija</b>
        </div>
            <div class="footer-icon2"><a href="https://www.facebook.com/jasmin126?mibextid=rS40aB7S9Ucbxw6v"><i class="fab fa-facebook"></i> </a></div>
            <div class="footer-icon3"><a href="https://www.threads.net/@6iasemi"><i class="fab fa-instagram"></i></a></div>
            <div class="footer-icon4"><i class="fas fa-envelope"></i></div>
            <div class="footer-icon5"><i class="fas fa-phone"></i> <b>Contact: +123 456 7890</b></div>
        </div>
            
        <div class="reserved">
			<p>© All rights reserved | <b>Jasmin Milla.</b></p>
        </div>
    </footer>
    </div>
    <script src="/rice/js/logout.js"></script>
    <script src="/rice/js/sidebar.js"></script>

</body>
</html>
