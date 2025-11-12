<?php
require_once 'connection.php';

// Fetch all blog posts ordered by most recent first
$sql = "SELECT uuid, user_uuid, title, category, image, image_1, image_2, image_3, body, created_at, updated_at 
        FROM blogposts 
        ORDER BY created_at DESC";

$result = $conn->query($sql);

$blogPosts = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $blogPosts[] = $row;
    }
}

// Get blog statistics
$stats = [
    'total_posts' => 0,
    'total_views' => 0,
    'drafts' => 0,
    'published' => 0
];

// Count total posts
$stats_sql = "SELECT COUNT(*) as total FROM blogposts";
$stats_result = $conn->query($stats_sql);
if ($stats_result) {
    $stats['total_posts'] = $stats_result->fetch_assoc()['total'];
}

// For now, set drafts to 0 and published to total (can be enhanced later with status field)
$stats['published'] = $stats['total_posts'];
$stats['drafts'] = 0;

// Calculate total views (placeholder - you can add a views column later)
// For now, use a simple formula: total_posts * average views estimation
$stats['total_views'] = $stats['total_posts'] * 100; // Placeholder calculation

// Fetch all unique categories with post counts
$categories = [];
$cat_sql = "SELECT category, COUNT(*) as count FROM blogposts GROUP BY category ORDER BY category ASC";
$cat_result = $conn->query($cat_sql);
if ($cat_result && $cat_result->num_rows > 0) {
    while($cat_row = $cat_result->fetch_assoc()) {
        $categories[] = $cat_row;
    }
}

$conn->close();

// Return JSON response if requested
if (isset($_GET['json']) && $_GET['json'] == 'true') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'count' => count($blogPosts),
        'posts' => $blogPosts,
        'stats' => $stats,
        'categories' => $categories
    ]);
    exit();
}
?>
