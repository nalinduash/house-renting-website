<?php
require_once "../includes/auth_check.php";
requireRole("member");
require_once "../config/connectdb.php";
require_once "../includes/header.php";

$member_id = $_SESSION["user_id"];

// Fetch purchases for this member
$stmt = $conn->prepare("SELECT h.photo, h.location, h.bedrooms, h.price, t.price_paid, t.date 
                        FROM transactions t
                        JOIN houses h ON t.house_id = h.id
                        WHERE t.buyer_id = :member_id
                        ORDER BY t.date DESC");
$stmt->execute([":member_id" => $member_id]);
$purchases = $stmt->fetchAll();
?>

<h2>My Purchases</h2>

<?php if (count($purchases) > 0): ?>
    <div style="display:flex; flex-wrap:wrap; gap:20px;">
        <?php foreach ($purchases as $p): ?>
            <div style="border:1px solid #ccc; padding:10px; width:250px;">
                <img src="../images/houses/<?php echo $p['photo']; ?>" 
                     alt="House Image" 
                     style="width:100%; height:150px; object-fit:cover;">
                <p><b>Location:</b> <?php echo htmlspecialchars($p['location']); ?></p>
                <p><b>Bedrooms:</b> <?php echo $p['bedrooms']; ?></p>
                <p><b>Original Price:</b> $<?php echo number_format($p['price'], 2); ?></p>
                <p><b>Price Paid:</b> $<?php echo number_format($p['price_paid'], 2); ?></p>
                <p><b>Purchased On:</b> <?php echo $p['date']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>You haven’t purchased any houses yet.</p>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>
