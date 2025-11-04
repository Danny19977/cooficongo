<?php
session_start();
require_once 'connection.php';

// Set JSON header
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_uuid'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please log in to delete posts.'
    ]);
    exit();
}

// Check if UUID is provided
if (!isset($_GET['uuid']) || empty($_GET['uuid'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Post ID is required.'
    ]);
    exit();
}

$post_uuid = $_GET['uuid'];
$user_uuid = $_SESSION['user_uuid'];

// First, get the post details to check ownership and get image path
$sql = "SELECT image, user_uuid FROM blogposts WHERE uuid = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred.'
    ]);
    exit();
}

$stmt->bind_param("s", $post_uuid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Post not found.'
    ]);
    $stmt->close();
    $conn->close();
    exit();
}

$post = $result->fetch_assoc();
$stmt->close();

// Check if user owns this post (optional - remove if any admin can delete)
if ($post['user_uuid'] !== $user_uuid) {
    echo json_encode([
        'success' => false,
        'message' => 'You do not have permission to delete this post.'
    ]);
    $conn->close();
    exit();
}

// Delete the post from database
$delete_sql = "DELETE FROM blogposts WHERE uuid = ?";
$delete_stmt = $conn->prepare($delete_sql);

if ($delete_stmt === false) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred.'
    ]);
    exit();
}

$delete_stmt->bind_param("s", $post_uuid);

if ($delete_stmt->execute()) {
    // Delete the image file if it exists
    if (!empty($post['image'])) {
        $image_path = '../' . $post['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Post deleted successfully.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to delete post.'
    ]);
}

$delete_stmt->close();
$conn->close();
?>
