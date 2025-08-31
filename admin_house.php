<?php
include 'connectDB.php';

$stmt = $conn->query("SELECT houses.*, users.username AS owner FROM houses LEFT JOIN users ON houses.owner_id = users.id");
$houses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Houses</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>House Listing</h2>
    <?php foreach ($houses as $house): ?>
        <div class="card">
            <img src="<?= $house['image'] ?>" alt="House Image">
            <h3><?= htmlspecialchars($house['title']) ?></h3>
            <p>Location: <?= htmlspecialchars($house['location']) ?></p>
            <p>Bedrooms: <?= $house['bedrooms'] ?></p>
            <p>Garden: <?= $house['garden'] ? 'Yes' : 'No' ?></p>
            <p>Garage: <?= $house['garage'] ? 'Yes' : 'No' ?></p>
            <p>Price: $<?= $house['price'] ?> (Discount: <?= $house['discount'] ?>%</p>
            <p>Status: <?= $house['status'] ?></p>
            <p>Owner: <?= $house['owner'] ?></p>
            <a href="upload_house_image.php?id=<?= $house['id'] ?>">Update Image</a>
        </div>
        <?php endforeach; ?>
</body>
</html>
