<?php
session_start();

/* ================= AUTH ================= */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: dashboard.php");
    exit();
}

/* ================= DB ================= */
include 'db_conn.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $area_name = trim($_POST['area_name']);
    $area_type = $_POST['area_type'];
    $capacity  = (int) $_POST['capacity'];
    $status    = $_POST['status'];

    if ($area_name && $area_type && $capacity > 0) {
        $stmt = $conn->prepare(
            "INSERT INTO parking_areas (area_name, area_type, capacity, status)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssis", $area_name, $area_type, $capacity, $status);

        if ($stmt->execute()) {
            header("Location: parkingareas.php");
            exit();
        } else {
            $error = "Failed to create parking area.";
        }
    } else {
        $error = "Please fill in all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Parking Area</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="fk-header">
    <h1>Create Parking Area</h1>
    <a href="parkingareas.php" class="logout-btn">Back</a>
</header>

<main class="main-content">

<div class="card">
    <h2>New Parking Area</h2>

    <?php if ($error): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Area Name</label>
            <input type="text" name="area_name" required>
        </div>

        <div class="form-group">
            <label>Area Type</label>
            <select name="area_type" required>
                <option value="">-- Select --</option>
                <option value="Student">Student</option>
                <option value="Staff">Staff</option>
                <option value="Admin">Admin</option>
            </select>
        </div>

        <div class="form-group">
            <label>Capacity</label>
            <input type="number" name="capacity" min="1" required>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>

        <div class="wf-actions">
            <button type="submit" class="btn">Create</button>
            <a href="parkingareas.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</main>
</body>
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> 32b9a13dd3259d11647a66209011d48317fed76a
