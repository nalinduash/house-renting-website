<?php
require_once "../includes/auth_check.php";
requireRole("member");
require_once "../includes/header.php";
?>

<h2>Welcome, <?php echo $_SESSION["username"]; ?> (Member)</h2>
<p>You can browse and buy houses from here.</p>

<ul>
    <li><a href="houses.php">Browse Houses</a></li>
    <li><a href="my_purchases.php">My Purchases</a></li>
</ul>

<?php require_once "../includes/footer.php"; ?>
