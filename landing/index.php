<?php
session_start();

// If already logged in → send to role-specific home
if (isset($_SESSION["role"])) {
    if ($_SESSION["role"] === "member") {
        header("Location: ../members/member_home.php");
        exit;
    } elseif ($_SESSION["role"] === "manager") {
        header("Location: ../managers/manager_home.php");
        exit;
    } elseif ($_SESSION["role"] === "admin") {
        header("Location: ../admin/admin_dashboard.php");
        exit;
    }
}

require_once "../includes/header.php";
?>

<!-- Hero Section -->
<div style="
    background: url('../images/hero.jpg') no-repeat center center; 
    background-size: cover; 
    height: 400px; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    color: white;
    text-shadow: 1px 1px 4px rgba(0,0,0,0.7);
">
    <div style="text-align:center;">
        <h1>Welcome to House Renting Website</h1>
        <p style="font-size:18px;">Find your dream house with just a few clicks</p>
        <div style="margin-top:20px;">
            <a href="auth/login.php">
                <button style="padding:12px 24px; margin-right:10px; font-size:16px;">Login</button>
            </a>
            <a href="auth/register.php">
                <button style="padding:12px 24px; font-size:16px;">Register</button>
            </a>
        </div>
    </div>
</div>

<!-- About Section -->
<div style="padding:40px; text-align:center;">
    <h2>About Our Service</h2>
    <p>We provide a simple and secure platform for members to browse and purchase houses and 
    managers to add new properties</p>
</div>

<?php require_once "../includes/footer.php"; ?>
