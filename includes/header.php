<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>House Renting Website</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
</head>
<body>
    <header>
        <h1>🏠 House Renting Website</h1>
        <nav>
            <ul>
                <?php if (isset($_SESSION["role"])): ?>
                    <?php if ($_SESSION["role"] === "member"): ?>
                        <li><a href="../members/member_home.php">Home</a></li>
                        <li><a href="../members/houses.php">Browse Houses</a></li>
                        <li><a href="../members/my_purchases.php">My Purchases</a></li>
                    <?php elseif ($_SESSION["role"] === "manager"): ?>
                        <li><a href="../managers/manager_home.php">Manager Home</a></li>
                        <li><a href="../managers/add_house.php">Add House</a></li>
                    <?php elseif ($_SESSION["role"] === "admin"): ?>
                        <li><a href="../admin/admin_dashboard.php">Dashboard</a></li>
                        <li><a href="../admin/users.php">Users</a></li>
                        <li><a href="../admin/discounts.php">Discounts</a></li>
                        <li><a href="../admin/reports.php">Reports</a></li>
                    <?php endif; ?>
                    <li><a href="../auth/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="../auth/login.php">Login</a></li>
                    <li><a href="../auth/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <h1 class="nav-expander" hidden>☰</h1>
    </header>
    <main>
