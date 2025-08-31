<?php
require_once "../includes/auth_check.php";
requireRole("admin");
require_once "../includes/header.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h2>Welcome, Admin <?php echo $_SESSION["username"]; ?></h2>

    <ul>
        <li><a href="users.php">Manage Users</a></li>
        <li><a href="discounts.php">Manage Discounts</a></li>
        <li><a href="reports.php">View Reports</a></li>
    </ul>

    <br>
    <a href="../auth/logout.php">Logout</a>
</body>
</html>
<?php require_once "../includes/footer.php"; ?>

