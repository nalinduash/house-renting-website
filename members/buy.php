<?php
require_once "../includes/auth_check.php";
requireRole("member"); // Only members can buy

require_once "../config/connectdb.php";
require_once "../includes/header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["house_id"])) {
    $house_id = (int) $_POST["house_id"];
    $buyer_id = $_SESSION["user_id"];

    // Fetch house details (price & discount)
    $stmt = $conn->prepare("SELECT price, discount, status FROM houses WHERE id = :id LIMIT 1");
    $stmt->execute([":id" => $house_id]);
    $house = $stmt->fetch();

    if ($house) {
        if ($house["status"] === "sold") {
            $message = "Sorry, this house is already sold.";
        } else {
            // Apply discount if available
            $finalPrice = $house["price"] - ($house["price"] * ($house["discount"] / 100));

            // Insert transaction
            $stmt = $conn->prepare("INSERT INTO transactions (house_id, buyer_id, price_paid) 
                                    VALUES (:house_id, :buyer_id, :price_paid)");
            $stmt->execute([
                ":house_id" => $house_id,
                ":buyer_id" => $buyer_id,
                ":price_paid" => $finalPrice
            ]);

            // Mark house as sold
            $stmt = $conn->prepare("UPDATE houses SET status = 'sold' WHERE id = :id");
            $stmt->execute([":id" => $house_id]);

            $message = "Purchase successful! You bought the house for $" . number_format($finalPrice, 2);
        }
    } else {
        $message = "House not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buy House</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h2>Buy a House</h2>

    <?php if ($message): ?>
        <p style="color:green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="./buy.php">
        <label>Enter House ID:</label>
        <input type="number" name="house_id" required>
        <button type="submit">Buy Now</button>
    </form>

    <br>
    <a href="houses.php">⬅ Back to Houses</a>
</body>
</html>
<?php require_once "../includes/footer.php"; ?>
