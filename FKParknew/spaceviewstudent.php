<<<<<<< HEAD
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Space View</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="fk-header">
    <a href="dashboard.php" class="header-logo">
        <img src="uploads/umpsa.png">
    </a>
    <h1>FKPark Parking System</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
</header>

<div class="dashboard-container">

<aside class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="ProfilePage.php">My Profile</a></li>
        <li><a href="vehicleRegistration.php">Register Vehicle</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</aside>

<main class="main-content">
    <h2>Space View</h2>

    <div class="wf-grid-3">

        <div class="wf-box">
            <h3>Student Details</h3>
            <p><strong>Matric ID:</strong> CA23022</p>
            <p><strong>Name:</strong> Student Name</p>
        </div>

        <div class="wf-box wf-center">
            <h3>Parking Grid</h3>
            <img src="uploads/parking_grid.png" width="100%">
            <br><br>
            <img src="uploads/qrcode.png" width="120">
        </div>

        <div class="wf-box">
            <h3>Area Details</h3>
            <p><strong>Area Class:</strong> Student</p>
            <p><strong>Area Type:</strong> Open</p>
            <p><strong>Reason:</strong> Lecture</p>
            <p><strong>Status:</strong> Available</p>

            <div class="wf-actions">
                <a href="dashboard.php" class="btn">Back</a>
                <button class="btn">Apply Booking</button>
            </div>
        </div>

    </div>
</main>
</div>

</body>
</html>
=======
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Must be student
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Student') {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Space View</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="fk-header">
    <a href="dashboard.php" class="header-logo">
        <img src="uploads/umpsa.png">
    </a>
    <h1>FKPark Parking System</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
</header>

<div class="dashboard-container">

<aside class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="ProfilePage.php">My Profile</a></li>
        <li><a href="vehicleRegistration.php">Register Vehicle</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</aside>

<main class="main-content">
    <h2>Space View</h2>

    <div class="wf-grid-3">

        <div class="wf-box">
            <h3>Student Details</h3>
            <p><strong>Matric ID:</strong> CA23022</p>
            <p><strong>Name:</strong> Student Name</p>
        </div>

        <div class="wf-box wf-center">
            <h3>Parking Grid</h3>
            <img src="uploads/parking_grid.png" width="100%">
            <br><br>
            <img src="uploads/qrcode.png" width="120">
        </div>

        <div class="wf-box">
            <h3>Area Details</h3>
            <p><strong>Area Class:</strong> Student</p>
            <p><strong>Area Type:</strong> Open</p>
            <p><strong>Reason:</strong> Lecture</p>
            <p><strong>Status:</strong> Available</p>

            <div class="wf-actions">
                <a href="dashboard.php" class="btn">Back</a>
                <button class="btn">Apply Booking</button>
            </div>
        </div>

    </div>
</main>
</div>

</body>
</html>
>>>>>>> 32b9a13dd3259d11647a66209011d48317fed76a
