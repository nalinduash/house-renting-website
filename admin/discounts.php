<?php
require_once "../includes/auth_check.php";
requireRole("admin"); // Only admin can access

require_once "../config/connectdb.php";
require_once "../includes/header.php";

$message = "";

// Update discount if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["house_id"])) {
    $house_id = (int) $_POST["house_id"];
    $discount = (float) $_POST["discount"];

    $stmt = $conn->prepare("UPDATE houses SET discount = :discount WHERE id = :id");
    $stmt->execute([
        ":discount" => $discount,
        ":id" => $house_id
    ]);

    $message = "Discount updated successfully!";
}

// Fetch all houses
$stmt = $conn->query("SELECT id, location, bedrooms, price, discount, status FROM houses");
$houses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Discounts</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h2>Manage Discounts</h2>

    <?php if ($message): ?>
        <p style="color:green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Location</th>
            <th>Bedrooms</th>
            <th>Price</th>
            <th>Current Discount (%)</th>
            <th>Update Discount</th>
        </tr>

        <?php foreach ($houses as $house): ?>
        <tr>
            <td><?php echo $house["id"]; ?></td>
            <td><?php echo htmlspecialchars($house["location"]); ?></td>
            <td><?php echo $house["bedrooms"]; ?></td>
            <td><?php echo number_format($house["price"], 2); ?></td>
            <td><?php echo $house["discount"]; ?>%</td>
            <td>
                <form method="POST" action="./discounts.php">
                    <input type="hidden" name="house_id" value="<?php echo $house["id"]; ?>">
                    <input type="number" name="discount" step="0.01" min="0" max="100"
                           value="<?php echo $house["discount"]; ?>" required>
                    <button type="submit">Update</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="admin_dashboard.php">⬅ Back to Dashboard</a>
</body>
</html>
<?php require_once "../includes/footer.php"; ?>
