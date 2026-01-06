<?php
include '../db_conn.php';

if (!isset($_GET['summon_id'])) {
    die("Invalid summon.");
}

$summon_id = $_GET['summon_id'];

$sql = "
    SELECT 
        t.summonDate,
        t.summonViolationType,
        t.summonDemeritPoint,
        t.summonStatus,
        v.plate_number
    FROM trafficsummon t
    INNER JOIN vehicles v ON t.vehicle_id = v.vehicle_id
    WHERE t.summon_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $summon_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Summon not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Traffic Summon Details</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
<div class="container">
    <div class="card">
        <h2>Traffic Summon Details</h2>
        <p><strong>Plate Number:</strong> <?php echo $data['plate_number']; ?></p>
        <p><strong>Date:</strong> <?php echo $data['summonDate']; ?></p>
        <p><strong>Violation:</strong> <?php echo $data['summonViolationType']; ?></p>
        <p><strong>Demerit Point:</strong> <?php echo $data['summonDemeritPoint']; ?></p>
        <p><strong>Status:</strong> <?php echo $data['summonStatus']; ?></p>
    </div>
</div>
</body>
</html>
