<?php
session_start();
require 'db_conn.php';

/* =========================
   CHECK LOGIN
   ========================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/* =========================
   ADMIN ONLY ACCESS
   ========================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: dashboard.php");
    exit();
}

/* =========================
   VALIDATE AREA ID
   ========================= */
if (!isset($_GET['area_id']) || !is_numeric($_GET['area_id'])) {
    header("Location: parkingareas.php");
    exit();
}

$area_id = (int) $_GET['area_id'];

/* =========================
   DELETE PARKING AREA
   ========================= */
$stmt = $conn->prepare("DELETE FROM parkingareas WHERE area_id = ?");
$stmt->bind_param("i", $area_id);

if ($stmt->execute()) {
    // Success → redirect back
    header("Location: parkingareas.php?status=deleted");
    exit();
} else {
    echo "Error deleting parking area. Please try again.";
}
?>