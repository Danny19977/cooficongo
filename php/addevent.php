<?php
require_once 'connection.php';
session_start();

if (!isset($_SESSION['user_uuid'])) {
    header('Location: ../eventpost.php?error=not_logged_in');
    exit();
}

$title = trim($_POST['title'] ?? '');
$summary = trim($_POST['summary'] ?? '');
$description = trim($_POST['description'] ?? '');
$activity_date = trim($_POST['activity_date'] ?? '');
$location = trim($_POST['location'] ?? '');
$user_uuid = $_SESSION['user_uuid'];

if (empty($title) || empty($summary) || empty($description) || empty($activity_date) || empty($location)) {
    header('Location: ../eventpost.php?error=empty_fields');
    exit();
}

// Handle image uploads
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$max_size = 5 * 1024 * 1024; // 5MB
$upload_dir = '../assets/img/events/';

// Create directory if it doesn't exist
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Function to handle single image upload
function uploadEventImage($file, $upload_dir, $allowed_types, $max_size) {
    if (!isset($file) || $file['error'] != 0) {
        return null;
    }
    
    $file_type = $file['type'];
    $file_size = $file['size'];
    
    if (!in_array($file_type, $allowed_types)) {
        return false; // Invalid type
    }
    
    if ($file_size > $max_size) {
        return false; // Too large
    }
    
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $unique_filename = uniqid('event_', true) . '.' . $file_extension;
    $target_file = $upload_dir . $unique_filename;
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return 'assets/img/events/' . $unique_filename;
    }
    
    return false; // Upload failed
}

// Upload images (all optional for events)
$image_path = uploadEventImage($_FILES['image'] ?? null, $upload_dir, $allowed_types, $max_size);
$image_2_path = uploadEventImage($_FILES['image_2'] ?? null, $upload_dir, $allowed_types, $max_size);
$image_3_path = uploadEventImage($_FILES['image_3'] ?? null, $upload_dir, $allowed_types, $max_size);
$image_4_path = uploadEventImage($_FILES['image_4'] ?? null, $upload_dir, $allowed_types, $max_size);
$image_5_path = uploadEventImage($_FILES['image_5'] ?? null, $upload_dir, $allowed_types, $max_size);

// Check if any image upload failed (not just skipped)
if ($image_path === false || $image_2_path === false || $image_3_path === false || $image_4_path === false || $image_5_path === false) {
    // Clean up already uploaded files
    $uploaded_files = [$image_path, $image_2_path, $image_3_path, $image_4_path, $image_5_path];
    foreach ($uploaded_files as $file) {
        if ($file && $file !== false && file_exists('../' . $file)) {
            unlink('../' . $file);
        }
    }
    
    header('Location: ../eventpost.php?error=upload_failed');
    exit();
}

$uuid = bin2hex(random_bytes(16));
$now = date('Y-m-d H:i:s');

$stmt = $conn->prepare("INSERT INTO activitiespost (uuid, user_uuid, title, image, image_2, image_3, image_4, image_5, summary, description, activity_date, location, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param('ssssssssssssss', $uuid, $user_uuid, $title, $image_path, $image_2_path, $image_3_path, $image_4_path, $image_5_path, $summary, $description, $activity_date, $location, $now, $now);

if ($stmt->execute()) {
    header('Location: ../eventpost.php?success=event_added');
} else {
    // Clean up uploaded images on database error
    $uploaded_files = [$image_path, $image_2_path, $image_3_path, $image_4_path, $image_5_path];
    foreach ($uploaded_files as $file) {
        if ($file && file_exists('../' . $file)) {
            unlink('../' . $file);
        }
    }
    header('Location: ../eventpost.php?error=insert_failed');
}
$stmt->close();
$conn->close();

