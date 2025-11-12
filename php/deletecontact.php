<?php
// deletecontact.php - Delete contact messages
session_start();
require_once 'connection.php';

// Check if user is logged in (optional - remove if you want public access)
// if (!isset($_SESSION['user_uuid'])) {
//     echo json_encode(['success' => false, 'message' => 'Unauthorized']);
//     exit();
// }

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['uuid']) || empty($input['uuid'])) {
    echo json_encode(['success' => false, 'message' => 'Message UUID is required']);
    exit();
}

$uuid = $input['uuid'];

// Delete the message
$sql = "DELETE FROM contact WHERE uuid = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit();
}

$stmt->bind_param("s", $uuid);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Message deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Message not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete message: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
