<?php
require_once "../includes/auth_check.php";
requireRole("manager");
require_once "../config/connectdb.php";
require_once "../includes/header.php";

// Get manager stats
$manager_id = $_SESSION["user_id"];
$my_houses = $conn->query("SELECT COUNT(*) FROM houses WHERE owner_id = $manager_id")->fetchColumn();
$available_houses = $conn->query("SELECT COUNT(*) FROM houses WHERE owner_id = $manager_id AND status='available'")->fetchColumn();
$sold_houses = $conn->query("SELECT COUNT(*) FROM houses WHERE owner_id = $manager_id AND status='sold'")->fetchColumn();
$total_sales = $conn->query("SELECT COALESCE(SUM(t.price_paid), 0) FROM transactions t JOIN houses h ON t.house_id = h.id WHERE h.owner_id = $manager_id")->fetchColumn();
?>

<div class="manager-dashboard">
    <h2>Welcome, <?php echo $_SESSION["username"]; ?> (Manager) 🏢</h2>
    <p class="welcome-message">Manage your property portfolio and add new listings.</p>

    <!-- Manager Stats -->
    <div class="stats-container">
        <div class="stat-card">
            <h1><?php echo $my_houses; ?></h1>
            <p>My Properties</p>
        </div>
        <div class="stat-card">
            <h1><?php echo $available_houses; ?></h1>
            <p>Available</p>
        </div>
        <div class="stat-card">
            <h1><?php echo $sold_houses; ?></h1>
            <p>Sold</p>
        </div>
        <div class="stat-card">
            <h1>$<?php echo number_format($total_sales, 2); ?></h1>
            <p>Total Sales</p>
        </div>
    </div>

    <!-- Manager Actions -->
    <div class="quick-actions">
        <h3>Manager Actions</h3>
        <div class="stats-container">
            <a href="add_house.php" class="stat-card">
                ➕ Add New House
            </a>
            <a href="../auth/logout.php" class="stat-card">
                🚪 Logout
            </a>
        </div>
    </div>

    <!-- Performance Overview -->
    <div class="recent-activity">
        <h3>Performance Overview</h3>
        <div class="recent-activity-card">
            <p>
                You currently manage <strong><?php echo $my_houses; ?> properties</strong>, 
                with <strong><?php echo $available_houses; ?> available</strong> for sale and 
                <strong><?php echo $sold_houses; ?> sold</strong>. 
                Your properties have generated <strong>$<?php echo number_format($total_sales, 2); ?></strong> in total sales.
            </p>
            <?php if ($available_houses > 0): ?>
                <p style="margin-top: 10px;">💡 <strong>Tip:</strong> Consider reviewing your available properties to ensure they're competitively priced.</p>
            <?php else: ?>
                <p style="margin-top: 10px;">🎉 <strong>Great job!</strong> All your properties have been sold. Time to add more listings!</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>