<?php
/**
 * SEGMENT 1: SESSION & ACCESS CONTROL
 * ---------------------------------------------------------
 * We start the session to access global variables like user_id.
 * If no user_id is found, we redirect to login for security.
 */
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: 1_Login.php");
    exit();
}

/**
 * SEGMENT 2: CSRF SECURITY TOKEN
 * ---------------------------------------------------------
 * We generate a random 32-byte string if it doesn't exist.
 * This token is hidden in every form to prevent unauthorized 
 * external scripts from submitting data on behalf of the user.
 */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$msg = "";
$msg_type = "";

/**
 * SEGMENT 3: POST REQUEST HANDLING (LOGIC LAYER)
 * ---------------------------------------------------------
 * This huge block only runs when a user clicks a "Submit" button.
 * It identifies which form was sent using the 'form_action' hidden input.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verify the CSRF token before processing any data
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }

    // ACTION: Update Name and Phone
    if ($_POST['form_action'] == 'update_student') {
        $name = trim($_POST['name']);
        $phone = trim($_POST['phone']);
        $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ? WHERE user_id = ?");
        $stmt->bind_param("sss", $name, $phone, $user_id);
        if ($stmt->execute()) {
            $msg = "Profile updated successfully!";
            $msg_type = "success";
            $_SESSION['name'] = $name; // Sync session name with DB
        }
        $stmt->close();
    }

    // ACTION: Profile Photo Upload
    // Checks for valid extensions and moves the file to the 'uploads/' folder.
    elseif ($_POST['form_action'] == 'update_photo') {
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                // Use timestamp in filename to prevent browser caching issues
                $new_filename = "photo_" . $user_id . "_" . time() . "." . $ext;
                $target = "uploads/" . $new_filename;
                if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target)) {
                    $stmt = $conn->prepare("UPDATE users SET photo = ? WHERE user_id = ?");
                    $stmt->bind_param("ss", $target, $user_id);
                    $stmt->execute();
                    $stmt->close();
                    $msg = "Profile photo updated!";
                    $msg_type = "success";
                }
            } else {
                $msg = "Invalid file type (JPG/PNG only).";
                $msg_type = "error";
            }
        }
    }

    // ACTION: Change Password
    // Compares current password hash before allowing a new hash to be saved.
    elseif ($_POST['form_action'] == 'change_password') {
        $current_p = $_POST['current_password'];
        $new_p = $_POST['new_password'];
        $confirm_p = $_POST['confirm_password'];

        if ($new_p !== $confirm_p) {
            $msg = "New passwords do not match!";
            $msg_type = "error";
        } else {
            $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            if ($res && password_verify($current_p, $res['password'])) {
                $hashed_p = password_hash($new_p, PASSWORD_DEFAULT);
                $upd = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                $upd->bind_param("ss", $hashed_p, $user_id);
                $upd->execute();
                $msg = "Password updated!";
                $msg_type = "success";
                $upd->close();
            } else {
                $msg = "Current password incorrect.";
                $msg_type = "error";
            }
            $stmt->close();
        }
    }

    // ACTION: Update Vehicle Details
    elseif ($_POST['form_action'] == 'update_vehicle') {
        $v_id = $_POST['vehicle_id'];
        $brand = trim($_POST['vehicle_brand']);
        $due = $_POST['license_due_date'];
        $stmt = $conn->prepare("UPDATE vehicles SET vehicle_brand = ?, license_due_date = ? WHERE vehicle_id = ? AND user_id = ?");
        $stmt->bind_param("ssss", $brand, $due, $v_id, $user_id);
        if ($stmt->execute()) {
            $msg = "Vehicle info updated!";
            $msg_type = "success";
        }
        $stmt->close();
    }

    // ACTION: Delete Account
    // Deletes child records (vehicles) first to maintain database integrity.
    elseif ($_POST['form_action'] == 'delete_account') {
        $confirm_password = $_POST['confirm_password'];
        $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $user_data = $stmt->get_result()->fetch_assoc();
        if ($user_data && password_verify($confirm_password, $user_data['password'])) {
            $conn->query("DELETE FROM vehicles WHERE user_id = '$user_id'");
            if ($conn->query("DELETE FROM users WHERE user_id = '$user_id'")) {
                session_destroy();
                echo "<script>alert('Account deleted.'); window.location='1_Login.php';</script>";
                exit();
            }
        } else {
            $msg = "Incorrect password.";
            $msg_type = "error";
        }
        $stmt->close();
    }
}

/**
 * SEGMENT 4: DATA RETRIEVAL (VIEW LAYER)
 * ---------------------------------------------------------
 * Fetches the latest user and vehicle data from the database 
 * to display in the HTML forms below.
 */
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$vehicles = [];
if ($role === "Student") {
    $stmt = $conn->prepare("SELECT * FROM vehicles WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $v_res = $stmt->get_result();
    while ($row = $v_res->fetch_assoc()) {
        $vehicles[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Profile | FKPark</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .btn-cancel {
            background-color: #666 !important;
        }

        .upload-section {
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>

<body>

    <header class="fk-header">
        <a href="1_Dashboard.php" class="header-logo"><img src="uploads/umpsa.png" alt="Logo"></a>
        <h1>FKPark Parking System</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="1_Dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <?php if ($role === "Student"): ?>
                    <li><a href="1_ProfilePage.php" class="active"><i class="fas fa-user"></i> My Profile</a></li>
                    <li><a href="1_vehicleRegistration.php"><i class="fas fa-car"></i> Register Vehicle</a></li>
                    <li><a href="parkingBooking.php"><i class="fas fa-id-badge"></i> Booking Parking</a></li>
                <?php else: ?>
                    <li><a href="1_ProfilePage.php" class="active"><i class="fas fa-user-cog"></i> My Profile</a></li>
                <?php endif; ?>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="card">
                <div class="profile-header">
                    <img src="<?php echo htmlspecialchars($user['photo']); ?>" class="profile-img">
                    <div>
                        <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                        <p><?php echo htmlspecialchars($user['user_id']); ?> (<?php echo htmlspecialchars($role); ?>)
                        </p>
                        <form method="POST" enctype="multipart/form-data" class="upload-section">
                            <input type="hidden" name="form_action" value="update_photo">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="file" name="profile_photo" id="p_file" hidden onchange="this.form.submit()">
                            <button type="button" class="btn" style="font-size: 12px;"
                                onclick="document.getElementById('p_file').click()">Change Photo</button>
                        </form>
                    </div>
                </div>

                <?php if ($msg): ?>
                    <div class="<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
                <?php endif; ?>

                <div class="tab-header">
                    <button class="tab-link active" onclick="openTab(event, 'PersonalInfo')">Personal Info</button>
                    <?php if ($role === "Student"): ?>
                        <button class="tab-link" onclick="openTab(event, 'VehicleInfo')">Vehicle Info</button>
                    <?php endif; ?>
                    <button class="tab-link" onclick="openTab(event, 'Security')">Security</button>
                    <button class="tab-link" onclick="openTab(event, 'AccountDanger')" style="color: #d9534f;">Delete
                        Account</button>
                </div>

                <div id="PersonalInfo" class="tab-content active">
                    <form method="POST" id="formPersonal">
                        <input type="hidden" name="form_action" value="update_student">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="form-group"><label>Full Name</label><input type="text" name="name"
                                value="<?php echo htmlspecialchars($user['name']); ?>" disabled required></div>
                        <div class="form-group"><label>Phone Number</label><input type="text" name="phone"
                                value="<?php echo htmlspecialchars($user['phone']); ?>" disabled required></div>
                        <div class="btn-flex">
                            <button type="button" class="btn edit-btn" onclick="enableEdit('formPersonal')">Edit
                                Info</button>
                            <button type="submit" class="btn save-btn" style="display:none;">Save Changes</button>
                            <button type="button" class="btn btn-cancel cancel-btn" style="display:none;"
                                onclick="location.reload()">Cancel</button>
                        </div>
                    </form>
                </div>

                <div id="Security" class="tab-content">
                    <form method="POST">
                        <input type="hidden" name="form_action" value="change_password">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="form-group"><label>Current Password</label><input type="password"
                                name="current_password" required></div>
                        <div class="form-group"><label>New Password</label><input type="password" name="new_password"
                                required></div>
                        <div class="form-group"><label>Confirm New Password</label><input type="password"
                                name="confirm_password" required></div>
                        <button type="submit" class="btn">Update Password</button>
                    </form>
                </div>

                <?php if ($role === "Student"): ?>
                    <div id="VehicleInfo" class="tab-content">
                        <?php if (!empty($vehicles)):
                            foreach ($vehicles as $vehicle): ?>
                                <div style="border: 1px solid #eee; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                                    <form method="POST" id="formVehicle_<?php echo $vehicle['vehicle_id']; ?>">
                                        <input type="hidden" name="form_action" value="update_vehicle">
                                        <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['vehicle_id']; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <div class="form-grid">
                                            <div class="form-group"><label>Plate Number</label><input type="text"
                                                    value="<?php echo htmlspecialchars($vehicle['plate_number']); ?>" disabled
                                                    style="background:#f4f4f4;"></div>
                                            <div class="form-group"><label>Vehicle Brand</label><input type="text"
                                                    name="vehicle_brand"
                                                    value="<?php echo htmlspecialchars($vehicle['vehicle_brand']); ?>" disabled
                                                    required></div>
                                            <div class="form-group"><label>License Due Date</label><input type="date"
                                                    name="license_due_date" value="<?php echo $vehicle['license_due_date']; ?>"
                                                    disabled required></div>
                                        </div>
                                        <div class="btn-flex">
                                            <button type="button" class="btn edit-btn"
                                                onclick="enableEdit('formVehicle_<?php echo $vehicle['vehicle_id']; ?>')">Edit
                                                Vehicle</button>
                                            <button type="submit" class="btn save-btn" style="display:none;">Save</button>
                                            <button type="button" class="btn btn-cancel cancel-btn" style="display:none;"
                                                onclick="location.reload()">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            <?php endforeach; else: ?>
                            <p style="text-align: center;">No vehicles found.</p><?php endif; ?>
                    </div>
                <?php endif; ?>

                <div id="AccountDanger" class="tab-content">
                    <div style="border: 2px dashed #d9534f; padding: 30px; border-radius: 12px;">
                        <h3 style="color: #d9534f;">Warning Zone</h3>
                        <p>Account deletion is irreversible.</p>
                        <form method="POST" onsubmit="return confirm('Confirm deletion?');">
                            <input type="hidden" name="form_action" value="delete_account">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <div class="form-group"><label>Confirm Password</label><input type="password"
                                    name="confirm_password" required style="border: 1px solid #d9534f;"></div>
                            <button type="submit" class="btn" style="background-color: #d9534f;">Delete Forever</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        /**
         * SEGMENT 9: FRONTEND INTERACTIVITY (JS)
         * ---------------------------------------------------------
         * openTab: Switches between visible divs using class toggling.
         * enableEdit: Removes 'disabled' attribute from inputs to allow typing.
         */
        function openTab(evt, tabName) {
            document.querySelectorAll(".tab-content").forEach(c => c.classList.remove("active"));
            document.querySelectorAll(".tab-link").forEach(l => l.classList.remove("active"));
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }

        function enableEdit(formId) {
            const form = document.getElementById(formId);
            form.querySelectorAll("input:not([style*='background'])").forEach(input => {
                if (input.type !== 'hidden') input.disabled = false;
            });
            form.querySelector(".save-btn").style.display = "inline-block";
            form.querySelector(".cancel-btn").style.display = "inline-block";
            form.querySelector(".edit-btn").style.display = "none";
        }
    </script>
</body>

</html>