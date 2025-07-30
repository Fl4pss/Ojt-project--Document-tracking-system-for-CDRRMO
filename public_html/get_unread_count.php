<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['count' => 0]);
    exit;
}

$username = $_SESSION['username'];
$likePattern = '%"' . $username . '"%';

$sql = "SELECT COUNT(*) as count FROM notifications WHERE status = 'unread' AND notified_user_id LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $likePattern);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(['count' => $row['count']]);
?>
