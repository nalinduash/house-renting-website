<?php
include 'connectDB.php';
$id = (int)$_GET['id'];

$stmt = $conn->prepare("DELETE FROM users WHERE id=?");
$stmt->execute([$id]);

header("Location: admin_users.php");
exit;
?>
