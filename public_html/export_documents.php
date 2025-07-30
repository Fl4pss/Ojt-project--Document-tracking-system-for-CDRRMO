<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=documents_export.csv');

$output = fopen('php://output', 'w');

// Add CSV headers
fputcsv($output, ['Tracking No.', 'Document Type', 'Attachment', 'Owner', 'Date Received']);

$username = $_SESSION['username'];

$query = "SELECT tracking_no, document_type, attachment, sender, date_received FROM documents 
          WHERE approved_by = '$username' OR owner_id = '$username'
          ORDER BY created_at DESC";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>
