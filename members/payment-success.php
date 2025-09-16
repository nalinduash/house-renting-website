<?php
require_once "../includes/auth_check.php";
requireRole("member");
require_once "../config/connectdb.php";
require_once "../config/stripe.php";
require_once "../includes/header.php";

if (!isset($_GET['session_id']) || !isset($_GET['house_id'])) {
    header("Location: houses.php");
    exit;
}

$session_id = $_GET['session_id'];
$house_id = (int)$_GET['house_id'];
$buyer_id = $_SESSION["user_id"];

try {
    // Retrieve the Stripe session
    $session = \Stripe\Checkout\Session::retrieve($session_id);
    
    if ($session->payment_status === 'paid') {
        // Get house details
        $stmt = $conn->prepare("SELECT price, discount, status FROM houses WHERE id = :id");
        $stmt->execute([":id" => $house_id]);
        $house = $stmt->fetch();

        if ($house && $house["status"] === "available") {
            $finalPrice = $house["price"] - ($house["price"] * ($house["discount"] / 100));

            // Insert transaction with Stripe payment ID
            $stmt = $conn->prepare("INSERT INTO transactions (house_id, buyer_id, price_paid, stripe_payment_id) 
                                    VALUES (:house_id, :buyer_id, :price_paid, :stripe_payment_id)");
            $stmt->execute([
                ":house_id" => $house_id,
                ":buyer_id" => $buyer_id,
                ":price_paid" => $finalPrice,
                ":stripe_payment_id" => $session->payment_intent
            ]);

            // Mark house as sold
            $stmt = $conn->prepare("UPDATE houses SET status = 'sold' WHERE id = :id");
            $stmt->execute([":id" => $house_id]);

            $message = "Payment successful! House purchased for $" . number_format($finalPrice, 2);
        } else {
            $message = "House not available anymore.";
        }
    } else {
        $message = "Payment not completed.";
    }
} catch (Exception $e) {
    $message = "Error processing payment: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: center;">
        <h2>✅ Payment Successful!</h2>
        
        <?php if (isset($message)): ?>
            <div class="success" style="padding: 20px; margin: 20px 0;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <p>Thank you for your purchase! The house has been added to your properties.</p>
        
        <div style="margin-top: 30px;">
            <a href="my_purchases.php" class="home-button">View My Purchases</a>
            <a href="houses.php" class="home-button" style="background: #95a5a6;">Browse More Houses</a>
        </div>
    </div>
</body>
</html>