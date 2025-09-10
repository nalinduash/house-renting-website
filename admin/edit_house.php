<?php
require_once "../includes/auth_check.php";
requireRole("admin");
require_once "../config/connectdb.php";
require_once "../includes/header.php";

if (!isset($_GET['id'])) {
    header("Location: manage_houses.php");
    exit;
}

$house_id = (int)$_GET['id'];
$message = "";

// Get current house data
$stmt = $conn->prepare("SELECT * FROM houses WHERE id = :id");
$stmt->execute([":id" => $house_id]);
$house = $stmt->fetch();

if (!$house) {
    header("Location: manage_houses.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $location = trim($_POST["location"]);
    $bedrooms = (int) $_POST["bedrooms"];
    $price = (float) $_POST["price"];
    $garden = isset($_POST["garden"]) ? 1 : 0;
    $garage = isset($_POST["garage"]) ? 1 : 0;
    $status = $_POST["status"];
    $discount = (float) $_POST["discount"];

    $photoPath = $house['photo']; // Keep existing photo by default

    // Handle new photo upload
    if (!empty($_FILES["photo"]["name"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {
        $file = $_FILES["photo"];
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $newName = uniqid() . "." . $ext;
        $folder = __DIR__ . "/../images/";
        $dest = $folder . $newName;
            
        if (move_uploaded_file($file["tmp_name"], $dest)) {
            $photoPath = "images/" . $newName;
        }
    }

    $stmt = $conn->prepare("UPDATE houses SET 
                            photo = :photo, 
                            location = :location, 
                            bedrooms = :bedrooms, 
                            garden = :garden, 
                            garage = :garage, 
                            price = :price, 
                            discount = :discount, 
                            status = :status 
                            WHERE id = :id");
    
    $stmt->execute([
        ":photo" => $photoPath,
        ":location" => $location,
        ":bedrooms" => $bedrooms,
        ":garden" => $garden,
        ":garage" => $garage,
        ":price" => $price,
        ":discount" => $discount,
        ":status" => $status,
        ":id" => $house_id
    ]);

    $message = "House updated successfully!";
    header("Location: manage_houses.php?message=" . urlencode($message));
    exit;
}
?>

<div class="admin-content">
    <h2>Edit House</h2>

    <?php if ($message): ?>
        <div class="success"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label>Current Photo:</label>
                <?php if (!empty($house['photo'])): ?>
                    <img src="../<?php echo htmlspecialchars($house['photo']); ?>" 
                         alt="House Image" 
                         class="house-image-medium">
                <?php else: ?>
                    <p>No image</p>
                <?php endif; ?>
                
                <label>New Photo (optional):</label>
                <input type="file" name="photo" accept=".jpg,.jpeg,.png,.gif">
            </div>
            
            <div>
                <label>Location:</label>
                <input type="text" name="location" value="<?php echo htmlspecialchars($house['location']); ?>" required>
                
                <label>Bedrooms:</label>
                <input type="number" name="bedrooms" value="<?php echo $house['bedrooms']; ?>" min="1" required>
                
                <label>Price:</label>
                <input type="number" step="0.01" name="price" value="<?php echo $house['price']; ?>" min="0" required>
                
                <label>Discount (%):</label>
                <input type="number" step="0.01" name="discount" value="<?php echo $house['discount']; ?>" min="0" max="100">
                
                <label>Status:</label>
                <select name="status" required>
                    <option value="available" <?php echo $house['status'] === 'available' ? 'selected' : ''; ?>>Available</option>
                    <option value="sold" <?php echo $house['status'] === 'sold' ? 'selected' : ''; ?>>Sold</option>
                    <option value="maintenance" <?php echo $house['status'] === 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                </select>
                
                <label style="display: block; margin-top: 15px;">
                    <input type="checkbox" name="garden" <?php echo $house['garden'] ? 'checked' : ''; ?>> Has Garden
                </label>
                
                <label style="display: block; margin-top: 10px;">
                    <input type="checkbox" name="garage" <?php echo $house['garage'] ? 'checked' : ''; ?>> Has Garage
                </label>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit">Update House</button>
            <a href="manage_houses.php" class="gray-button">Cancel</a>
        </div>
    </form>
</div>

<?php require_once "../includes/footer.php"; ?>