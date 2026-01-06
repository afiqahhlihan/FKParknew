<<<<<<< HEAD
<?php
session_start();
// 1. Database Connection
include 'db_conn.php'; 

// 2. Include the PHP QR Code Library
// Make sure the 'phpqrcode' folder exists in your project directory
if (file_exists('phpqrcode/qrlib.php')) {
    include 'phpqrcode/qrlib.php';
} else {
    die("Error: 'phpqrcode' folder not found. Please download the library and place it in your project folder.");
}

// 3. Security Check: Ensure booking ID is provided and user is logged in
if (!isset($_GET['id']) || !isset($_SESSION['user_id'])) {
    die("Error: Unauthorized access.");
}

$booking_id = $conn->real_escape_string($_GET['id']);
$user_id = $_SESSION['user_id'];

// 4. Fetch booking details (Verification to ensure the booking belongs to the user)
$sql = "SELECT booking_id FROM parkingbooking 
        WHERE booking_id = '$booking_id' AND user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Error: Booking record not found.");
}

// 5. Construct the URL for the QR Code
// Requirement 3: Scan must redirect to a parking page to enter duration.
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];

// IMPORTANT: Ensure '/FKPark/' matches your actual project folder name in htdocs
$qr_link = $protocol . $host . "/FKPark/parking_page.php?id=" . $booking_id;

// 6. Image Output Preparation
// ob_clean() clears any previous text output so the image data doesn't get corrupted
ob_clean(); 
header('Content-Type: image/png');

// 7. QR Code Parameters
$ecc = 'H';       // Error Correction Level (High - allows code to be read even if slightly damaged)
$pixel_size = 10; // Size of the QR pixels
$frame_size = 4;  // White margin around the QR code

// 8. Generate and Output the QR Image
QRcode::png($qr_link, false, $ecc, $pixel_size, $frame_size);

$conn->close();
=======
<?php
session_start();
// 1. Database Connection
include 'db_conn.php'; 

// 2. Include the PHP QR Code Library
// Make sure the 'phpqrcode' folder exists in your project directory
if (file_exists('phpqrcode/qrlib.php')) {
    include 'phpqrcode/qrlib.php';
} else {
    die("Error: 'phpqrcode' folder not found. Please download the library and place it in your project folder.");
}

// 3. Security Check: Ensure booking ID is provided and user is logged in
if (!isset($_GET['id']) || !isset($_SESSION['user_id'])) {
    die("Error: Unauthorized access.");
}

$booking_id = $conn->real_escape_string($_GET['id']);
$user_id = $_SESSION['user_id'];

// 4. Fetch booking details (Verification to ensure the booking belongs to the user)
$sql = "SELECT booking_id FROM parkingbooking 
        WHERE booking_id = '$booking_id' AND user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Error: Booking record not found.");
}

// 5. Construct the URL for the QR Code
// Requirement 3: Scan must redirect to a parking page to enter duration.
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];

// IMPORTANT: Ensure '/FKPark/' matches your actual project folder name in htdocs
$qr_link = $protocol . $host . "/FKPark/parking_page.php?id=" . $booking_id;

// 6. Image Output Preparation
// ob_clean() clears any previous text output so the image data doesn't get corrupted
ob_clean(); 
header('Content-Type: image/png');

// 7. QR Code Parameters
$ecc = 'H';       // Error Correction Level (High - allows code to be read even if slightly damaged)
$pixel_size = 10; // Size of the QR pixels
$frame_size = 4;  // White margin around the QR code

// 8. Generate and Output the QR Image
QRcode::png($qr_link, false, $ecc, $pixel_size, $frame_size);

$conn->close();
>>>>>>> 32b9a13dd3259d11647a66209011d48317fed76a
?>