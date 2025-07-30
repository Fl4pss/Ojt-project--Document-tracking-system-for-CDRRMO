<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['action'])) {
    $id = intval($_POST['id']);
    $action = $_POST['action'];

    $new_status = $action === 'activate' ? 'active' : 'inactive';

    $stmt = $conn->prepare("UPDATE admin_users SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $id);

    if ($stmt->execute()) {
        echo "User status updated to " . ucfirst($new_status);
    } else {
        http_response_code(500);
        echo "Failed to update user status.";
    }

    $stmt->close();
} else {
    http_response_code(400);
    echo "Invalid request.";
}
?>
