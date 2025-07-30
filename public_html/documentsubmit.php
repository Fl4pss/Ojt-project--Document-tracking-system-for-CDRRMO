<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
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
            <h2>Submit Document</h2>
            <?php if ($error_message): ?>
                <p class="error"><?php echo $error_message; ?></p>
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