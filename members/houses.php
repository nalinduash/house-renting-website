<?php
require_once "../includes/auth_check.php";
requireRole("member");
require_once "../config/connectdb.php";
require_once "../includes/header.php";

$stmt = $conn->query("SELECT * FROM houses WHERE status='available'");
$houses = $stmt->fetchAll();
?>

<h2>Available Houses</h2>

<div style="display:flex; flex-wrap:wrap; gap:20px;">
    <?php foreach ($houses as $house): ?>
        <div class="card">
            <img src="../<?php echo htmlspecialchars($house['photo']); ?>" alt="House Image" style="width:100%; height:150px; object-fit:cover;">
            <p><b>Location:</b> <?php echo htmlspecialchars($house['location']); ?></p>
            <p><b>Bedrooms:</b> <?php echo $house['bedrooms']; ?></p>
            <p><b>Garden:</b> <?php echo $house['garden'] ? 'Yes' : 'No'; ?></p>
            <p><b>Garage:</b> <?php echo $house['garage'] ? 'Yes' : 'No'; ?></p>
            <p><b>Price:</b> $<?php echo number_format($house['price'], 2); ?></p>
            <p><b>Discount:</b> <?php echo $house['discount']; ?>%</p>
            <form method="POST" action="buy.php">
                <input type="hidden" name="house_id" value="<?php echo $house['id']; ?>">
                <button type="submit">Buy Now</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once "../includes/footer.php"; ?>