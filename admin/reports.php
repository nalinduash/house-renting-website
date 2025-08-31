<?php
require_once "../includes/auth_check.php";
requireRole("admin");
require_once "../config/connectdb.php";
require_once "../includes/header.php";

$where = "";
$params = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["from_date"]) && !empty($_POST["to_date"])) {
        $where .= " AND t.date BETWEEN :from_date AND :to_date";
        $params[":from_date"] = $_POST["from_date"];
        $params[":to_date"] = $_POST["to_date"];
    }
    if (!empty($_POST["member_id"])) {
        $where .= " AND u.id = :member_id";
        $params[":member_id"] = $_POST["member_id"];
    }
}

$sql = "SELECT t.id, h.location, h.price, t.price_paid, u.username, t.date
        FROM transactions t
        JOIN houses h ON t.house_id = h.id
        JOIN users u ON t.buyer_id = u.id
        WHERE 1=1 $where
        ORDER BY t.date DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$transactions = $stmt->fetchAll();

// Fetch members for dropdown
$members = $conn->query("SELECT id, username FROM users WHERE role='member'")->fetchAll();
?>

<h2>Sales Reports</h2>

<form method="POST" action="">
    <label>From Date:</label>
    <input type="date" name="from_date">
    <label>To Date:</label>
    <input type="date" name="to_date">
    <label>Filter by Member:</label>
    <select name="member_id">
        <option value="">-- All --</option>
        <?php foreach ($members as $m): ?>
            <option value="<?php echo $m["id"]; ?>"><?php echo $m["username"]; ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filter</button>
</form>

<table border="1" cellpadding="8" cellspacing="0" style="margin-top:20px;">
    <tr>
        <th>ID</th>
        <th>House Location</th>
        <th>Original Price</th>
        <th>Paid Price</th>
        <th>Buyer</th>
        <th>Date</th>
    </tr>
    <?php foreach ($transactions as $t): ?>
    <tr>
        <td><?php echo $t["id"]; ?></td>
        <td><?php echo htmlspecialchars($t["location"]); ?></td>
        <td>$<?php echo number_format($t["price"], 2); ?></td>
        <td>$<?php echo number_format($t["price_paid"], 2); ?></td>
        <td><?php echo $t["username"]; ?></td>
        <td><?php echo $t["date"]; ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php require_once "../includes/footer.php"; ?>
