<?php
include 'db_connect.php';
session_start();

// Ensure JSON is received
$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input['document_id']) || !isset($input['action_type'])) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit();
}

$document_id = intval($input['document_id']);
$action_type = trim($input['action_type']);
$details = isset($input['details']) ? trim($input['details']) : null;
$performed_by = $_SESSION['username'] ?? 'Unknown';

// Prepare SQL and bind parameters
$stmt = $conn->prepare("
    INSERT INTO document_activity (document_id, action_type, performed_by, details) 
    VALUES (?, ?, ?, ?)
");

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
    exit();
}

$stmt->bind_param("isss", $document_id, $action_type, $performed_by, $details);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}
?>
