<?php
include 'db_connect.php';

$id = $_POST['id'];
$username = $_POST['username'];
$password = $_POST['password'];
$pin = $_POST['pin'];

// Sanitize inputs
$username = mysqli_real_escape_string($conn, $username);
$id = (int)$id;
$pin = mysqli_real_escape_string($conn, $pin);

// Build the SQL query
if (!empty($password) && !empty($pin)) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE admin_users SET username = '$username', password = '$hashed', pin = '$pin' WHERE id = $id";
} elseif (!empty($password)) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE admin_users SET username = '$username', password = '$hashed' WHERE id = $id";
} elseif (!empty($pin)) {
    $sql = "UPDATE admin_users SET username = '$username', pin = '$pin' WHERE id = $id";
} else {
    $sql = "UPDATE admin_users SET username = '$username' WHERE id = $id";
}

// Execute the query
if (mysqli_query($conn, $sql)) {
    echo "success";
} else {
    http_response_code(500);
    echo "error";
}
?>
