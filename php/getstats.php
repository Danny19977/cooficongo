<?php
require_once 'connection.php';

header('Content-Type: application/json');

// Initialize stats
$stats = [
    'total_posts' => 0,
    'total_views' => 0,
    'drafts' => 0,
    'published' => 0,
    'today_posts' => 0,
    'this_week_posts' => 0,
    'this_month_posts' => 0
];

try {
    // Count total posts
    $total_sql = "SELECT COUNT(*) as total FROM blogposts";
    $total_result = $conn->query($total_sql);
    if ($total_result) {
        $stats['total_posts'] = (int)$total_result->fetch_assoc()['total'];
    }

    // Count posts from today
    $today_sql = "SELECT COUNT(*) as today FROM blogposts WHERE DATE(created_at) = CURDATE()";
    $today_result = $conn->query($today_sql);
    if ($today_result) {
        $stats['today_posts'] = (int)$today_result->fetch_assoc()['today'];
    }

    // Count posts from this week
    $week_sql = "SELECT COUNT(*) as week FROM blogposts WHERE YEARWEEK(created_at) = YEARWEEK(NOW())";
    $week_result = $conn->query($week_sql);
    if ($week_result) {
        $stats['this_week_posts'] = (int)$week_result->fetch_assoc()['week'];
    }

    // Count posts from this month
    $month_sql = "SELECT COUNT(*) as month FROM blogposts WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())";
    $month_result = $conn->query($month_sql);
    if ($month_result) {
        $stats['this_month_posts'] = (int)$month_result->fetch_assoc()['month'];
    }

    // For now, all posts are published (you can add a status column later for drafts)
    $stats['published'] = $stats['total_posts'];
    $stats['drafts'] = 0;

    // Calculate total views (placeholder - add a views column to track real views)
    // Simple estimation: total_posts * average views per post
    $stats['total_views'] = $stats['total_posts'] * 100;

    $conn->close();

    echo json_encode([
        'success' => true,
        'stats' => $stats
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching statistics',
        'error' => $e->getMessage()
    ]);
}
?>
