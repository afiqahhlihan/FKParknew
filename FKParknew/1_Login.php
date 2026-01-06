<?php
session_start();
include 'db_conn.php';

// Logic: If already logged in, redirect to dashboard immediately
if (isset($_SESSION['user_id'])) {
    header("Location: 1_Dashboard.php");
    exit();
}

// Variable initialization for form state logic
$user_id = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = trim($_POST['user_id']); //
    $password = $_POST['password']; //

    // Logic: Database check for the User ID
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?"); //
    $stmt->bind_param("s", $user_id); //
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Logic: Verify encrypted password
        if (password_verify($password, $row['password'])) {

            // Logic: Security improvement - Regenerate ID to prevent session fixation
            session_regenerate_id(true);

            $_SESSION['user_id'] = $row['user_id']; //
            $_SESSION['role'] = $row['role']; //
            $_SESSION['name'] = $row['name']; //

            header("Location: 1_Dashboard.php"); //
            exit();

        } else {
            // Logic: Generic error to prevent password guessing detection
            $error = "Invalid User ID or Password.";
        }
    } else {
        // Logic: Generic error to prevent username enumeration
        $error = "Invalid User ID or Password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FKPark | Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="fk-header">
        <div class="header-logo">
            <img src="uploads/umpsa.png" alt="FKPark Logo">
        </div>
        <h1>FKPark Parking System</h1>
    </header>

    <div class="container">
        <div class="card">
            <h2>FKPark Login</h2>

            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>User ID (Matric/Staff ID)</label>
                    <input type="text" name="user_id" required placeholder="e.g. CA23033"
                        value="<?php echo htmlspecialchars($user_id); ?>">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Enter your password">
                </div>

                <button type="submit" class="btn">Login</button>
            </form>

            <p style="margin-top: 20px; font-size: 14px; color: #666;">
                New user? <a href="1_Register.php" style="color: var(--accent-color); font-weight: bold;">Register
                    here</a>
            </p>
        </div>
    </div>
</body>

</html> 