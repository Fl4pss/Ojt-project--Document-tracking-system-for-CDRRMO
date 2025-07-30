<?php
session_start();
include 'db_connect.php';

if (!isset($_POST['document_id'], $_SESSION['username'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit();
}

$documentId = $_POST['document_id'];
$username = $_SESSION['username'];

$sql = "UPDATE notifications 
        SET status = 'read' 
        WHERE document_id = ? 
        AND JSON_CONTAINS(notified_user_id, JSON_QUOTE(?))";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $documentId, $username);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update notification']);
}
