<?php
session_start();
include '../db_conn.php';

// --- SECURITY ---
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'SMU Staff') {
    header("Location: Dashboard.php");
    exit();
}

// Total summons
$total = $conn->query("SELECT COUNT(*) AS total FROM trafficsummon")->fetch_assoc()['total'];

// Total demerit points
$demerit = $conn->query("SELECT SUM(summonDemeritPoint) AS total FROM trafficsummon")->fetch_assoc()['total'];

// Violation breakdown
$violations = $conn->query("
    SELECT summonViolationType, COUNT(*) AS total
    FROM trafficsummon
    GROUP BY summonViolationType
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Summon Report - FKPark</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

<header class="fk-header">
    <a href="Dashboard.php" class="header-logo">
        <img src="../uploads/umpsa.png" alt="FKPark Logo">
    </a>
    <h1>FKPark Parking System</h1>
    <a href="../logout.php" class="logout-btn">Logout</a>
</header>

<div class="dashboard-container">
    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="Dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="summonReport.php" class="active"><i class="fas fa-chart-bar"></i> Summon Report</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="card">
            <h2><i class="fas fa-chart-line"></i> Traffic Summon Statistics</h2>

            <p><strong>Total Summons Issued:</strong> <?php echo $total; ?></p>
            <p><strong>Total Demerit Points:</strong> <?php echo $demerit; ?></p>

            <h3 style="margin-top:25px;">Violation Breakdown</h3>

            <table style="width:100%; border-collapse:collapse;">
                <tr style="background:#f2f2f2;">
                    <th>Violation Type</th>
                    <th>Total Cases</th>
                </tr>
                <?php while ($v = $violations->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($v['summonViolationType']); ?></td>
                    <td><?php echo $v['total']; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </main>
</div>

</body>
</html>
