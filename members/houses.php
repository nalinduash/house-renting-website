<?php
require_once "../includes/auth_check.php";
requireRole("member");
require_once "../config/connectdb.php";
require_once "../includes/header.php";

// Initialize filter variables
$location_filter = $_GET['location'] ?? '';
$bedrooms_filter = $_GET['bedrooms'] ?? '';
$garden_filter = $_GET['garden'] ?? '';
$garage_filter = $_GET['garage'] ?? '';

// Build the SQL query with filters
$sql = "SELECT * FROM houses WHERE status='available'";
$params = [];

if (!empty($location_filter)) {
    $sql .= " AND location LIKE :location";
    $params[':location'] = '%' . $location_filter . '%';
}

if (!empty($bedrooms_filter) && $bedrooms_filter != 'any') {
    $sql .= " AND bedrooms = :bedrooms";
    $params[':bedrooms'] = $bedrooms_filter;
}

if (!empty($garden_filter) && $garden_filter != 'any') {
    $sql .= " AND garden = :garden";
    $params[':garden'] = ($garden_filter === 'yes') ? 1 : 0;
}

if (!empty($garage_filter) && $garage_filter != 'any') {
    $sql .= " AND garage = :garage";
    $params[':garage'] = ($garage_filter === 'yes') ? 1 : 0;
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$houses = $stmt->fetchAll();

// Get unique locations for the dropdown
$locations = $conn->query("SELECT DISTINCT location FROM houses WHERE status='available' ORDER BY location")->fetchAll();
?>

<h2>Available Houses</h2>

<!-- Filter Form -->
<div class="filter-section">
    <h3>Filter Properties</h3>
    <form method="GET" action="">
        <div class="filter-container">
            <!-- Location Filter -->
            <div>
                <label for="location">Location:</label>
                <select name="location" id="location">
                    <option value="">Any Location</option>
                    <?php foreach ($locations as $loc): ?>
                        <option value="<?php echo htmlspecialchars($loc['location']); ?>" 
                            <?php echo ($location_filter === $loc['location']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($loc['location']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Bedrooms Filter -->
            <div>
                <label for="bedrooms">Bedrooms:</label>
                <select name="bedrooms" id="bedrooms">
                    <option value="any">Any</option>
                    <option value="1" <?php echo ($bedrooms_filter === '1') ? 'selected' : ''; ?>>1 Bedroom</option>
                    <option value="2" <?php echo ($bedrooms_filter === '2') ? 'selected' : ''; ?>>2 Bedrooms</option>
                    <option value="3" <?php echo ($bedrooms_filter === '3') ? 'selected' : ''; ?>>3 Bedrooms</option>
                    <option value="4" <?php echo ($bedrooms_filter === '4') ? 'selected' : ''; ?>>4+ Bedrooms</option>
                </select>
            </div>

            <!-- Garden Filter -->
            <div>
                <label for="garden">Garden:</label>
                <select name="garden" id="garden">
                    <option value="any">Any</option>
                    <option value="yes" <?php echo ($garden_filter === 'yes') ? 'selected' : ''; ?>>With Garden</option>
                    <option value="no" <?php echo ($garden_filter === 'no') ? 'selected' : ''; ?>>Without Garden</option>
                </select>
            </div>

            <!-- Garage Filter -->
            <div>
                <label for="garage">Garage:</label>
                <select name="garage" id="garage">
                    <option value="any">Any</option>
                    <option value="yes" <?php echo ($garage_filter === 'yes') ? 'selected' : ''; ?>>With Garage</option>
                    <option value="no" <?php echo ($garage_filter === 'no') ? 'selected' : ''; ?>>Without Garage</option>
                </select>
            </div>
        </div>

        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <button type="submit">Apply Filters</button>
            <a href="houses.php" class="gray-button">Clear Filters</a>
        </div>
    </form>
</div>

<!-- Results Count -->
<div style="margin-bottom: 20px;">
    <p style="color: #666;">
        Found <?php echo count($houses); ?> propert<?php echo count($houses) === 1 ? 'y' : 'ies'; ?> matching your criteria
        <?php if ($location_filter || $bedrooms_filter !== 'any' || $garden_filter !== 'any' || $garage_filter !== 'any'): ?>
            <a href="houses.php">Show all properties</a>
        <?php endif; ?>
    </p>
</div>

<!-- Houses Grid -->
<?php if (count($houses) > 0): ?>
    <div class="stats-container">
        <?php foreach ($houses as $house): ?>
            <div class="card">
                <img src="../<?php echo htmlspecialchars($house['photo']); ?>" alt="House Image">
                <p><b>Location:</b> <?php echo htmlspecialchars($house['location']); ?></p>
                <p><b>Bedrooms:</b> <?php echo $house['bedrooms']; ?></p>
                <p><b>Garden:</b> <?php echo $house['garden'] ? 'Yes' : 'No'; ?></p>
                <p><b>Garage:</b> <?php echo $house['garage'] ? 'Yes' : 'No'; ?></p>
                <p><b>Price:</b> $<?php echo number_format($house['price'], 2); ?></p>
                <?php if ($house['discount'] > 0): ?>
                    <p style="color: #27ae60;">
                        <b>Discount:</b> <?php echo $house['discount']; ?>% OFF!
                    </p>
                    <p style="color: #e74c3c;">
                        <b>Final Price:</b> $<?php echo number_format($house['price'] - ($house['price'] * $house['discount'] / 100), 2); ?>
                    </p>
                <?php else: ?>
                    <p><b>Discount:</b> None</p>
                <?php endif; ?>
                <form method="POST" action="buy.php">
                    <input type="hidden" name="house_id" value="<?php echo $house['id']; ?>">
                    <button type="submit">Buy Now</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div style="text-align: center; padding: 40px; background: white; border-radius: 8px;">
        <p style="color: #666; font-size: 1.1em;">No properties found matching your criteria.</p>
        <p style="color: #999; margin-top: 10px;">Try adjusting your filters or <a href="houses.php">view all properties</a></p>
    </div>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>