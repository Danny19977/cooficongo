<?php
// contactdisplay.php - Fetch contact messages for admin view
session_start();
$conn = null;
try {
    require_once 'connection.php';
} catch (Throwable $e) {
    $conn = null;
}

// Check if user is logged in (optional - remove if you want public access)
// if (!isset($_SESSION['user_uuid'])) {
//     echo json_encode(['success' => false, 'message' => 'Unauthorized']);
//     exit();
// }

$messages = [];

try {
    if (!($conn instanceof mysqli)) {
        throw new RuntimeException('Database connection unavailable');
    }

    // Support both legacy/new table and column names.
    $sql = "SELECT uuid, fullname, email, subject, message, created_at FROM contact ORDER BY created_at DESC";

    try {
        $result = $conn->query($sql);
    } catch (Throwable $inner) {
        $sql = "SELECT uuid, name AS fullname, email, subject, message, created_at FROM contacts ORDER BY created_at DESC";
        $result = $conn->query($sql);
    }

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
    }
} catch (Throwable $e) {
    // Return an empty payload instead of failing with HTTP 500.
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

if ($conn instanceof mysqli) {
    $conn->close();
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'messages' => $messages,
    'stats' => $stats
]);
?>
