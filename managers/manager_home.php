<?php
require_once "../includes/auth_check.php";
requireRole("manager");
require_once "../includes/header.php";
?>

<h2>Welcome, <?php echo $_SESSION["username"]; ?> (Manager)</h2>
<p>You can add new houses here.</p>

<ul>
    <li><a href="add_house.php">Add New House</a></li>
</ul>

<?php require_once "../includes/footer.php"; ?>
