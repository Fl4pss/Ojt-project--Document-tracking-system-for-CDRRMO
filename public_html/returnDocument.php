<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $docId = $_GET['id'];
    $username = $_SESSION['username'];
    $timestamp = date("Y-m-d H:i:s");

    // Step 1: Update document to revert forwarded status
    $updateQuery = "UPDATE documents 
                    SET forwarded = 'no', 
                        current_status = 'returned', 
                        updated_at = ? 
                    WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $timestamp, $docId);

    if ($stmt->execute()) {
        // Step 2: Insert into document_activity for audit trail
        $action_type = 'returned'; // Make sure this is added to your ENUM
        $details = "Document reverted to sender.";

        $logQuery = "INSERT INTO document_activity (document_id, action_type, performed_by, details) 
                     VALUES (?, ?, ?, ?)";
        $logStmt = $conn->prepare($logQuery);
        $logStmt->bind_param("isss", $docId, $action_type, $username, $details);
        $logStmt->execute();

        $_SESSION['success'] = "Document has been reverted successfully.";
    } else {
        $_SESSION['error'] = "Failed to revert document.";
    }

    $stmt->close();
}

header("Location: documents.php");
exit();
