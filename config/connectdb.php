<?php
$server = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "house_rent_db";

try {
    $conn = new PDO("mysql:host=$server;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
