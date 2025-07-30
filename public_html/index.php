<?php
session_start();
include 'db_connect.php';

$error_message = '';

// Improve session security
session_regenerate_id(true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Query the database for the user
    $sql = "SELECT id, username, password, role, status FROM admin_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Check if user is active
        if ($row['status'] !== 'active') {
            $error_message = "Your account has been deactivated. Please contact the administrator.";
        } elseif (password_verify($password, $row['password'])) {
            // Password is correct and account is active
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role']; // Store role in session for future use

            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Invalid Username or Password!";
        }
    } else {
        $error_message = "Invalid Username or Password!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>City of San Juan</title>
<link rel="stylesheet" href="cddrmo.css">
</head>
<body>
<div class="top-right-logos">
    <img src="img/makabago.png" alt="Logo 1">
    <img src="img/lbp.png" alt="Logo 2">
    <img src="img/sjc.png" alt="Logo 3">
</div>


<div class="login-container">
    <div class="login-box">
        <img src="img/sanjuan.png" alt="City Logo" class="logo">
        <h2>LOGIN</h2>
        <?php if (!empty($error_message)): ?>
            <p class="error"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">LOGIN</button>
        </form>
    </div>
</div>
</body>
</html>