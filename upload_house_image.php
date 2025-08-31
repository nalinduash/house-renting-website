<?php
include 'connectDB.php';

if(!isset($_GET['id'])){
    die("Error: No house ID provided.");
}
$id = (int)$_GET['id'];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $targetDir = "uploads/";
    $fileName = basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . time() . "_" . $fileName;

    if(move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)){
        $stmt = $conn->prepare("UPDATE houses SET image=? WHERE id=?");
        $stmt->execute([$targetFile, $id]);
        header("Location: admin_houses.php");
        exit;
    }else{
        echo "Image upload failed!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload House Image</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Upload New Image</h2>
    <form method="post" enctype="multipart/form-data"">
        <input type="file" name="image" required><br><br>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
