<?php
session_start();
include 'db_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    echo "Error: User not logged in.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_part = date('Y-m-d');

    // Count today's documents for tracking number
    $result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM documents WHERE DATE(created_at) = '$date_part'");
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'] + 1;
    $tracking_no = "TRK-" . date('Ymd') . "-" . str_pad($count, 3, '0', STR_PAD_LEFT);

    // Get values from POST safely
    $document_type    = $_POST['document_type'] ?? '';  
    $subject          = $_POST['subject'] ?? '';
    $required_actions = $_POST['required_actions'] ?? ''; 
    $sent_to          = $_POST['sent_to'] ?? '';

    // Ensure `sent_to` is valid JSON
    if (empty($sent_to)) {
        $sent_to_json = json_encode([]); // Store as empty array if empty
    } elseif (is_array($sent_to)) {
        $sent_to_json = json_encode($sent_to); // Convert array to JSON
    } else {
        $sent_to_json = json_encode([$sent_to]); // Convert single value to JSON array
    }

    // Get sender & owner_id from session
    $sender   = $_SESSION['username']; 
    $owner_id = $_SESSION['username'];

    $date_received  = date('Y-m-d H:i:s');
    $current_status = "Approved";
    $created_at     = date('Y-m-d H:i:s');

    // Handle file upload
    $target_dir   = "uploads/";
    $target_file  = $target_dir . basename($_FILES["attachment"]["name"]);
    move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file);

    $sql = "INSERT INTO documents 
            (tracking_no, document_type, subject, attachment, sender, owner_id, date_received, current_status, required_actions, sent_to, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssss", $tracking_no, $document_type, $subject, $target_file, $sender, $owner_id, $date_received, $current_status, $required_actions, $sent_to_json, $created_at);

    if ($stmt->execute()) {
        echo "<script>alert('Document added successfully'); window.location.href='documents.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
