<?php
/**
 * FKPark Registration Logic
 * -------------------------
 * 1. Clears form on fresh load (GET).
 * 2. Enforces 7-character Matric IDs.
 * 3. Handles Profile Photo security and storage.
 */

// --- 1. DATABASE CONNECTION ---
include 'db_conn.php';

// --- 2. VARIABLE INITIALIZATION ---
// This ensures that on a page refresh (GET), the form is empty.
$name = $user_id = $phone = $role = "";
$errors = [];

// --- 3. FORM SUBMISSION HANDLING (POST) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize basic text inputs
    $name = trim($_POST['name']);
    $user_id = trim($_POST['user_id']);
    $phone = trim($_POST['phone']);
    $role = $_POST['role'] ?? "";
    $password_raw = $_POST['password'];

    // --- 4. INPUT VALIDATION ---
    if (empty($name))
        $errors['name'] = "Full name is required.";

    // Matric ID Logic: Exactly 7 alphanumeric characters
    if (strlen($user_id) !== 7) {
        $errors['user_id'] = "Matric ID must be exactly 7 characters.";
    } elseif (!preg_match('/^[a-zA-Z0-9]{7}$/', $user_id)) {
        $errors['user_id'] = "Must be alphanumeric (no spaces/symbols).";
    }

    // Phone Logic: Standard 10-12 digits
    if (!preg_match('/^[0-9]{10,12}$/', $phone)) {
        $errors['phone'] = "Please enter 10-12 digits without dashes.";
    }

    // Password Logic: Security minimum
    if (strlen($password_raw) < 8) {
        $errors['password'] = "Password must be at least 8 characters.";
    }

    // --- 5. DUPLICATE CHECK ---
    if (empty($errors)) {
        $check_stmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
        $check_stmt->bind_param("s", $user_id);
        $check_stmt->execute();
        $check_stmt->store_result();
        if ($check_stmt->num_rows > 0) {
            $errors['user_id'] = "This ID is already registered.";
        }
        $check_stmt->close();
    }

    // --- 6. FILE SECURITY LOGIC ---
    $photo = "uploads/default.jpg"; // Default if no photo uploaded
    if (empty($errors) && isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $file_tmp = $_FILES['photo']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

        // Logic: Verify file is a real image
        $check_img = getimagesize($file_tmp);

        if ($check_img === false) {
            $errors['photo'] = "File is not a valid image.";
        } elseif (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $errors['photo'] = "Only JPG, JPEG, and PNG allowed.";
        } elseif ($_FILES['photo']['size'] > 2000000) {
            $errors['photo'] = "Image must be under 2MB.";
        } else {
            // Rename to prevent overwriting
            $new_filename = bin2hex(random_bytes(4)) . "_" . $user_id . "." . $ext;
            $photo = "uploads/" . $new_filename;
            if (!file_exists('uploads/'))
                mkdir('uploads/', 0777, true);
            move_uploaded_file($file_tmp, $photo);
        }
    }

    // --- 7. DATABASE INSERTION ---
    if (empty($errors)) {
        $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (user_id, password, role, name, phone, photo) VALUES (?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($sql);
        $insert_stmt->bind_param("ssssss", $user_id, $password_hashed, $role, $name, $phone, $photo);

        if ($insert_stmt->execute()) {
            echo "<script>alert('Registration Successful!'); window.location='1_Login.php';</script>";
            exit();
        } else {
            $errors['general'] = "System Error: Could not save data.";
        }
        $insert_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>FKPark | Registration</title>
    <link rel="stylesheet" href="0_style.css">
</head>

<body>

    <header class="fk-header">
        <div class="header-logo"><img src="uploads/umpsa.png" alt="Logo"></div>
        <h1>FKPark Parking System</h1>
    </header>

    <div class="container">
        <div class="card registration-card">
            <h2>Account Registration</h2>

            <?php if (isset($errors['general'])): ?>
                <div class="error"><?php echo $errors['general']; ?></div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data" autocomplete="off">
                <div class="form-grid">

                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" required
                            class="<?php echo isset($errors['name']) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($name); ?>">
                        <small class="guidance-text">Enter name as per IC/Passport.</small>
                        <?php if (isset($errors['name'])): ?>
                            <span class="error-msg"><?php echo $errors['name']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Matric ID / User ID</label>
                        <input type="text" name="user_id" required maxlength="7" minlength="7" pattern="[A-Za-z0-9]{7}"
                            class="<?php echo isset($errors['user_id']) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($user_id); ?>" placeholder="e.g. CA23022">
                        <small class="guidance-text">Must be exactly 7 characters (Alphanumeric).</small>
                        <?php if (isset($errors['user_id'])): ?>
                            <span class="error-msg"><?php echo $errors['user_id']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" required placeholder="0123456789"
                            class="<?php echo isset($errors['phone']) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($phone); ?>">
                        <small class="guidance-text">Format: 10-12 digits without dashes.</small>
                        <?php if (isset($errors['phone'])): ?>
                            <span class="error-msg"><?php echo $errors['phone']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select name="role">
                            <option value="Student" <?php echo ($role == "Student") ? "selected" : ""; ?>>Student</option>
                            <option value="SMU Staff" <?php echo ($role == "SMU Staff") ? "selected" : ""; ?>>SMU Staff
                            </option>
                            <option value="Administrator" <?php echo ($role == "Administrator") ? "selected" : ""; ?>>
                                Administrator</option>
                        </select>
                        <small class="guidance-text">Select your university status.</small>
                    </div>

                    <div class="form-group">
                        <label>Create Password</label>
                        <input type="password" name="password" required minlength="8"
                            class="<?php echo isset($errors['password']) ? 'input-error' : ''; ?>">
                        <small class="guidance-text">Required: At least 8 characters.</small>
                        <?php if (isset($errors['password'])): ?>
                            <span class="error-msg"><?php echo $errors['password']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Profile Photo</label>
                        <input type="file" name="photo"
                            class="<?php echo isset($errors['photo']) ? 'input-error' : ''; ?>">
                        <small class="guidance-text">Optional. Max 2MB (JPG/PNG).</small>
                        <?php if (isset($errors['photo'])): ?>
                            <span class="error-msg"><?php echo $errors['photo']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="grid-full btn-flex">
                        <button type="submit" class="btn">Register Account</button>
                        <button type="button" class="btn btn-clear"
                            onclick="window.location.href=window.location.href">Clear Form</button>
                    </div>

                    <div class="grid-full" style="text-align: center; margin-top: 10px;">
                        Already have an account? <a href="1_Login.php"
                            style="color: var(--accent-color); font-weight: bold;">Login here</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>