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
    <div class="stats-container" style="display: flex; gap: 20px; margin-bottom: 30px;">
        <div class="stat-card" style="background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%); color: white; padding: 20px; border-radius: 8px; flex: 1; text-align: center;">
            <h3 style="margin: 0; font-size: 2rem;"><?php echo $purchase_count; ?></h3>
            <p style="margin: 5px 0 0 0;">Properties Owned</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #27ae60 0%, #2c3e50 100%); color: white; padding: 20px; border-radius: 8px; flex: 1; text-align: center;">
            <h3 style="margin: 0; font-size: 2rem;">$<?php echo number_format($total_spent, 2); ?></h3>
            <p style="margin: 5px 0 0 0;">Total Invested</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions" style="margin-bottom: 30px;">
        <h3>Quick Actions</h3>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="houses.php" style="background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%); color: white; padding: 15px 25px; border-radius: 6px; text-decoration: none; display: inline-block;">
                🏠 Browse All Houses
            </a>
            <a href="my_purchases.php" style="background: linear-gradient(135deg, #27ae60 0%, #2c3e50 100%); color: white; padding: 15px 25px; border-radius: 6px; text-decoration: none; display: inline-block;">
                📋 View My Purchases
            </a>
            <a href="../auth/logout.php" style="background: linear-gradient(135deg, #e74c3c 0%, #2c3e50 100%); color: white; padding: 15px 25px; border-radius: 6px; text-decoration: none; display: inline-block;">
                🚪 Logout
            </a>
        </div>
    </div>

    <!-- Featured Houses -->
    <?php if (count($recent_houses) > 0): ?>
    <div class="featured-houses">
        <h3>Featured Properties</h3>
        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            <?php foreach ($recent_houses as $house): ?>
            <div style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; width: 300px; background: white;">
                <img src="../<?php echo htmlspecialchars($house['photo']); ?>" 
                     alt="House Image" 
                     style="width: 100%; height: 180px; object-fit: cover; border-radius: 5px; margin-bottom: 10px;">
                <h4 style="margin: 0 0 10px 0; color: #2c3e50;"><?php echo htmlspecialchars($house['location']); ?></h4>
                <p style="margin: 5px 0; color: #666;">
                    🛏️ <?php echo $house['bedrooms']; ?> Bedrooms<br>
                    💰 $<?php echo number_format($house['price'], 2); ?>
                    <?php if ($house['discount'] > 0): ?>
                        <span style="color: #27ae60;">(<?php echo $house['discount']; ?>% off!)</span>
                    <?php endif; ?>
                </p>
                <a href="houses.php" style="display: inline-block; background: #3498db; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; margin-top: 10px;">
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
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
            <p style="color: #666; margin: 0;">
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