<?php
require_once "../includes/auth_check.php";
requireRole("member");
require_once "../config/connectdb.php";
require_once "../includes/header.php";
require_once "../config/stripe.php";

if (!isset($_GET['house_id'])) {
    header("Location: houses.php");
    exit;
}

$house_id = (int)$_GET['house_id'];
$buyer_id = $_SESSION["user_id"];

// Get house details
$stmt = $conn->prepare("SELECT id, photo, location, price, discount FROM houses WHERE id = :id AND status='available'");
$stmt->execute([":id" => $house_id]);
$house = $stmt->fetch();

if (!$house) {
    die("House not found or already sold!");
}

// Calculate final price
$finalPrice = $house["price"] - ($house["price"] * ($house["discount"] / 100));
$amount = round($finalPrice * 100); // Convert to cents

try {
    // Create Stripe Checkout session
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'House in ' . $house['location'],
                    'description' => 'House Purchase',
                ],
                'unit_amount' => $amount,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/house-renting-website/members/payment-success.php?session_id={CHECKOUT_SESSION_ID}&house_id=' . $house_id,
        'cancel_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/house-renting-website/members/payment-cancel.php?house_id=' . $house_id,
        'metadata' => [
            'house_id' => $house_id,
            'buyer_id' => $buyer_id
        ]
    ]);
} catch (Exception $e) {
    die("Error creating payment session: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Checkout</title>
    <script src="https://js.stripe.com/v3/"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2>Complete Your Purchase</h2>
        
        <div class="card" style="margin-bottom: 20px; margin-left: auto; margin-right: auto;">
            <img src="../<?php echo htmlspecialchars($house['photo']); ?>" alt="House Image">
            <h3>House Details</h3>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($house['location']); ?></p>
            <p><strong>Original Price:</strong> $<?php echo number_format($house['price'], 2); ?></p>
            <p><strong>Discount:</strong> <?php echo $house['discount']; ?>%</p>
            <p><strong>Final Price:</strong> <span style="color: #27ae60; font-weight: bold;">
                $<?php echo number_format($finalPrice, 2); ?>
            </span></p>
        </div>

        <button id="checkout-button" style="background: #5469d4; color: white; padding: 15px 30px; 
                border: none; border-radius: 4px; font-size: 16px; cursor: pointer; width: 100%;">
            Pay Now with Stripe
        </button>

        <p style="text-align: center; margin-top: 20px; color: #666;">
            <small>This is a test payment. Use Stripe test card: 4242 4242 4242 4242</small>
        </p>
    </div>

    <script>
        var stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');
        var checkoutButton = document.getElementById('checkout-button');

        checkoutButton.addEventListener('click', function() {
            stripe.redirectToCheckout({
                sessionId: '<?php echo $session->id; ?>'
            }).then(function(result) {
                if (result.error) {
                    alert(result.error.message);
                }
            });
        });
    </script>
</body>
</html>
<?php require_once "../includes/footer.php" ?>