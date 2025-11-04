<?php
require_once 'connection.php';
header('Content-Type: application/json');

$stats = [
    'total_events' => 0,
    'upcoming' => 0,
    'past' => 0
];

try {
    $now = date('Y-m-d');
    $sql = "SELECT COUNT(*) as total FROM activitiespost";
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        $stats['total_events'] = (int)$row['total'];
    }
    $sql = "SELECT COUNT(*) as upcoming FROM activitiespost WHERE activity_date >= '$now'";
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        $stats['upcoming'] = (int)$row['upcoming'];
    }
    $sql = "SELECT COUNT(*) as past FROM activitiespost WHERE activity_date < '$now'";
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        $stats['past'] = (int)$row['past'];
    }
    echo json_encode(['success' => true, 'stats' => $stats]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
$conn->close();
