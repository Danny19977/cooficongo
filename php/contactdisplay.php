<?php
// contactdisplay.php - Fetch contact messages for admin view
session_start();
require_once 'connection.php';

// Check if user is logged in (optional - remove if you want public access)
// if (!isset($_SESSION['user_uuid'])) {
//     echo json_encode(['success' => false, 'message' => 'Unauthorized']);
//     exit();
// }

// Fetch all contact messages
$sql = "SELECT uuid, fullname, email, subject, message, created_at 
        FROM contact 
        ORDER BY created_at DESC";

$result = $conn->query($sql);

$messages = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

// Calculate statistics
$stats = [
    'total' => count($messages),
    'today' => 0,
    'week' => 0
];

$today = date('Y-m-d');
$weekAgo = date('Y-m-d', strtotime('-7 days'));

foreach ($messages as $message) {
    $messageDate = date('Y-m-d', strtotime($message['created_at']));
    
    if ($messageDate === $today) {
        $stats['today']++;
    }
    
    if ($messageDate >= $weekAgo) {
        $stats['week']++;
    }
}

$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'messages' => $messages,
    'stats' => $stats
]);
?>
