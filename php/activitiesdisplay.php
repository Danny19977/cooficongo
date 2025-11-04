<?php
// activitiesdisplay.php
require_once 'connection.php';

$activitiesPosts = [];
$stats = [
    'total_events' => 0,
    'upcoming' => 0,
    'past' => 0
];

try {
    $sql = "SELECT uuid, user_uuid, title, summary, description, activity_date, location, created_at, updated_at 
            FROM activitiespost 
            ORDER BY activity_date DESC, created_at DESC";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $activitiesPosts[] = $row;
        }
    }

    // Stats
    $now = date('Y-m-d');
    $stats['total_events'] = count($activitiesPosts);
    $stats['upcoming'] = 0;
    $stats['past'] = 0;
    foreach ($activitiesPosts as $event) {
        if ($event['activity_date'] >= $now) {
            $stats['upcoming']++;
        } else {
            $stats['past']++;
        }
    }
} catch (Exception $e) {
    // handle error
}

// Fetch all unique locations with event counts
$locations = [];
$loc_sql = "SELECT location, COUNT(*) as count FROM activitiespost GROUP BY location ORDER BY location ASC";
$loc_result = $conn->query($loc_sql);
if ($loc_result && $loc_result->num_rows > 0) {
    while($loc_row = $loc_result->fetch_assoc()) {
        $locations[] = $loc_row;
    }
}

$conn->close();

// Return JSON response if requested
if (isset($_GET['json']) && $_GET['json'] == 'true') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'count' => count($activitiesPosts),
        'activities' => $activitiesPosts,
        'stats' => $stats,
        'locations' => $locations
    ]);
    exit();
}
?>