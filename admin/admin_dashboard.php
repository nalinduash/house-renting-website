<?php
require_once "../includes/auth_check.php";
requireRole("admin");
require_once "../config/connectdb.php";
require_once "../includes/header.php";

// Get admin stats
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_houses = $conn->query("SELECT COUNT(*) FROM houses")->fetchColumn();
$total_sales = $conn->query("SELECT COALESCE(SUM(price_paid), 0) FROM transactions")->fetchColumn();
$available_houses = $conn->query("SELECT COUNT(*) FROM houses WHERE status='available'")->fetchColumn();
?>

<div class="admin-dashboard">
    <h2>Welcome, Admin <?php echo $_SESSION["username"]; ?>! 👑</h2>
    <p class="welcome-message">Manage the entire platform from this dashboard.</p>

    <!-- Admin Stats -->
    <div class="stats-container">
        <div class="stat-card">
            <h1><?php echo $total_users; ?></h1>
            <p>Total Users</p>
        </div>
        <div class="stat-card">
            <h1><?php echo $total_houses; ?></h1>
            <p>Total Properties</p>
        </div>
        <div class="stat-card">
            <h1><?php echo $available_houses; ?></h1>
            <p>Available Properties</p>
        </div>
        <div class="stat-card">
            <h1>$<?php echo number_format($total_sales, 2); ?></h1>
            <p>Total Sales</p>
        </div>
    </div>

    <!-- Admin Actions -->
    <div class="quick-actions">
        <h3>Admin Actions</h3>
        <div class="stats-container">
            <a href="users.php" class="stat-card">
                👥 Manage Users
            </a>
            <a href="discounts.php" class="stat-card">
                💰 Manage Discounts
            </a>
            <a href="manage_houses.php" class="stat-card">
                🏠 Manage Houses
            </a>
            <a href="reports.php" class="stat-card">
                📊 View Reports
            </a>
            <a href="../auth/logout.php" class="stat-card">
                🚪 Logout
            </a>
        </div>
    </div>

    <!-- Quick Overview -->
    <div class="recent-activity">
        <h3>Platform Overview</h3>
        <div class="recent-activity-card">
            <p>
                The platform is running smoothly. You have 
                <strong><?php echo $available_houses; ?> properties available</strong> for sale out of 
                <strong><?php echo $total_houses; ?> total properties</strong>. 
                Total sales amount to <strong>$<?php echo number_format($total_sales, 2); ?></strong> 
                across all transactions.
            </p>
        </div>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>