<?php
require_once "../includes/auth_check.php";
requireRole("member");
require_once "../config/connectdb.php";
require_once "../includes/header.php";

$house_id = isset($_GET['house_id']) ? (int)$_GET['house_id'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Cancelled</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: center;">
        <h2>❌ Payment Cancelled</h2>
        
        <div class="error" style="padding: 20px; margin: 20px 0;">
            Your payment was cancelled. No charges were made.
        </div>

        <p>You can try again or browse other houses.</p>
        
        <div style="margin-top: 30px;">
            <?php if ($house_id): ?>
                <a href="create-payment.php?house_id=<?php echo $house_id; ?>" class="home-button">Try Again</a>
            <?php endif; ?>
            <a href="houses.php" class="home-button" style="background: #95a5a6;">Browse Houses</a>
        </div>
    </div>
</body>
</html>