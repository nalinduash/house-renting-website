<?php
require_once "../includes/auth_check.php";
requireRole("admin");
require_once "../config/connectdb.php";
require_once "../includes/header.php";

$message = "";

// Delete house
if (isset($_GET["delete"])) {
    $house_id = (int)$_GET["delete"];
    
    // First, check if house exists and get photo path
    $stmt = $conn->prepare("SELECT photo FROM houses WHERE id = :id");
    $stmt->execute([":id" => $house_id]);
    $house = $stmt->fetch();
    
    if ($house) {
        // Delete the house image file
        if (!empty($house['photo']) && file_exists("../" . $house['photo'])) {
            unlink("../" . $house['photo']);
        }
        
        // Delete the house from database
        $stmt = $conn->prepare("DELETE FROM houses WHERE id = :id");
        $stmt->execute([":id" => $house_id]);
        
        $message = "House deleted successfully!";
    } else {
        $message = "House not found!";
    }
}

// Fetch all houses
$stmt = $conn->query("SELECT * FROM houses ORDER BY id DESC");
$houses = $stmt->fetchAll();
?>

<div class="admin-content">
    <h2>Manage Houses</h2>

    <?php if ($message): ?>
        <div class="success"><?php echo $message; ?></div>
    <?php endif; ?>

    <div style="margin-bottom: 20px;">
        <a href="add_house.php" class="home-button">➕ Add New House</a>
        <a href="admin_dashboard.php" class="gray-button">⬅ Back to Dashboard</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Photo</th>
            <th>Location</th>
            <th>Bedrooms</th>
            <th>Price</th>
            <th>Discount</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($houses as $house): ?>
        <tr>
            <td><?php echo $house["id"]; ?></td>
            <td>
                <?php if (!empty($house['photo'])): ?>
                    <img src="../<?php echo htmlspecialchars($house['photo']); ?>" 
                        class="house-image-small"
                        alt="House Image" >
                <?php else: ?>
                    No Image
                <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($house["location"]); ?></td>
            <td><?php echo $house["bedrooms"]; ?></td>
            <td>$<?php echo number_format($house["price"], 2); ?></td>
            <td><?php echo $house["discount"]; ?>%</td>
            <td>
                <span style="color: <?php echo $house['status'] === 'available' ? '#27ae60' : '#e74c3c'; ?>;">
                    <?php echo ucfirst($house['status']); ?>
                </span>
            </td>
            <td>
                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                    <a href="edit_house.php?id=<?php echo $house['id']; ?>" 
                       class="home-button">
                        ✏️ Edit
                    </a>
                    <a href="?delete=<?php echo $house['id']; ?>" 
                       class="gray-button"
                       onclick="return confirm('Are you sure you want to delete this house?')">
                        🗑️ Delete
                    </a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once "../includes/footer.php"; ?>