<?php
// gallerydisplay.php - Fetch and display gallery items
require_once 'connection.php';

// Fetch gallery items
$galleries = [];
$stats = [
    'total_galleries' => 0,
    'total_images' => 0,
    'total_videos' => 0
];

// Get all gallery items ordered by creation date (newest first)
$sql = "SELECT * FROM gallery ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $galleries[] = $row;
        
        // Count images (check for non-empty strings)
        for ($i = 1; $i <= 10; $i++) {
            if (!empty($row['image_' . $i]) && $row['image_' . $i] !== '') {
                $stats['total_images']++;
            }
        }
        
        // Count videos (check for non-empty strings)
        for ($i = 1; $i <= 10; $i++) {
            if (!empty($row['video_' . $i]) && $row['video_' . $i] !== '') {
                $stats['total_videos']++;
            }
        }
    }
    $stats['total_galleries'] = count($galleries);
}

// Check if JSON response is requested (for AJAX calls from gallery.html)
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_GET['format']) && $_GET['format'] === 'json')) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'galleries' => $galleries,
        'stats' => $stats
    ]);
    $conn->close();
    exit();
}

$conn->close();
?>
