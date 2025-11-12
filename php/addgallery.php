<?php
session_start();
require_once 'connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log the request
error_log("Gallery upload request received. Method: " . $_SERVER["REQUEST_METHOD"]);
error_log("Session user_uuid: " . (isset($_SESSION['user_uuid']) ? $_SESSION['user_uuid'] : 'NOT SET'));

// Check if user is logged in
if (!isset($_SESSION['user_uuid'])) {
    error_log("User not logged in - redirecting");
    header("Location: ../Login.html?error=not_logged_in");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_log("POST request confirmed");
    
    // Get user UUID from session
    $user_uuid = $_SESSION['user_uuid'];
    
    // Get form data
    $category = trim($_POST['category']);
    error_log("Category: " . $category);
    
    // Validate inputs
    if (empty($category)) {
        error_log("Category is empty - redirecting with error");
        header("Location: ../gallerypost.php?error=empty_fields");
        exit();
    }
    
    // Handle image and video uploads
    $allowed_image_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $allowed_video_types = ['video/mp4', 'video/mpeg', 'video/quicktime', 'video/x-msvideo', 'video/webm'];
    $max_image_size = 5 * 1024 * 1024; // 5MB for images
    $max_video_size = 50 * 1024 * 1024; // 50MB for videos
    $image_upload_dir = '../assets/img/gallery/';
    $video_upload_dir = '../assets/videos/gallery/';
    
    // Create directories if they don't exist
    if (!is_dir($image_upload_dir)) {
        mkdir($image_upload_dir, 0755, true);
    }
    if (!is_dir($video_upload_dir)) {
        mkdir($video_upload_dir, 0755, true);
    }
    
    // Function to handle single file upload
    function uploadFile($file, $upload_dir, $allowed_types, $max_size, $file_prefix = 'gallery_') {
        if (!isset($file) || $file['error'] != 0) {
            return null;
        }
        
        $file_type = $file['type'];
        $file_size = $file['size'];
        
        // Validate file type and size
        if (!in_array($file_type, $allowed_types)) {
            return false; // Invalid type
        }
        
        if ($file_size > $max_size) {
            return false; // Too large
        }
        
        // Generate unique filename
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $unique_filename = uniqid($file_prefix, true) . '.' . $file_extension;
        $target_file = $upload_dir . $unique_filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            // Return relative path
            $relative_path = str_replace('../', '', $target_file);
            return $relative_path;
        }
        
        return false; // Upload failed
    }
    
    // Arrays to store uploaded file paths
    $uploaded_files = [];
    $image_paths = [];
    $video_paths = [];
    
    // Upload images (1-10)
    for ($i = 1; $i <= 10; $i++) {
        $file_key = 'image_' . $i;
        error_log("Checking $file_key: " . (isset($_FILES[$file_key]) ? "exists" : "not set"));
        
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] != 4) {
            error_log("$file_key has file. Error code: " . $_FILES[$file_key]['error']);
            
            $result = uploadFile($_FILES[$file_key], $image_upload_dir, $allowed_image_types, $max_image_size, 'gallery_img_');
            
            if ($result === false) {
                error_log("Upload failed for $file_key");
                // Clean up already uploaded files
                foreach ($uploaded_files as $uploaded_file) {
                    if (file_exists('../' . $uploaded_file)) {
                        unlink('../' . $uploaded_file);
                    }
                }
                header("Location: ../gallerypost.php?error=upload_failed");
                exit();
            }
            
            if ($result !== null) {
                $image_paths[$file_key] = $result;
                $uploaded_files[] = $result;
            }
        }
    }
    
    // Check if at least one image was uploaded
    if (empty($image_paths)) {
        header("Location: ../gallerypost.php?error=no_image");
        exit();
    }
    
    // Upload videos (1-10)
    for ($i = 1; $i <= 10; $i++) {
        $file_key = 'video_' . $i;
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] != 4) {
            $result = uploadFile($_FILES[$file_key], $video_upload_dir, $allowed_video_types, $max_video_size, 'gallery_vid_');
            
            if ($result === false) {
                // Clean up already uploaded files
                foreach ($uploaded_files as $uploaded_file) {
                    if (file_exists('../' . $uploaded_file)) {
                        unlink('../' . $uploaded_file);
                    }
                }
                header("Location: ../gallerypost.php?error=upload_failed");
                exit();
            }
            
            if ($result !== null) {
                $video_paths[$file_key] = $result;
                $uploaded_files[] = $result;
            }
        }
    }
    
    // Generate UUID for the gallery item
    function generateUUID() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    
    $gallery_uuid = generateUUID();
    $created_at = date('Y-m-d H:i:s');
    
    // Prepare SQL statement
    $sql = "INSERT INTO gallery (uuid, user_uuid, category, image_1, image_2, image_3, image_4, image_5, image_6, image_7, image_8, image_9, image_10, video_1, video_2, video_3, video_4, video_5, video_6, video_7, video_8, video_9, video_10, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        // Clean up uploaded files
        foreach ($uploaded_files as $uploaded_file) {
            if (file_exists('../' . $uploaded_file)) {
                unlink('../' . $uploaded_file);
            }
        }
        header("Location: ../gallerypost.php?error=database_error");
        exit();
    }
    
    // Prepare parameters for binding
    $params = [
        $gallery_uuid, 
        $user_uuid, 
        $category
    ];
    
    // Add image paths (use empty string instead of null)
    for ($i = 1; $i <= 10; $i++) {
        $key = 'image_' . $i;
        $params[] = isset($image_paths[$key]) ? $image_paths[$key] : '';
    }
    
    // Add video paths (use empty string instead of null)
    for ($i = 1; $i <= 10; $i++) {
        $key = 'video_' . $i;
        $params[] = isset($video_paths[$key]) ? $video_paths[$key] : '';
    }
    
    // Add created_at
    $params[] = $created_at;
    
    // Bind parameters (24 params: uuid, user_uuid, category, 10 images, 10 videos, created_at)
    $types = 'sss' . str_repeat('s', 20) . 's'; // 3 strings + 20 nullable strings + 1 string
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../gallerypost.php?success=gallery_added");
        exit();
    } else {
        // If database insert fails, remove uploaded files
        foreach ($uploaded_files as $uploaded_file) {
            if (file_exists('../' . $uploaded_file)) {
                unlink('../' . $uploaded_file);
            }
        }
        $stmt->close();
        $conn->close();
        header("Location: ../gallerypost.php?error=database_error");
        exit();
    }
} else {
    header("Location: ../gallerypost.php");
    exit();
}
?>
