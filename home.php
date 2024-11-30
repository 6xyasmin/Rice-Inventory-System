<?php
session_start();
require('db.php');

if (!isset($_SESSION["auth"]) || $_SESSION["auth"] == false || $_SESSION["role"] !== 'admin') {
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
  <link rel="icon" type="image/x-icon" href="img/ricelog.png"> 
  <link rel="stylesheet" href="/rice/css/Style.css">
    <title>Rice Inventory System</title>
</head>
<style>
     .sidebar {
            width: calc(200px + <?php echo strlen($_SESSION['username']) * 8; ?>px);
        }
     
        
</style>
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
     ADMIN: &nbsp; 
    <u><b><?php echo strtoupper(htmlspecialchars($_SESSION['username'])); ?></u></b></h2>
</div>
<?php endif; ?>
    <div class="nav-button" onclick="location.href='/rice/home.php'"><i class="fas fa-home"></i><button>Home</button></div>
        <div class="nav-button" id="addRiceBtn"><i class="fas fa-plus"></i><button class="add">Add Rice</button></div>
        <div class="nav-button" onclick="location.href='/rice/archived_rice.php'"><i class="fas fa-archive"></i><button>Archive</button></div>
        <div class="nav-button" onclick="confirmLogout()"><i class="fa fa-sign-out"></i><button class="logout">Logout</button></div>
    </div>

    <div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h5><img src="\rice\img\add.png" alt="add" class="add-image">ADD RICE</h5>
        <form id="riceForm" action="save_rice.php" method="post">
            <label for="name"><b>Type of Rice:</b></label>
            <input type="text" name="name" required><br>
            <label for="stocks"><b>Stocks:</b></label>
            <input type="number" name="stocks" required><br>
           <label for="kilograms"><b>Kilograms:</b></label>
            <select name="kilograms" required>
                <option value="25">25 kg</option>
                <option value="50">50 kg</option>
            </select><br>
            <label for="price"><b>Price:</b></label>
            <input type="number" name="price" required><br>
            <label for="expiration_date"><b>Expiration Date:</b></label>
            <input type="date" name="expiration_date" required><br>
            <input type="submit" value="Save" id="saveButton" onclick="return confirmSave();">
            <input type="button" value="Cancel" id="cancelButton" onclick="return confirmCancel();">

        </form>
    </div>
    </div>
</div>

<div id="editRiceModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h5><img src="\rice\img\edit.png" alt="edit" class="edit-image">EDIT RICE</h5>
        <form id="editRiceForm" action="update_rice.php" method="post" onsubmit="return confirmUpdate();">
            <input type="hidden" id="editId" name="rice_id">
            <label for="editName"><b>Name:</b></label>
            <input type="text" id="editName" name="name" required><br>
            <label for="editStocks"><b>Stocks:</b></label>
            <input type="number" id="editStocks" name="stocks" required><br>  

            <label for="kilograms"><b>Kilograms:</b></label>
            <select name="kilograms" required>
                <option value="25">25 kg</option>
                <option value="50">50 kg</option>
            </select><br>

            <label for="editPrice"><b>Price:</b></label>
            <input type="number" id="editPrice" name="price" required><br>
            <label for="editExpirationDate"><b>Expiration Date:</b></label>
            <input type="date" id="editExpirationDate" name="expiration_date" required><br>
            <input type="submit" value="Update" id="editSaveButton">
        </form>
    </div>
</div>
<div id="main">
    <div class="h1class">
            
<h1>Rice Product</h1>
    </div>
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
        $sql = "SELECT * FROM rice_inventory WHERE status = '1'";
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
                echo "<td>
                <button class='edit' onclick=\"openEditModal(
                    '" . htmlspecialchars($row["rice_id"]) . "',
                    '" . htmlspecialchars($row["name"]) . "',
                    '" . htmlspecialchars($row["stocks"]) . "',
                    '" . htmlspecialchars($row["kilograms"]) . "',
                    '" . htmlspecialchars($row["price"]) . "',
                    '" . htmlspecialchars($row["expiration_date"]) . "'
                )\">
                    <i class='fas fa-edit'></i>
                </button>

                
                <button class='archive' onclick=\"confirmArchive('archived_rice.php?rice_id=" . urlencode(htmlspecialchars($row['rice_id'])) . "&from=home')\">
                    <i class='fas fa-archive'></i>
                </button>
            </td>";
            

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No records found</td></tr>";
        }
        ?>
    </table>
    
</div>
<script src="/rice/js/home.js"></script>
<script src="/rice/js/archived.js"></script>
<script src="/rice/js/update.js"></script>
<script src="/rice/js/saveCancel.js"></script>
<script src="/rice/js/logout.js"></script>
<script src="/rice/js/editmodal.js"></script>
<script src="/rice/js/Sidebar.js"></script>
</body>
</html>
