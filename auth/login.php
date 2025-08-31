<?php
session_start();
require_once "../config/connectdb.php"; // DB connection
require_once "../includes/header.php";

$message = ""; // To store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Prepare query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->execute([":username" => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
    //if ($user && ($password == $user["password"])) {
        // Login success → store session data
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["role"] = $user["role"];
        $_SESSION["username"] = $user["username"];

        // Redirect based on role
        if ($user["role"] === "member") {
            header("Location: ../members/member_home.php");
        } elseif ($user["role"] === "manager") {
            header("Location: ../managers/manager_home.php");
        } elseif ($user["role"] === "admin") {
            header("Location: ../admin/admin_dashboard.php");
        }
        exit;
    } else {
        $message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h2>Login</h2>

    <?php if ($message): ?>
        <p style="color:red;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="./login.php">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
<?php require_once "../includes/footer.php"; ?>
