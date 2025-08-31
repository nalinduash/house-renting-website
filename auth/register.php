<?php
require_once "../config/connectdb.php";
require_once "../includes/header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email    = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $role     = "member"; // default role for new users

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, role) 
                                VALUES (:username, :password, :email, :role)");
        $stmt->execute([
            ":username" => $username,
            ":password" => $hashedPassword,
            ":email"    => $email,
            ":role"     => $role
        ]);

        $message = "Registration successful! <a href='login.php'>Login here</a>.";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h2>Register</h2>

    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Register</button>
    </form>

    <br>
    <a href="login.php">Already have an account? Login</a>
</body>
</html>
<?php require_once "../includes/footer.php"; ?>
