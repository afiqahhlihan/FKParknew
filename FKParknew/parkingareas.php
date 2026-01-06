<<<<<<< HEAD
<?php
session_start();
require 'db_conn.php';

/* =========================
   LOGIN & ROLE CHECK
   ========================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'Administrator') {
    header("Location: dashboard.php");
    exit();
}

/* =========================
   FETCH PARKING AREAS
   ========================= */
$areas = $conn->query("SELECT * FROM parkingareas ORDER BY area_name");

$selectedAreaId = $_GET['area_id'] ?? null;

if ($selectedAreaId) {
    $stmt = $conn->prepare("SELECT * FROM parkingareas WHERE area_id = ?");
    $stmt->bind_param("i", $selectedAreaId);
    $stmt->execute();
    $selectedArea = $stmt->get_result()->fetch_assoc();
} else {
    $selectedArea = $areas->fetch_assoc(); // default first
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FKPark | Parking Areas</title>
    <link rel="stylesheet" href="0_style.css">
</head>
<body>

<header class="fk-header">
    <a href="dashboard.php" class="header-logo">
        <img src="uploads/umpsa.png" alt="FKPark Logo">
    </a>
    <h1>FKPark Parking System</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
</header>

<div class="dashboard-container">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <ul class="sidebar-menu">

            <li>
                <a href="dashboard.php">
                    <i class="fas fa-home"></i> &nbsp; Dashboard
                </a>
            </li>

            <li>
                <a href="parkingareas.php" class="active">
                    <i class="fas fa-warehouse"></i> &nbsp; Parking Areas
                </a>
            </li>

            <li>
                <a href="parkingspace.php">
                    <i class="fas fa-parking"></i> &nbsp; Parking Spaces
                </a>
            </li>

            <li>
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> &nbsp; Logout
                </a>
            </li>

        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">

        <h2>Parking Areas Management</h2>

        <div class="wf-grid-3">

            <!-- AREA SUMMARY -->
            <div class="wf-box">
                <h3>Area Summary</h3>

                <form method="GET">
                    <label><strong>Select Area</strong></label>
                    <select name="area_id" onchange="this.form.submit()">
                        <?php
                        $areas->data_seek(0);
                        while ($row = $areas->fetch_assoc()):
                        ?>
                            <option value="<?= $row['area_id'] ?>"
                                <?= ($row['area_id'] == $selectedArea['area_id']) ? 'selected' : '' ?>>
                                <?= $row['area_name'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </form>

                <br>

                <p><strong>Area Name:</strong> <?= $selectedArea['area_name'] ?></p>
                <p><strong>Area Type:</strong> <?= $selectedArea['area_type'] ?></p>
                <p><strong>Capacity:</strong> <?= $selectedArea['capacity'] ?></p>

                <p><strong>Usage:</strong>
                    <?= $selectedArea['requires_booking'] ? 'Advance Booking Required' : 'Immediate Parking Allowed' ?>
                </p>

                <p><strong>Status:</strong> <?= $selectedArea['status'] ?></p>

                <?php if ($selectedArea['status'] !== 'Active'): ?>
                    <p><strong>Closure Reason:</strong> <?= $selectedArea['closure_reason'] ?></p>
                <?php endif; ?>
            </div>

            <!-- PARKING GRID (PLACEHOLDER) -->
            <div class="wf-box wf-center">
                <h3>Parking Area Layout</h3>
                <img src="uploads/parking_grid.png" alt="Parking Grid" width="100%">
                <p style="margin-top:15px;color:#777;">
                    *Layout preview only
                </p>
            </div>

            <!-- ADMIN ACTIONS -->
            <div class="wf-box">
                <h3>Admin Actions</h3>

                <a href="create_parkingarea.php" class="btn">Create New Area</a>
                <br><br>

                <a href="edit_parkingarea.php?area_id=<?= $selectedArea['area_id'] ?>" class="btn">
                    Edit Area
                </a>
                <br><br>

                <a href="delete_parkingarea.php?area_id=<?= $selectedArea['area_id'] ?>"
                   class="btn"
                   onclick="return confirm('Are you sure you want to delete this parking area?');">
                    Delete Area
                </a>
            </div>

        </div>

    </main>
</div>

</body>
</html>
=======
<?php
session_start();
require 'db_conn.php';

/* =========================
   LOGIN & ROLE CHECK
   ========================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'Administrator') {
    header("Location: dashboard.php");
    exit();
}

/* =========================
   FETCH PARKING AREAS
   ========================= */
$areas = $conn->query("SELECT * FROM parkingareas ORDER BY area_name");

$selectedAreaId = $_GET['area_id'] ?? null;

if ($selectedAreaId) {
    $stmt = $conn->prepare("SELECT * FROM parkingareas WHERE area_id = ?");
    $stmt->bind_param("i", $selectedAreaId);
    $stmt->execute();
    $selectedArea = $stmt->get_result()->fetch_assoc();
} else {
    $selectedArea = $areas->fetch_assoc(); // default first
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FKPark | Parking Areas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="fk-header">
    <a href="dashboard.php" class="header-logo">
        <img src="uploads/umpsa.png" alt="FKPark Logo">
    </a>
    <h1>FKPark Parking System</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
</header>

<div class="dashboard-container">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <ul class="sidebar-menu">

            <li>
                <a href="dashboard.php">
                    <i class="fas fa-home"></i> &nbsp; Dashboard
                </a>
            </li>

            <li>
                <a href="parkingareas.php" class="active">
                    <i class="fas fa-warehouse"></i> &nbsp; Parking Areas
                </a>
            </li>

            <li>
                <a href="parkingspace.php">
                    <i class="fas fa-parking"></i> &nbsp; Parking Spaces
                </a>
            </li>

            <li>
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> &nbsp; Logout
                </a>
            </li>

        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">

        <h2>Parking Areas Management</h2>

        <div class="wf-grid-3">

            <!-- AREA SUMMARY -->
            <div class="wf-box">
                <h3>Area Summary</h3>

                <form method="GET">
                    <label><strong>Select Area</strong></label>
                    <select name="area_id" onchange="this.form.submit()">
                        <?php
                        $areas->data_seek(0);
                        while ($row = $areas->fetch_assoc()):
                        ?>
                            <option value="<?= $row['area_id'] ?>"
                                <?= ($row['area_id'] == $selectedArea['area_id']) ? 'selected' : '' ?>>
                                <?= $row['area_name'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </form>

                <br>

                <p><strong>Area Name:</strong> <?= $selectedArea['area_name'] ?></p>
                <p><strong>Area Type:</strong> <?= $selectedArea['area_type'] ?></p>
                <p><strong>Capacity:</strong> <?= $selectedArea['capacity'] ?></p>

                <p><strong>Usage:</strong>
                    <?= $selectedArea['requires_booking'] ? 'Advance Booking Required' : 'Immediate Parking Allowed' ?>
                </p>

                <p><strong>Status:</strong> <?= $selectedArea['status'] ?></p>

                <?php if ($selectedArea['status'] !== 'Active'): ?>
                    <p><strong>Closure Reason:</strong> <?= $selectedArea['closure_reason'] ?></p>
                <?php endif; ?>
            </div>

            <!-- PARKING GRID (PLACEHOLDER) -->
            <div class="wf-box wf-center">
                <h3>Parking Area Layout</h3>
                <img src="uploads/parking_grid.png" alt="Parking Grid" width="100%">
                <p style="margin-top:15px;color:#777;">
                    *Layout preview only
                </p>
            </div>

            <!-- ADMIN ACTIONS -->
            <div class="wf-box">
                <h3>Admin Actions</h3>

                <a href="create_parkingarea.php" class="btn">Create New Area</a>
                <br><br>

                <a href="edit_parkingarea.php?area_id=<?= $selectedArea['area_id'] ?>" class="btn">
                    Edit Area
                </a>
                <br><br>

                <a href="delete_parkingarea.php?area_id=<?= $selectedArea['area_id'] ?>"
                   class="btn"
                   onclick="return confirm('Are you sure you want to delete this parking area?');">
                    Delete Area
                </a>
            </div>

        </div>

    </main>
</div>

</body>
</html>
>>>>>>> 32b9a13dd3259d11647a66209011d48317fed76a
