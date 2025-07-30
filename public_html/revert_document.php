<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Fetch the document's previous status
    $query = "SELECT previous_status FROM documents WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $document = $result->fetch_assoc();

    if ($document) {
        $previousStatus = $document['previous_status'] ?: 'Pending'; // Default to 'Pending' if null

        // Update the document status
        $updateQuery = "UPDATE documents SET current_status = ?, previous_status = 'Forwarded' WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("si", $previousStatus, $id);

        if ($updateStmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Document reverted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to revert document.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Document not found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
