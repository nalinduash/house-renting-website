<?php
session_start();

// If not logged in, go to login
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

// role check
function requireRole($role) {
    if ($_SESSION["role"] !== $role) {
        echo "Access Denied!";
        exit;
    }
}
?>
