<?php
session_start();
include 'db_conn.php';

// --- 1. Security Check ---
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

if (!isset($_GET['id'])) { 
    header("Location: parkingBooking.php"); 
    exit(); 
}

$booking_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// --- 2. Fetch Booking Details ---
$sql = "SELECT b.*, u.name FROM parkingbooking b 
        JOIN users u ON b.user_id = u.user_id 
        WHERE b.booking_id = '$booking_id' AND b.user_id = '$user_id'";

$result = $conn->query($sql);
$booking = $result->fetch_assoc();

if (!$booking) { 
    echo "<script>alert('Booking not found!'); window.location.href='parkingBooking.php';</script>";
    exit(); 
}

// Normalize status: UPPERCASE to match DB ENUM
$db_status = strtoupper(trim($booking['status']));

// --- 3. Action Logic (Check-in, Check-out, Cancel) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action_type'])) {
    $action = $_POST['action_type'];
    $slot_number = strtoupper($booking['parking_slot']);
    $current_time = date('H:i:s');
    
    if ($action == 'checkin') {
        // Updated to 'IN USE'
        $conn->query("UPDATE parkingbooking SET status = 'IN USE', booking_time_start = '$current_time' WHERE booking_id = '$booking_id'");
        $conn->query("UPDATE parkingspace SET status = 'Occupied' WHERE space_number = '$slot_number'");
        $message = "Check-in successful! Your parking session has started.";
    } 
    else if ($action == 'checkout') {
        $conn->query("UPDATE parkingbooking SET status = 'COMPLETED', booking_time_end = '$current_time' WHERE booking_id = '$booking_id'");
        $conn->query("UPDATE parkingspace SET status = 'Available' WHERE space_number = '$slot_number'");
        $message = "Check-out successful! Thank you.";
    }
    else if ($action == 'cancel') {
        $conn->query("UPDATE parkingbooking SET status = 'CANCELLED' WHERE booking_id = '$booking_id'");
        $conn->query("UPDATE parkingspace SET status = 'Available' WHERE space_number = '$slot_number'");
        $message = "Booking cancelled successfully.";
    }
    
    echo "<script>alert('$message'); window.location.href='parkingBooking.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Details - FKPark</title>
    <link rel="stylesheet" href="0_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="fk-header">
        <h1>FKPark Parking System</h1>
    </header>
    
    <div class="dashboard-container">
        <main class="main-content">
            <div class="card" style="max-width: 500px; margin: 40px auto; text-align: center; padding: 30px; border-top: 5px solid #004a99; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 10px; background: white;">
                
                <h2>Booking Details</h2>
                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
                
                <div class="qr-container" style="margin-bottom: 25px;">
                    <img src="generate_qr.php?id=<?php echo $booking_id; ?>" alt="QR Code" style="width: 180px; border: 1px solid #ddd; padding: 10px; border-radius: 10px;">
                    <p style="color: #666; font-size: 0.85em; margin-top: 10px;">Scan this QR at the entrance</p>
                </div>

                <div class="details-list" style="text-align: left; background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 25px; line-height: 2.2;">
                    <p><strong><i class="fas fa-hashtag" style="color:#004a99; width:25px;"></i> ID:</strong> <?php echo str_pad($booking['booking_id'], 4, '0', STR_PAD_LEFT); ?></p>
                    <p><strong><i class="fas fa-user" style="color:#004a99; width:25px;"></i> Name:</strong> <?php echo htmlspecialchars($booking['name']); ?></p>
                    <p><strong><i class="fas fa-car" style="color:#004a99; width:25px;"></i> Plate No:</strong> <?php echo htmlspecialchars($booking['vehicle_plate']); ?></p>
                    <p><strong><i class="fas fa-th" style="color:#004a99; width:25px;"></i> Slot:</strong> <?php echo htmlspecialchars($booking['parking_slot']); ?></p>
                    <p><strong><i class="fas fa-calendar" style="color:#004a99; width:25px;"></i> Date:</strong> <?php echo $booking['booking_date']; ?></p>
                    <p><strong><i class="fas fa-info-circle" style="color:#004a99; width:25px;"></i> Status:</strong> 
                        <span style="background:#e3f2fd; padding:4px 10px; border-radius:4px; font-weight:bold; color:#004a99;">
                            <?php echo $db_status ? $db_status : 'UNDEFINED'; ?>
                        </span>
                    </p>
                </div>

                <form method="POST">
                    <?php if ($db_status == 'ACTIVE'): ?>
                        <button type="submit" name="action_type" value="checkin" class="btn" style="width:100%; padding: 15px; background-color: #28a745; color: white; border: none; border-radius: 5px; margin-bottom: 15px; font-weight: bold; cursor: pointer; font-size: 16px;">
                            <i class="fas fa-sign-in-alt"></i> START PARKING (CHECK-IN)
                        </button>
                        
                        <div style="margin-bottom: 15px; color: #999; font-size: 0.9em;">— OR —</div>

                        <button type="submit" name="action_type" value="cancel" class="btn" onclick="return confirm('Confirm cancellation?')" style="width:100%; padding: 12px; background-color: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
                            <i class="fas fa-times-circle"></i> CANCEL BOOKING
                        </button>

                    <?php elseif ($db_status == 'IN USE'): ?>
                        <button type="submit" name="action_type" value="checkout" class="btn" style="width:100%; padding: 15px; background-color: #007bff; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 16px;">
                            <i class="fas fa-sign-out-alt"></i> LEAVE PARKING (CHECK-OUT)
                        </button>
                    
                    <?php else: ?>
                        <div style="padding: 15px; background: #fff3cd; border-radius: 5px; color: #856404; font-style: italic;">
                            This booking is already <?php echo strtolower($db_status); ?>.
                        </div>
                    <?php endif; ?>
                </form>
                
                <hr style="margin-top: 25px; border: 0; border-top: 1px solid #eee;">
                <a href="parkingBooking.php" style="display:block; margin-top:15px; color: #004a99; text-decoration:none; font-weight: bold;">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </main>
    </div>
</body>
</html>
<?php
session_start();
include 'db_conn.php';

// --- 1. Security Check ---
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

if (!isset($_GET['id'])) { 
    header("Location: parkingBooking.php"); 
    exit(); 
}

$booking_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// --- 2. Fetch Booking Details ---
$sql = "SELECT b.*, u.name FROM parkingbooking b 
        JOIN users u ON b.user_id = u.user_id 
        WHERE b.booking_id = '$booking_id' AND b.user_id = '$user_id'";

$result = $conn->query($sql);
$booking = $result->fetch_assoc();

if (!$booking) { 
    echo "<script>alert('Booking not found!'); window.location.href='parkingBooking.php';</script>";
    exit(); 
}

// Normalize status: UPPERCASE to match DB ENUM
$db_status = strtoupper(trim($booking['status']));

// --- 3. Action Logic (Check-in, Check-out, Cancel) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action_type'])) {
    $action = $_POST['action_type'];
    $slot_number = strtoupper($booking['parking_slot']);
    $current_time = date('H:i:s');
    
    if ($action == 'checkin') {
        // Updated to 'IN USE'
        $conn->query("UPDATE parkingbooking SET status = 'IN USE', booking_time_start = '$current_time' WHERE booking_id = '$booking_id'");
        $conn->query("UPDATE parkingspace SET status = 'Occupied' WHERE space_number = '$slot_number'");
        $message = "Check-in successful! Your parking session has started.";
    } 
    else if ($action == 'checkout') {
        $conn->query("UPDATE parkingbooking SET status = 'COMPLETED', booking_time_end = '$current_time' WHERE booking_id = '$booking_id'");
        $conn->query("UPDATE parkingspace SET status = 'Available' WHERE space_number = '$slot_number'");
        $message = "Check-out successful! Thank you.";
    }
    else if ($action == 'cancel') {
        $conn->query("UPDATE parkingbooking SET status = 'CANCELLED' WHERE booking_id = '$booking_id'");
        $conn->query("UPDATE parkingspace SET status = 'Available' WHERE space_number = '$slot_number'");
        $message = "Booking cancelled successfully.";
    }
    
    echo "<script>alert('$message'); window.location.href='parkingBooking.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Details - FKPark</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="fk-header">
        <h1>FKPark Parking System</h1>
    </header>
    
    <div class="dashboard-container">
        <main class="main-content">
            <div class="card" style="max-width: 500px; margin: 40px auto; text-align: center; padding: 30px; border-top: 5px solid #004a99; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 10px; background: white;">
                
                <h2>Booking Details</h2>
                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
                
                <div class="qr-container" style="margin-bottom: 25px;">
                    <img src="generate_qr.php?id=<?php echo $booking_id; ?>" alt="QR Code" style="width: 180px; border: 1px solid #ddd; padding: 10px; border-radius: 10px;">
                    <p style="color: #666; font-size: 0.85em; margin-top: 10px;">Scan this QR at the entrance</p>
                </div>

                <div class="details-list" style="text-align: left; background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 25px; line-height: 2.2;">
                    <p><strong><i class="fas fa-hashtag" style="color:#004a99; width:25px;"></i> ID:</strong> <?php echo str_pad($booking['booking_id'], 4, '0', STR_PAD_LEFT); ?></p>
                    <p><strong><i class="fas fa-user" style="color:#004a99; width:25px;"></i> Name:</strong> <?php echo htmlspecialchars($booking['name']); ?></p>
                    <p><strong><i class="fas fa-car" style="color:#004a99; width:25px;"></i> Plate No:</strong> <?php echo htmlspecialchars($booking['vehicle_plate']); ?></p>
                    <p><strong><i class="fas fa-th" style="color:#004a99; width:25px;"></i> Slot:</strong> <?php echo htmlspecialchars($booking['parking_slot']); ?></p>
                    <p><strong><i class="fas fa-calendar" style="color:#004a99; width:25px;"></i> Date:</strong> <?php echo $booking['booking_date']; ?></p>
                    <p><strong><i class="fas fa-info-circle" style="color:#004a99; width:25px;"></i> Status:</strong> 
                        <span style="background:#e3f2fd; padding:4px 10px; border-radius:4px; font-weight:bold; color:#004a99;">
                            <?php echo $db_status ? $db_status : 'UNDEFINED'; ?>
                        </span>
                    </p>
                </div>

                <form method="POST">
                    <?php if ($db_status == 'ACTIVE'): ?>
                        <button type="submit" name="action_type" value="checkin" class="btn" style="width:100%; padding: 15px; background-color: #28a745; color: white; border: none; border-radius: 5px; margin-bottom: 15px; font-weight: bold; cursor: pointer; font-size: 16px;">
                            <i class="fas fa-sign-in-alt"></i> START PARKING (CHECK-IN)
                        </button>
                        
                        <div style="margin-bottom: 15px; color: #999; font-size: 0.9em;">— OR —</div>

                        <button type="submit" name="action_type" value="cancel" class="btn" onclick="return confirm('Confirm cancellation?')" style="width:100%; padding: 12px; background-color: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
                            <i class="fas fa-times-circle"></i> CANCEL BOOKING
                        </button>

                    <?php elseif ($db_status == 'IN USE'): ?>
                        <button type="submit" name="action_type" value="checkout" class="btn" style="width:100%; padding: 15px; background-color: #007bff; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 16px;">
                            <i class="fas fa-sign-out-alt"></i> LEAVE PARKING (CHECK-OUT)
                        </button>
                    
                    <?php else: ?>
                        <div style="padding: 15px; background: #fff3cd; border-radius: 5px; color: #856404; font-style: italic;">
                            This booking is already <?php echo strtolower($db_status); ?>.
                        </div>
                    <?php endif; ?>
                </form>
                
                <hr style="margin-top: 25px; border: 0; border-top: 1px solid #eee;">
                <a href="parkingBooking.php" style="display:block; margin-top:15px; color: #004a99; text-decoration:none; font-weight: bold;">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </main>
    </div>
</body>
</html>
