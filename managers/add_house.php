<?php
require_once "../includes/auth_check.php";
requireRole("manager");
require_once "../config/connectdb.php";
require_once "../includes/header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $location = $_POST["location"];
    $bedrooms = (int) $_POST["bedrooms"];
    $price = (float) $_POST["price"];
    $garden = isset($_POST["garden"]) ? 1 : 0;
    $garage = isset($_POST["garage"]) ? 1 : 0;

    // For simplicity, we just store image filename (upload feature can be added later)
    $photo = $_POST["photo"];  

    $stmt = $conn->prepare("INSERT INTO houses (photo, location, bedrooms, garden, garage, price, discount, owner_id, status) 
                            VALUES (:photo, :location, :bedrooms, :garden, :garage, :price, 0, :owner_id, 'available')");
    $stmt->execute([
        ":photo" => $photo,
        ":location" => $location,
        ":bedrooms" => $bedrooms,
        ":garden" => $garden,
        ":garage" => $garage,
        ":price" => $price,
        ":owner_id" => $_SESSION["user_id"]
    ]);

    $message = "House added successfully!";
}
?>

<h2>Add New House</h2>

<?php if ($message): ?>
    <p style="color:green;"><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Photo Filename:</label><br>
    <input type="text" name="photo" required><br><br>

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
