<<<<<<< HEAD
=======
<<<<<<<< HEAD:Dashboard.php
========
>>>>>>> 0a350b216d38042aa038f0bf8c3297b22125c457
<!-- test change -->


<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
<<<<<<< HEAD
    header("Location: login.php");
=======
    header("Location: 1-Login.php");
>>>>>>> 0a350b216d38042aa038f0bf8c3297b22125c457
    exit();
}

$name = $_SESSION['name'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard - FKPark</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <!-- HEADER -->
    <header class="fk-header">
        <a href="Dashboard.php" class="header-logo">
            <img src="uploads/umpsa.png" alt="FKPark Logo">
        </a>
        <h1>FKPark Parking System</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>

    <div class="dashboard-container">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <ul class="sidebar-menu">

                <!-- COMMON -->
                <li>
                    <a href="Dashboard.php" class="active">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>

                <!-- ================= STUDENT ================= -->
                <?php if ($role === "Student"): ?>
                    <li>
<<<<<<< HEAD
                        <a href="ProfilePage.php">
=======
                        <a href="1_ProfilePage.php">
>>>>>>> 0a350b216d38042aa038f0bf8c3297b22125c457
                            <i class="fas fa-user"></i> My Profile
                        </a>
                    </li>

                    <li>
<<<<<<< HEAD
                        <a href="vehicleRegistration.php">
=======
                        <a href="1_vehicleRegistration.php">
>>>>>>> 0a350b216d38042aa038f0bf8c3297b22125c457
                            <i class="fas fa-car"></i> Register Vehicle
                        </a>
                    </li>
                    <li>
                        <a href="parkingBooking.php">
                            <i class="fas fa-id-badge"></i> Booking Parking
                        </a>
                    </li>

                    <!-- MODULE 4 (STUDENT) -->
                    <li>
                        <a href="module4/MySummon.php">
                            <i class="fas fa-exclamation-triangle"></i> My Summons
                        </a>
                    </li>
                <?php endif; ?>

                <!-- ================= SMU STAFF ================= -->
                <?php if ($role === "SMU Staff"): ?>
                    <li>
                        <a href="module4/issueSummon.php">
                            <i class="fas fa-file-alt"></i> Issue Summon
                        </a>

                        <a href="module4/summonList.php">
                            <i class="fas fa-list"></i> Summon Records List
                        </a>

                        <a href="module4/summonReport.php">
                            <i class="fas fa-chart-bar"></i> Summon Report
                        </a>
                    </li>
                <?php endif; ?>

                <!-- ================= ADMIN ================= -->
                <?php if ($role === "Administrator"): ?>
                    <li>
                        <a href="ProfilePage.php">
                            <i class="fas fa-user-cog"></i> My Profile
                        </a>
                    </li>
                <?php endif; ?>

                <!-- LOGOUT -->
                <li>
                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>

            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">

            <div class="welcome-banner">
                <h2>Welcome back, <?php echo htmlspecialchars($name); ?>!</h2>
                <p>You are logged in as: <strong><?php echo htmlspecialchars($role); ?></strong></p>
            </div>

            <div class="card" style="max-width:100%; text-align:left;">
                <h3 style="color:var(--primary-color); margin-bottom:15px;">System Announcements</h3>

                <?php if ($role === "Student"): ?>
                    <ul>
                        <li><strong>Profile:</strong> Update your personal details.</li>
                        <li><strong>Vehicle:</strong> Register your car/motorcycle for a parking sticker.</li>
                        <li><strong>Summons:</strong> View traffic summons and demerit points.</li>
                    </ul>

                <?php elseif ($role === "SMU Staff"): ?>
                    <ul>
                        <li><strong>Issue Summon:</strong> Record traffic violations.</li>
                        <li><strong>Summon Records:</strong> View all issued summons.</li>
                        <li><strong>Reports:</strong> Analyze violation statistics.</li>
                    </ul>

                <?php elseif ($role === "Administrator"): ?>
                    <ul>
                        <li><strong>Admin Role:</strong> Manage user registrations and approvals.</li>
                        <li><strong>System Monitoring:</strong> Oversee FKPark operations.</li>
                    </ul>
                <?php endif; ?>
            </div>

        </main>
    </div>

</body>

<<<<<<< HEAD
</html>
=======
</html>
>>>>>>>> 0a350b216d38042aa038f0bf8c3297b22125c457:1_Dashboard.php
>>>>>>> 0a350b216d38042aa038f0bf8c3297b22125c457
