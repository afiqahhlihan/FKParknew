<?php
include 'db_conn.php';

// Get the Booking ID from the QR link
if (!isset($_GET['id'])) {
    die("Error: No Booking ID found in QR.");
}

$booking_id = $conn->real_escape_string($_GET['id']);

// Fetch booking details to display
$sql = "SELECT b.*, u.name 
        FROM parkingbooking b 
        JOIN users u ON b.user_id = u.user_id 
        WHERE b.booking_id = '$booking_id'";

$result = $conn->query($sql);
$data = $result->fetch_assoc();

if (!$data) {
    die("Error: Booking not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Parking Check-In</title>
    <link rel="stylesheet" href="0_style.css">
    <style>
        .container-checkin { max-width: 500px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center; }
        .info-box { background: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: left; }
        .btn-confirm { background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-size: 16px; }
    </style>
</head>
<body>
    <div class="container-checkin">
        <h2><i class="fas fa-parking"></i> Parking Check-In</h2>
        <hr><br>
        
        <div class="info-box">
            <p><strong>Booking ID:</strong> #<?php echo $data['booking_id']; ?></p>
            <p><strong>User Name:</strong> <?php echo $data['name']; ?></p>
            <p><strong>Vehicle Plate:</strong> <?php echo $data['vehicle_plate']; ?></p>
            <p><strong>Allocated Slot:</strong> <span style="color:blue; font-weight:bold;"><?php echo $data['parking_slot']; ?></span></p>
        </div>

        <form action="process_checkin.php" method="POST">
            <input type="hidden" name="booking_id" value="<?php echo $data['booking_id']; ?>">
            
            <div style="text-align: left; margin-bottom: 15px;">
                <label><strong>Enter Expected Duration (Hours):</strong></label>
                <input type="number" name="duration" min="1" max="24" required style="width: 100%; padding: 10px; margin-top: 5px;">
            </div>

            <button type="submit" class="btn-confirm">Confirm & Start Parking</button>
        </form>
    </div>
</body>
</html>