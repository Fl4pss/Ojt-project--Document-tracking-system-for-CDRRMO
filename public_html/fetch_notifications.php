<?php
session_start();
include 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

$username = $_SESSION['username'];

// Validate database connection
if (!$conn) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$sql = "SELECT id, activity, created_at FROM notifications WHERE status = 'unread' AND username = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'Failed to prepare statement']);
    exit;
}

$stmt->bind_param("s", $username);

if (!$stmt->execute()) {
    echo json_encode(['error' => 'Failed to fetch notifications']);
    exit;
}

$result = $stmt->get_result();
$notifications = [];

while ($row = $result->fetch_assoc()) {
    $notifications[] = [
        'id' => $row['id'],
        'activity' => htmlspecialchars($row['activity'], ENT_QUOTES, 'UTF-8'),
        'created_at' => date("F j, Y, g:i a", strtotime($row['created_at']))
    ];
}

// Send JSON response
echo json_encode($notifications);
exit;
?>
