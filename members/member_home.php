<?php
require_once "../includes/auth_check.php";
requireRole("member");
require_once "../config/connectdb.php";
require_once "../includes/header.php";

// Get member stats
$member_id = $_SESSION["user_id"];
$purchase_count = $conn->query("SELECT COUNT(*) FROM transactions WHERE buyer_id = $member_id")->fetchColumn();
$total_spent = $conn->query("SELECT COALESCE(SUM(price_paid), 0) FROM transactions WHERE buyer_id = $member_id")->fetchColumn();

// Get recently viewed houses (placeholder - would need tracking implementation)
$recent_houses = $conn->query("SELECT * FROM houses WHERE status='available' ORDER BY RAND() LIMIT 3")->fetchAll();
?>

<div class="member-dashboard">
    <h2>Welcome back, <?php echo $_SESSION["username"]; ?>! 👋</h2>
    <p class="welcome-message">Find your dream home from our exclusive collection of properties.</p>

    <!-- Quick Stats -->
    <div class="stats-container">
        <div class="stat-card">
            <h1><?php echo $purchase_count; ?></h3>
            <p>Properties Owned</p>
        </div>
        <div class="stat-card">
            <h1>$<?php echo number_format($total_spent, 2); ?></h3>
            <p>Total Invested</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3>Quick Actions</h3>
        <div class="stats-container">
            <a href="houses.php" class="stat-card">
                🏠 Browse All Houses
            </a>
            <a href="my_purchases.php" class="stat-card">
                📋 View My Purchases
            </a>
            <a href="../auth/logout.php" class="stat-card">
                🚪 Logout
            </a>
        </div>
    </div>

    <!-- Featured Houses -->
    <?php if (count($recent_houses) > 0): ?>
    <div class="featured-houses">
        <h3>Featured Properties</h3>
        <div class="stats-container">
            <?php foreach ($recent_houses as $house): ?>
            <div class="card">
                <img src="../<?php echo htmlspecialchars($house['photo']); ?>" 
                     alt="House Image">
                <h4><?php echo htmlspecialchars($house['location']); ?></h4>
                <p style="margin-left: 0px; margin-bottom: 10px;">🛏️ <?php echo $house['bedrooms']; ?> Bedrooms<br>
                    💰 $<?php echo number_format($house['price'], 2); ?>
                    <?php if ($house['discount'] > 0): ?>
                        <span style="color: #27ae60;">(<?php echo $house['discount']; ?>% off!)</span>
                    <?php endif; ?>
                </p>
                <a href="houses.php" class="home-button">
                    View Details
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Recent Activity (placeholder) -->
    <div class="recent-activity" style="margin-top: 30px;">
        <h3>Recent Activity</h3>
        <div class="recent-activity-card">
            <p>
                <?php if ($purchase_count > 0): ?>
                    You've been actively exploring properties. Your last purchase was 
                    <?php 
                    $last_purchase = $conn->query("SELECT MAX(date) FROM transactions WHERE buyer_id = $member_id")->fetchColumn();
                    echo $last_purchase ? date('F j, Y', strtotime($last_purchase)) : 'recently';
                    ?>.
                <?php else: ?>
                    Welcome to our platform! Start browsing our available properties to find your dream home.
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>