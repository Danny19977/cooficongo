<?php
session_start();
require_once 'connection.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_uuid'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// Check if UUID is provided
if (!isset($_GET['uuid'])) {
    echo json_encode(['success' => false, 'message' => 'No gallery UUID provided']);
    exit();
}

$gallery_uuid = $_GET['uuid'];

// Fetch gallery data to get file paths
$sql = "SELECT * FROM gallery WHERE uuid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $gallery_uuid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    echo json_encode(['success' => false, 'message' => 'Gallery not found']);
    exit();
}

$gallery = $result->fetch_assoc();
$stmt->close();

// Delete gallery from database
$sql = "DELETE FROM gallery WHERE uuid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $gallery_uuid);

if ($stmt->execute()) {
    // Delete associated files
    $files_to_delete = [];
    
    // Collect image files
    for ($i = 1; $i <= 10; $i++) {
        $key = 'image_' . $i;
        if (!empty($gallery[$key])) {
            $files_to_delete[] = $gallery[$key];
        }
    }
    
    // Collect video files
    for ($i = 1; $i <= 10; $i++) {
        $key = 'video_' . $i;
        if (!empty($gallery[$key])) {
            $files_to_delete[] = $gallery[$key];
        }
    }
    
    // Delete files from filesystem
    foreach ($files_to_delete as $file) {
        $file_path = '../' . $file;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    $stmt->close();
    $conn->close();
    echo json_encode(['success' => true, 'message' => 'Gallery deleted successfully']);
} else {
    $stmt->close();
    $conn->close();
    echo json_encode(['success' => false, 'message' => 'Failed to delete gallery']);
}
?>
