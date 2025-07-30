<?php
session_start();
include 'db_connect.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['document_id'], $data['sent_to'], $data['required_actions'])) {
        echo json_encode(["status" => "error", "message" => "Missing required fields."]);
        exit();
    }

    $document_id = $data['document_id'];
    $sent_to_array = $data['sent_to'];
    $sent_to_json = json_encode($sent_to_array); // Convert array to JSON
    $required_actions = $data['required_actions'];

    // Get current status before updating
    $query = "SELECT current_status FROM documents WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $document_id);
    $stmt->execute();
    $stmt->bind_result($current_status);
    $stmt->fetch();
    $stmt->close();

    // Update document forwarding info
    $updateQuery = "UPDATE documents 
                    SET forwarded = 'Yes', 
                        current_status = 'Pending', 
                        previous_status = ?,
                        sent_to = ?, 
                        required_actions = ?,
                        updated_at = NOW()
                    WHERE id = ?";
    
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssi", $current_status, $sent_to_json, $required_actions, $document_id);

    if ($stmt->execute()) {
        // Log to document_activity
        $performed_by = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown';
        $activityQuery = "INSERT INTO document_activity (document_id, action_type, performed_by, details)
                          VALUES (?, 'forwarded', ?, ?)";
        $activityDetails = "Forwarded to: " . implode(", ", $sent_to_array) . ". Required Actions: " . $required_actions;

        $logStmt = $conn->prepare($activityQuery);
        $logStmt->bind_param("iss", $document_id, $performed_by, $activityDetails);
        $logStmt->execute();
        $logStmt->close();

        $infoQuery = "SELECT tracking_no, subject FROM documents WHERE id = ?";
        $infoStmt = $conn->prepare($infoQuery);
        $infoStmt->bind_param("i", $document_id);
        $infoStmt->execute();
        $infoStmt->bind_result($tracking_no, $subject);
        $infoStmt->fetch();
        $infoStmt->close();

        $notification_message = "New Incoming Document: " . $subject;
        $notification_status  = "unread";
        $sender               = isset($_SESSION['username']) ? $_SESSION['username'] : 'System';
        $notified_user_id     = json_encode($sent_to_array);
        $created_at           = date('Y-m-d H:i:s');

        $notifyQuery = "INSERT INTO notifications 
                        (document_id, tracking_no, message, status, notified_user_id, sender, created_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $notifyStmt = $conn->prepare($notifyQuery);
        $notifyStmt->bind_param("issssss", $document_id, $tracking_no, $notification_message, $notification_status, $notified_user_id, $sender, $created_at);
        $notifyStmt->execute();
        $notifyStmt->close();


        echo json_encode(["status" => "success", "message" => "Document forwarded successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to forward document."]);
    }

    $stmt->close();
    $conn->close();
}
?>
