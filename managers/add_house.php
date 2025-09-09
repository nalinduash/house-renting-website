<?php
require_once "../includes/auth_check.php";
requireRole("manager");
require_once "../config/connectdb.php";
require_once "../includes/header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $location = trim($_POST["location"]);
    $bedrooms = (int) $_POST["bedrooms"];
    $price = (float) $_POST["price"];
    $garden = isset($_POST["garden"]) ? 1 : 0;
    $garage = isset($_POST["garage"]) ? 1 : 0;

    $file = $_FILES["photo"];

    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        $message = "Upload failed (error code: " . ($file['error'] ?? 'none') . ").";
    } else {
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $newName = uniqid() . "." . $ext;
        $folder  = __DIR__ . "/../images/";
        $dest = $folder . $newName;
        
        move_uploaded_file($file["tmp_name"], $dest);
                        
        $photoPath = "images/" . $newName;
                        
        $stmt = $conn->prepare("INSERT INTO houses (photo, location, bedrooms, garden, garage, price, discount, owner_id, status) 
                                VALUES (:photo, :location, :bedrooms, :garden, :garage, :price, 0, :owner_id, 'available')");
        $stmt->execute([
                            ":photo" => $photoPath,
                            ":location" => $location,
                            ":bedrooms" => $bedrooms,
                            ":garden" => $garden,
                            ":garage" => $garage,
                            ":price" => $price,
                            ":owner_id" => $_SESSION["user_id"]
                        ]);

        $message = "House added successfully!";
    }
}
?>

<h2>Add New House</h2>

<?php if ($message): ?>
    <p style="color:green;"><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data">
    <label>Picture:</label><br>
    <input type="file" name="photo" accept=".jpg,.jpeg,.png" required><br><br>

    <label>Location:</label><br>
    <input type="text" name="location" required><br><br>

    <label>Bedrooms:</label><br>
    <input type="number" name="bedrooms" required><br><br>

    <label>Price:</label><br>
    <input type="number" step="0.01" name="price" required><br><br>

    <label><input type="checkbox" name="garden"> Has Garden</label><br>
    <label><input type="checkbox" name="garage"> Has Garage</label><br><br>

    <button type="submit">Add House</button>
</form>

<?php require_once "../includes/footer.php"; ?>