<?php
session_start();
include 'db_connect.php';

$pin = $_POST['pin'];
$username = $_SESSION['username'];

// Sanitize the PIN
$pin = mysqli_real_escape_string($conn, $pin);

// Fetch the stored PIN from the database
$query = "SELECT pin FROM admin_users WHERE username = '$username'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    // Check if the entered PIN matches the stored one
    if ($row['pin'] === $pin) {
        echo "valid";
    } else {
        echo "invalid";
    }
} else {
    echo "error";
}
?>
