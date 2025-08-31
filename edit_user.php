<?php
    include 'connectDB.php';

    if(!isset($_GET['id'])){
        die("Error: No user ID provided.");
    }
    $id = (int)$_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user){
        die("User not found!");
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = $_POST['email'];
        $role = $_POST['role'];

        $stmt = $pdo->prepare("UPDATE users SET email=?, role=?, WHERE id=?");
        $stmt->execute([$email, $role, $id]);

        header("Location: admin_users.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Users</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Edit User - <?= htmlspecialchars($user['username']) ?></h2>
    <form method="post">
        Email: <input type="email" name="email" value="<?= $user['email'] ?>" required><br><br>
        Role:
        <select name="role">
            <option value="member" <?= $user['role']=='member'?'selected':''?>>Member</option>
            <option value="manager" <?= $user['role']=='manager'?'selected':''?>>Manager</option>
            <option value="admin" <?= $user['role']=='admin'?'selected':''?>>Admin</option>
        </select><br><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
