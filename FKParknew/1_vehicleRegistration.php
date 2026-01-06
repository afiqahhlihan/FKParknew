<?php
/**
 * SEGMENT 1: SESSION & SECURITY INITIALIZATION
 * ---------------------------------------------------------
 * We ensure the user is logged in and generate a CSRF token.
 * This token prevents "Cross-Site Request Forgery" attacks.
 */
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: 1_Login.php");
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$msg = "";
$msg_type = "";

/**
 * SEGMENT 2: DATA PROCESSING & SECURITY CHECKS
 * ---------------------------------------------------------
 * This block runs when the form is submitted. 
 * We verify the CSRF token and skip redundant sanitization because 
 * we use Prepared Statements later.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // CSRF Check: Verify the form was actually sent from your site
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Security violation: CSRF token mismatch.");
    }

    $type = $_POST['vehicle_type'];
    $brand = $_POST['vehicle_brand'];
    $plate = strtoupper(trim($_POST['plate_number']));
    $class = $_POST['license_class'];
    $due_date = $_POST['license_due_date'];

    /**
     * SEGMENT 3: SECURE FILE UPLOAD LOGIC
     * ---------------------------------------------------------
     * 1. Check for errors.
     * 2. Validate file extension (Prevents .php or .exe hacks).
     * 3. Set safe folder permissions (0755).
     */
    $grant_path = "";
    if (isset($_FILES['vehicle_grant']) && $_FILES['vehicle_grant']['error'] === 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true); // 0755 is more secure than 0777
        }

        $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];
        $file_ext = strtolower(pathinfo($_FILES['vehicle_grant']['name'], PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_ext)) {
            $filename = time() . "_" . preg_replace("/[^a-zA-Z0-0.]/", "_", basename($_FILES["vehicle_grant"]["name"]));
            $target_file = $target_dir . $filename;

            if (move_uploaded_file($_FILES["vehicle_grant"]["tmp_name"], $target_file)) {
                $grant_path = $target_file;
            } else {
                $msg = "Error moving the uploaded file.";
                $msg_type = "error";
            }
        } else {
            $msg = "Invalid file type. Only JPG, PNG, and PDF are allowed.";
            $msg_type = "error";
        }
    } else {
        $msg = "Please upload a valid vehicle grant file.";
        $msg_type = "error";
    }

    /**
     * SEGMENT 4: DATABASE INTEGRITY & INSERTION
     * ---------------------------------------------------------
     * We check if the plate exists before inserting.
     * We use Prepared Statements (? placeholders) to stop SQL Injection.
     */
    if ($msg == "") {
        // Check for duplicate plate number
        $check_stmt = $conn->prepare("SELECT plate_number FROM vehicles WHERE plate_number = ?");
        $check_stmt->bind_param("s", $plate);
        $check_stmt->execute();
        $check_res = $check_stmt->get_result();

        if ($check_res->num_rows > 0) {
            $msg = "Error: Plate number already registered.";
            $msg_type = "error";
        } else {
            // Securely insert the data
            $stmt = $conn->prepare("INSERT INTO vehicles (user_id, vehicle_type, vehicle_brand, plate_number, license_class, license_due_date, vehicle_grant) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $user_id, $type, $brand, $plate, $class, $due_date, $grant_path);

            if ($stmt->execute()) {
                echo "<script>alert('Vehicle Registered Successfully!'); window.location='1_ProfilePage.php';</script>";
                exit();
            } else {
                $msg = "Database Error: " . $stmt->error;
                $msg_type = "error";
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register Vehicle - FKPark</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <header class="fk-header">
        <a href="1_Dashboard.php" class="header-logo"><img src="uploads/umpsa.png" alt="FKPark Logo"></a>
        <h1>FKPark Parking System</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="1_Dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>

                <?php if ($role === "Student"): ?>
                    <li><a href="1_ProfilePage.php"><i class="fas fa-user"></i> My Profile</a></li>
                    <li><a href="vehicleRegistration.php" class="active"><i class="fas fa-car"></i> Register Vehicle</a>
                    </li>
                    <li><a href="1_parkingBooking.php"><i class="fas fa-id-badge"></i> Booking Parking</a></li>
                    <li><a href="module4/MySummon.php"><i class="fas fa-exclamation-triangle"></i> My Summons</a></li>
                <?php endif; ?>

                <?php if ($role === "SMU Staff"): ?>
                    <li><a href="module4/issueSummon.php"><i class="fas fa-file-alt"></i> Issue Summon</a></li>
                    <li><a href="module4/summonList.php"><i class="fas fa-list"></i> Summon Records List</a></li>
                    <li><a href="module4/summonReport.php"><i class="fas fa-chart-bar"></i> Summon Report</a></li>
                <?php endif; ?>

                <?php if ($role === "Administrator"): ?>
                    <li><a href="1_ProfilePage.php"><i class="fas fa-user-cog"></i> My Profile</a></li>
                <?php endif; ?>

                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="card">
                <h2 style="border-bottom: 2px solid var(--primary-color); padding-bottom: 15px; margin-bottom: 20px;">
                    Vehicle Registration
                </h2>

                <?php if ($msg != ""): ?>
                    <div class="<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class="form-group">
                        <label>Vehicle Type</label>
                        <select name="vehicle_type" required>
                            <option value="Car">Car</option>
                            <option value="Motorcycle">Motorcycle</option>
                            <option value="SUV/MPV">SUV / MPV</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Vehicle Brand</label>
                        <input type="text" name="vehicle_brand" placeholder="e.g. Honda, Proton" required>
                    </div>

                    <div class="form-group">
                        <label>Plate Number</label>
                        <input type="text" name="plate_number" placeholder="e.g. ABC 1234"
                            style="text-transform: uppercase;" required>
                    </div>

                    <div class="form-group">
                        <label>License Class</label>
                        <select name="license_class" required>
                            <option value="D">Class D (Car)</option>
                            <option value="DA">Class DA (Auto Car)</option>
                            <option value="B2">Class B2 (Motorcycle < 250cc)</option>
                            <option value="B">Class B (Motorcycle > 500cc)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>License Due Date</label>
                        <input type="date" name="license_due_date" required>
                    </div>

                    <div class="form-group">
                        <label>Vehicle Grant (Upload Image/PDF)</label>
                        <input type="file" name="vehicle_grant" accept=".jpg,.jpeg,.png,.pdf" required>
                    </div>

                    <button type="submit" class="btn">Submit Registration</button>
                </form>
            </div>
        </main>
    </div>
</body>

</html>