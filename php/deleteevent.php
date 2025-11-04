<?php
require_once 'connection.php';
header('Content-Type: application/json');

$uuid = $_GET['uuid'] ?? '';
if (empty($uuid)) {
    echo json_encode(['success' => false, 'message' => 'Missing event ID.']);
    exit();
}

$stmt = $conn->prepare("DELETE FROM activitiespost WHERE uuid = ?");
$stmt->bind_param('s', $uuid);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete event.']);
}
$stmt->close();
$conn->close();
