<<<<<<< HEAD
<?php
session_start();
require 'db_conn.php';

/* LOGIN + ADMIN CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: dashboard.php");
    exit();
}

/* FETCH AREAS */
$areas = $conn->query("SELECT * FROM parkingareas");
$selectedAreaId = $_GET['area_id'] ?? null;

if ($selectedAreaId) {
    $stmt = $conn->prepare("SELECT * FROM parkingareas WHERE area_id=?");
    $stmt->bind_param("i", $selectedAreaId);
    $stmt->execute();
    $selectedArea = $stmt->get_result()->fetch_assoc();
} else {
    $selectedArea = $areas->fetch_assoc();
}

/* GENERATE SPACES */
if (isset($_POST['generate_spaces'])) {

    // Check if spaces already exist
    $check = $conn->query("SELECT * FROM parkingspace WHERE area_id = {$selectedArea['area_id']}");
    if ($check->num_rows == 0) {

        for ($i = 1; $i <= $selectedArea['capacity']; $i++) {
            $spaceNo = strtoupper(substr($selectedArea['area_name'], 0, 1)) . "-" . str_pad($i, 2, "0", STR_PAD_LEFT);
            $token = bin2hex(random_bytes(8));

            $stmt = $conn->prepare(
                "INSERT INTO parkingspace (area_id, space_number, qr_token)
                 VALUES (?, ?, ?)"
            );
            $stmt->bind_param("iss", $selectedArea['area_id'], $spaceNo, $token);
            $stmt->execute();
        }
    }
}

/* FETCH SPACES */
$spaces = $conn->query(
    "SELECT * FROM parkingspace WHERE area_id = {$selectedArea['area_id']}"
);
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
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="parkingareas.php" class="active">Parking Areas</a></li>
            <li><a href="parkingspace.php">Parking Spaces</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>

    <!-- MAIN -->
    <main class="main-content">

        <h2>Parking Areas Summary</h2>

        <div class="wf-grid-3">

            <!-- AREA SUMMARY -->
            <div class="wf-box">
                <h3>Area Summary</h3>

                <form method="GET">
                    <label>Select Area</label>
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
                <p><strong>Type:</strong> <?= $selectedArea['area_type'] ?></p>
                <p><strong>Capacity:</strong> <?= $selectedArea['capacity'] ?></p>
                <p><strong>Status:</strong> <?= $selectedArea['status'] ?></p>

                <form method="POST">
                    <button class="btn" name="generate_spaces">
                        Generate Parking Spaces
                    </button>
                </form>
            </div>

            <!-- SPACE LIST -->
            <div class="wf-box wf-center">
                <h3>Parking Spaces</h3>

                <?php if ($spaces->num_rows > 0): ?>
                    <?php while ($s = $spaces->fetch_assoc()): ?>
                        <p>
                            <?= $s['space_number'] ?> —
                            <strong><?= $s['status'] ?></strong>
                            <br>
                            <small>QR → spaceview.php?token=<?= $s['qr_token'] ?></small>
                        </p>
                        <hr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No parking spaces generated yet.</p>
                <?php endif; ?>
            </div>

            <!-- ACTIONS -->
            <div class="wf-box">
                <h3>Admin Notes</h3>
                <p>
                    QR codes are generated automatically for each parking space.
                    When scanned, they redirect to the parking space information page.
                </p>
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

/* LOGIN + ADMIN CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: dashboard.php");
    exit();
}

/* FETCH AREAS */
$areas = $conn->query("SELECT * FROM parkingareas");
$selectedAreaId = $_GET['area_id'] ?? null;

if ($selectedAreaId) {
    $stmt = $conn->prepare("SELECT * FROM parkingareas WHERE area_id=?");
    $stmt->bind_param("i", $selectedAreaId);
    $stmt->execute();
    $selectedArea = $stmt->get_result()->fetch_assoc();
} else {
    $selectedArea = $areas->fetch_assoc();
}

/* GENERATE SPACES */
if (isset($_POST['generate_spaces'])) {

    // Check if spaces already exist
    $check = $conn->query("SELECT * FROM parkingspace WHERE area_id = {$selectedArea['area_id']}");
    if ($check->num_rows == 0) {

        for ($i = 1; $i <= $selectedArea['capacity']; $i++) {
            $spaceNo = strtoupper(substr($selectedArea['area_name'], 0, 1)) . "-" . str_pad($i, 2, "0", STR_PAD_LEFT);
            $token = bin2hex(random_bytes(8));

            $stmt = $conn->prepare(
                "INSERT INTO parkingspace (area_id, space_number, qr_token)
                 VALUES (?, ?, ?)"
            );
            $stmt->bind_param("iss", $selectedArea['area_id'], $spaceNo, $token);
            $stmt->execute();
        }
    }
}

/* FETCH SPACES */
$spaces = $conn->query(
    "SELECT * FROM parkingspace WHERE area_id = {$selectedArea['area_id']}"
);
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
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="parkingareas.php" class="active">Parking Areas</a></li>
            <li><a href="parkingspace.php">Parking Spaces</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>

    <!-- MAIN -->
    <main class="main-content">

        <h2>Parking Areas Summary</h2>

        <div class="wf-grid-3">

            <!-- AREA SUMMARY -->
            <div class="wf-box">
                <h3>Area Summary</h3>

                <form method="GET">
                    <label>Select Area</label>
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
                <p><strong>Type:</strong> <?= $selectedArea['area_type'] ?></p>
                <p><strong>Capacity:</strong> <?= $selectedArea['capacity'] ?></p>
                <p><strong>Status:</strong> <?= $selectedArea['status'] ?></p>

                <form method="POST">
                    <button class="btn" name="generate_spaces">
                        Generate Parking Spaces
                    </button>
                </form>
            </div>

            <!-- SPACE LIST -->
            <div class="wf-box wf-center">
                <h3>Parking Spaces</h3>

                <?php if ($spaces->num_rows > 0): ?>
                    <?php while ($s = $spaces->fetch_assoc()): ?>
                        <p>
                            <?= $s['space_number'] ?> —
                            <strong><?= $s['status'] ?></strong>
                            <br>
                            <small>QR → spaceview.php?token=<?= $s['qr_token'] ?></small>
                        </p>
                        <hr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No parking spaces generated yet.</p>
                <?php endif; ?>
            </div>

            <!-- ACTIONS -->
            <div class="wf-box">
                <h3>Admin Notes</h3>
                <p>
                    QR codes are generated automatically for each parking space.
                    When scanned, they redirect to the parking space information page.
                </p>
            </div>

        </div>

    </main>
</div>
</body>
</html>
>>>>>>> 32b9a13dd3259d11647a66209011d48317fed76a
