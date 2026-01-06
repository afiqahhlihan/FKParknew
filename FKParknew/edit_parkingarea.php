<?php
session_start();

/* ================= AUTH ================= */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: dashboard.php");
    exit();
}

/* ================= DB ================= */
include 'db_conn.php';

if (!isset($_GET['area_id'])) {
    header("Location: parkingareas.php");
    exit();
}

$area_id = (int) $_GET['area_id'];

/* Fetch area */
$stmt = $conn->prepare("SELECT * FROM parkingareas WHERE area_id = ?");
$stmt->bind_param("i", $area_id);
$stmt->execute();
$area = $stmt->get_result()->fetch_assoc();

if (!$area) {
    header("Location: parkingareas.php");
    exit();
}

/* Update */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $area_name = trim($_POST['area_name']);
    $area_type = $_POST['area_type'];
    $capacity  = (int) $_POST['capacity'];
    $status    = $_POST['status'];

    $stmt = $conn->prepare(
        "UPDATE parking_areas
         SET area_name=?, area_type=?, capacity=?, status=?
         WHERE area_id=?"
    );
    $stmt->bind_param("ssisi", $area_name, $area_type, $capacity, $status, $area_id);

    if ($stmt->execute()) {
        header("Location: parkingareas.php?area_id=$area_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Parking Area</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="fk-header">
    <h1>Edit Parking Area</h1>
    <a href="parkingareas.php" class="logout-btn">Back</a>
</header>

<main class="main-content">

<div class="card">
    <h2>Edit Area</h2>

    <form method="POST">
        <div class="form-group">
            <label>Area Name</label>
            <input type="text" name="area_name" value="<?= $area['area_name'] ?>" required>
        </div>

        <div class="form-group">
            <label>Area Type</label>
            <select name="area_type" required>
                <option value="Student" <?= $area['area_type']=='Student'?'selected':'' ?>>Student</option>
                <option value="Staff" <?= $area['area_type']=='Staff'?'selected':'' ?>>Staff</option>
                <option value="Admin" <?= $area['area_type']=='Admin'?'selected':'' ?>>Admin</option>
            </select>
        </div>

        <div class="form-group">
            <label>Capacity</label>
            <input type="number" name="capacity" min="1" value="<?= $area['capacity'] ?>" required>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="Active" <?= $area['status']=='Active'?'selected':'' ?>>Active</option>
                <option value="Inactive" <?= $area['status']=='Inactive'?'selected':'' ?>>Inactive</option>
            </select>
        </div>

        <div class="wf-actions">
            <button type="submit" class="btn">Save Changes</button>
            <a href="parkingareas.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</main>
</body>
</html>
