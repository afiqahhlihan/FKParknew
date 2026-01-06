<<<<<<< HEAD
<?php
session_start();
include 'db_conn.php'; 

// --- Security Check ---
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$role = $_SESSION['role']; 

// --- 1. Logic for CANCEL Booking (Status change) ---
if (isset($_GET['action']) && $_GET['action'] == 'cancel' && isset($_GET['id'])) {
    $booking_id = $conn->real_escape_string($_GET['id']);
    $sql = "UPDATE parkingbooking SET status = 'Cancelled' WHERE booking_id = '$booking_id' AND user_id = '$user_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Booking cancelled successfully!'); window.location.href='parkingBooking.php';</script>";
    }
}

// --- 2. Logic for DELETE Booking (Permanent removal) ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $booking_id = $conn->real_escape_string($_GET['id']);
    $sql = "DELETE FROM parkingbooking WHERE booking_id = '$booking_id' AND user_id = '$user_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Booking deleted permanently!'); window.location.href='parkingBooking.php';</script>";
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
    }
}

// Fetch user's bookings
$sql = "SELECT b.*, u.name FROM parkingbooking b JOIN users u ON b.user_id = u.user_id WHERE b.user_id = '$user_id' ORDER BY b.booking_id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Parking Booking Dashboard - FKPark</title>
    <link rel="stylesheet" href="0_style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .status-active { color: #28a745; font-weight: 600; background: #e8f5e9; padding: 4px 8px; border-radius: 4px; }
        .status-cancelled { color: #dc3545; font-weight: 600; background: #ffebee; padding: 4px 8px; border-radius: 4px; }
        
        .action-table-btn { 
            display: inline-block;
            margin: 2px; 
            padding: 8px 12px; 
            font-size: 12px; 
            text-decoration: none; 
            border-radius: 5px;
            color: white;
            transition: 0.3s;
        }
        .btn-update { background-color: #0056b3; }
        .btn-cancel { background-color: #ff9800; }
        .btn-delete { background-color: #f44336; } /* Warna merah untuk Delete */
        .btn-qr { background-color: #ffc107; color: black; }
        
        .action-table-btn:hover { opacity: 0.8; transform: translateY(-1px); }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; color: #333; }
    </style>
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
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="fas fa-home"></i> &nbsp; Dashboard</a></li>
                <li><a href="ProfilePage.php"><i class="fas fa-user"></i> &nbsp; My Profile</a></li>
                <li><a href="vehicleRegistration.php"><i class="fas fa-car"></i> &nbsp; Register Vehicle</a></li>
                <li><a href="parkingareas.php"><i class="fas fa-th"></i> &nbsp; Parking Area</a></li>
                <li><a href="parkingspace.php"><i class="fas fa-th"></i> &nbsp; Parking Space</a></li>
                <li><a href="parkingBooking.php" class="active"><i class="fas fa-id-badge"></i> &nbsp; Parking Booking</a></li>
                <li><a href="MySummon.php"><i class="fas fa-exclamation-triangle"></i> &nbsp; My Summons</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> &nbsp; Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="card" style="max-width: 100%;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h2>Parking Booking Management</h2>
                    <a href="book_update.php" class="btn" style="background-color: #4CAF50;">
                        <i class="fas fa-plus"></i> &nbsp; Make New Booking
                    </a>
                </div>
                <hr style="margin: 20px 0;">
                
                <h3>Your Current Bookings</h3>

                <?php if ($result && $result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Vehicle Plate</th>
                                    <th>Slot</th>
                                    <th>Date</th>
                                    <th>Time </th>
                                    <th>Status</th>
                                    <th style="text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr onclick="window.location='booking_details.php?id=<?php echo $row['booking_id']; ?>';" style="cursor:pointer;" onmouseover="this.style.backgroundColor='#f1f1f1';" onmouseout="this.style.backgroundColor='transparent';">
                                    <td>
                                     <?php 
                                     // This formats the ID into a fixed 4-digit width
                                     echo str_pad($row['booking_id'], 4, '0', STR_PAD_LEFT);?></td>
                                    <td><?php echo htmlspecialchars($row['vehicle_plate']); ?></td>
                                    <td><?php echo htmlspecialchars($row['parking_slot']); ?></td>
                                    <td><?php echo $row['booking_date']; ?></td>
                                    <td><?php echo substr($row['booking_time_start'], 0, 5); ?></td>
                                    <td>
                                        <span class="status-<?php echo strtolower($row['status']); ?>">
                                            <?php echo strtoupper($row['status']); ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center;" onclick="event.stopPropagation();"> 
                                        <a href="generate_qr.php?id=<?php echo $row['booking_id']; ?>" target="_blank" class="action-table-btn btn-qr">
                                            <i class="fas fa-qrcode"></i> View QR
                                        </a>
                                        
                                        <?php if ($row['status'] == 'Active'): ?>
                                            <a href="book_update.php?id=<?php echo $row['booking_id']; ?>" class="action-table-btn btn-update">Update</a>
                                            <a href="parkingBooking.php?action=cancel&id=<?php echo $row['booking_id']; ?>" class="action-table-btn btn-cancel" onclick="return confirm('Cancel this booking?')">Cancel</a>
                                        <?php endif; ?>
										<a href="book_update.php?id=<?php echo $row['booking_id']; ?>" class="action-table-btn btn-update">
                                        <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <a href="parkingBooking.php?action=delete&id=<?php echo $row['booking_id']; ?>" class="action-table-btn btn-delete" onclick="return confirm('Are you sure you want to PERMANENTLY delete this booking?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #777;">
                        <i class="fas fa-calendar-times fa-3x"></i>
                        <p style="margin-top: 10px;">No parking bookings found for your account.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
<?php $conn->close(); ?>
=======
<?php
session_start();
include 'db_conn.php'; 

// --- Security Check ---
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$role = $_SESSION['role']; 

// --- 1. Logic for CANCEL Booking (Status change) ---
if (isset($_GET['action']) && $_GET['action'] == 'cancel' && isset($_GET['id'])) {
    $booking_id = $conn->real_escape_string($_GET['id']);
    $sql = "UPDATE parkingbooking SET status = 'Cancelled' WHERE booking_id = '$booking_id' AND user_id = '$user_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Booking cancelled successfully!'); window.location.href='parkingBooking.php';</script>";
    }
}

// --- 2. Logic for DELETE Booking (Permanent removal) ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $booking_id = $conn->real_escape_string($_GET['id']);
    $sql = "DELETE FROM parkingbooking WHERE booking_id = '$booking_id' AND user_id = '$user_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Booking deleted permanently!'); window.location.href='parkingBooking.php';</script>";
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
    }
}

// Fetch user's bookings
$sql = "SELECT b.*, u.name FROM parkingbooking b JOIN users u ON b.user_id = u.user_id WHERE b.user_id = '$user_id' ORDER BY b.booking_id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Parking Booking Dashboard - FKPark</title>
    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .status-active { color: #28a745; font-weight: 600; background: #e8f5e9; padding: 4px 8px; border-radius: 4px; }
        .status-cancelled { color: #dc3545; font-weight: 600; background: #ffebee; padding: 4px 8px; border-radius: 4px; }
        
        .action-table-btn { 
            display: inline-block;
            margin: 2px; 
            padding: 8px 12px; 
            font-size: 12px; 
            text-decoration: none; 
            border-radius: 5px;
            color: white;
            transition: 0.3s;
        }
        .btn-update { background-color: #0056b3; }
        .btn-cancel { background-color: #ff9800; }
        .btn-delete { background-color: #f44336; } /* Warna merah untuk Delete */
        .btn-qr { background-color: #ffc107; color: black; }
        
        .action-table-btn:hover { opacity: 0.8; transform: translateY(-1px); }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; color: #333; }
    </style>
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
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="fas fa-home"></i> &nbsp; Dashboard</a></li>
                <li><a href="ProfilePage.php"><i class="fas fa-user"></i> &nbsp; My Profile</a></li>
                <li><a href="vehicleRegistration.php"><i class="fas fa-car"></i> &nbsp; Register Vehicle</a></li>
                <li><a href="parkingareas.php"><i class="fas fa-th"></i> &nbsp; Parking Area</a></li>
                <li><a href="parkingspace.php"><i class="fas fa-th"></i> &nbsp; Parking Space</a></li>
                <li><a href="parkingBooking.php" class="active"><i class="fas fa-id-badge"></i> &nbsp; Parking Booking</a></li>
                <li><a href="MySummon.php"><i class="fas fa-exclamation-triangle"></i> &nbsp; My Summons</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> &nbsp; Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="card" style="max-width: 100%;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h2>Parking Booking Management</h2>
                    <a href="book_update.php" class="btn" style="background-color: #4CAF50;">
                        <i class="fas fa-plus"></i> &nbsp; Make New Booking
                    </a>
                </div>
                <hr style="margin: 20px 0;">
                
                <h3>Your Current Bookings</h3>

                <?php if ($result && $result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Vehicle Plate</th>
                                    <th>Slot</th>
                                    <th>Date</th>
                                    <th>Time </th>
                                    <th>Status</th>
                                    <th style="text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr onclick="window.location='booking_details.php?id=<?php echo $row['booking_id']; ?>';" style="cursor:pointer;" onmouseover="this.style.backgroundColor='#f1f1f1';" onmouseout="this.style.backgroundColor='transparent';">
                                    <td>
                                     <?php 
                                     // This formats the ID into a fixed 4-digit width
                                     echo str_pad($row['booking_id'], 4, '0', STR_PAD_LEFT);?></td>
                                    <td><?php echo htmlspecialchars($row['vehicle_plate']); ?></td>
                                    <td><?php echo htmlspecialchars($row['parking_slot']); ?></td>
                                    <td><?php echo $row['booking_date']; ?></td>
                                    <td><?php echo substr($row['booking_time_start'], 0, 5); ?></td>
                                    <td>
                                        <span class="status-<?php echo strtolower($row['status']); ?>">
                                            <?php echo strtoupper($row['status']); ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center;" onclick="event.stopPropagation();"> 
                                        <a href="generate_qr.php?id=<?php echo $row['booking_id']; ?>" target="_blank" class="action-table-btn btn-qr">
                                            <i class="fas fa-qrcode"></i> View QR
                                        </a>
                                        
                                        <?php if ($row['status'] == 'Active'): ?>
                                            <a href="book_update.php?id=<?php echo $row['booking_id']; ?>" class="action-table-btn btn-update">Update</a>
                                            <a href="parkingBooking.php?action=cancel&id=<?php echo $row['booking_id']; ?>" class="action-table-btn btn-cancel" onclick="return confirm('Cancel this booking?')">Cancel</a>
                                        <?php endif; ?>
										<a href="book_update.php?id=<?php echo $row['booking_id']; ?>" class="action-table-btn btn-update">
                                        <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <a href="parkingBooking.php?action=delete&id=<?php echo $row['booking_id']; ?>" class="action-table-btn btn-delete" onclick="return confirm('Are you sure you want to PERMANENTLY delete this booking?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #777;">
                        <i class="fas fa-calendar-times fa-3x"></i>
                        <p style="margin-top: 10px;">No parking bookings found for your account.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
<?php $conn->close(); ?>
>>>>>>> 32b9a13dd3259d11647a66209011d48317fed76a
