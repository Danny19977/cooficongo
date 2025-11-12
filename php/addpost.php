<?php
session_start();
require_once 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_uuid'])) {
    header("Location: ../Login.html?error=not_logged_in");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user UUID from session
    $user_uuid = $_SESSION['user_uuid'];
    
    // Get form data
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $body = trim($_POST['blog']);
    
    // Validate inputs
    if (empty($title) || empty($category) || empty($body)) {
        header("Location: ../blogpost.php?error=empty_fields");
        exit();
    }
    
    // Handle image uploads - main image and optional additional images
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    $upload_dir = '../assets/img/blog/';
    
    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Function to handle single image upload
    function uploadImage($file, $upload_dir, $allowed_types, $max_size) {
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
        $unique_filename = uniqid('blog_', true) . '.' . $file_extension;
        $target_file = $upload_dir . $unique_filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return 'assets/img/blog/' . $unique_filename;
        }
        
        return false; // Upload failed
    }
    
    // Upload main image (required)
    $image_path = uploadImage($_FILES['image'], $upload_dir, $allowed_types, $max_size);
    
    if ($image_path === null) {
        header("Location: ../blogpost.php?error=no_image");
        exit();
    } elseif ($image_path === false) {
        header("Location: ../blogpost.php?error=upload_failed");
        exit();
    }
    
    // Upload additional images (optional)
    $image_1_path = uploadImage($_FILES['image_1'] ?? null, $upload_dir, $allowed_types, $max_size);
    $image_2_path = uploadImage($_FILES['image_2'] ?? null, $upload_dir, $allowed_types, $max_size);
    $image_3_path = uploadImage($_FILES['image_3'] ?? null, $upload_dir, $allowed_types, $max_size);
    
    // Check if any optional image upload failed (not just skipped)
    if ($image_1_path === false || $image_2_path === false || $image_3_path === false) {
        // Clean up already uploaded files
        if ($image_path && file_exists('../' . $image_path)) unlink('../' . $image_path);
        if ($image_1_path && file_exists('../' . $image_1_path)) unlink('../' . $image_1_path);
        if ($image_2_path && file_exists('../' . $image_2_path)) unlink('../' . $image_2_path);
        if ($image_3_path && file_exists('../' . $image_3_path)) unlink('../' . $image_3_path);
        
        header("Location: ../blogpost.php?error=upload_failed");
        exit();
    }
    
    // Generate UUID for the blog post
    function generateUUID() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    
    $post_uuid = generateUUID();
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');
    
    // Prepare and execute SQL statement
    $sql = "INSERT INTO blogposts (uuid, user_uuid, title, category, image, image_1, image_2, image_3, body, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        header("Location: ../blogpost.php?error=database_error");
        exit();
    }
    
    $stmt->bind_param("sssssssssss", $post_uuid, $user_uuid, $title, $category, $image_path, $image_1_path, $image_2_path, $image_3_path, $body, $created_at, $updated_at);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../blogpost.php?success=post_added");
        exit();
    } else {
        // If database insert fails, remove uploaded image
        if ($image_path && file_exists('../' . $image_path)) {
            unlink('../' . $image_path);
        }
        $stmt->close();
        $conn->close();
        header("Location: ../blogpost.php?error=insert_failed");
        exit();
    }
} else {
    header("Location: ../blogpost.php");
    exit();
}
?>
