<?php
require_once "../includes/auth_check.php";
requireRole("admin");
require_once "../config/connectdb.php";
require_once "../includes/header.php";

$message = "";

// Delete user
if (isset($_GET["delete"])) {
    $id = (int) $_GET["delete"];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute([":id" => $id]);
    $message = "User deleted successfully!";
}

// Change role
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user_id"], $_POST["role"])) {
    $id = (int) $_POST["user_id"];
    $role = $_POST["role"];

    $stmt = $conn->prepare("UPDATE users SET role = :role WHERE id = :id");
    $stmt->execute([":role" => $role, ":id" => $id]);

    $message = "User role updated successfully!";
}

// Fetch all users
$stmt = $conn->query("SELECT id, username, email, role FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h2>Manage Users</h2>

    <?php if ($message): ?>
        <p style="color:green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Current Role</th>
            <th>Change Role</th>
            <th>Action</th>
        </tr>

        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user["id"]; ?></td>
            <td><?php echo htmlspecialchars($user["username"]); ?></td>
            <td><?php echo htmlspecialchars($user["email"]); ?></td>
            <td><?php echo $user["role"]; ?></td>
            <td>
                <form method="POST" action="">
                    <input type="hidden" name="user_id" value="<?php echo $user["id"]; ?>">
                    <select name="role">
                        <option value="member" <?php if ($user["role"]=="member") echo "selected"; ?>>Member</option>
                        <option value="manager" <?php if ($user["role"]=="manager") echo "selected"; ?>>Manager</option>
                        <option value="admin" <?php if ($user["role"]=="admin") echo "selected"; ?>>Admin</option>
                    </select>
                    <button type="submit">Update</button>
                </form>
            </td>
            <td>
                <a href="?delete=<?php echo $user["id"]; ?>" onclick="return confirm('Delete this user?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="admin_dashboard.php">⬅ Back to Dashboard</a>
</body>
</html>
<?php require_once "../includes/footer.php"; ?>
