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
    
    // Handle image upload
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        $file_type = $_FILES['image']['type'];
        $file_size = $_FILES['image']['size'];
        
        // Validate file type and size
        if (!in_array($file_type, $allowed_types)) {
            header("Location: ../blogpost.php?error=invalid_image_type");
            exit();
        }
        
        if ($file_size > $max_size) {
            header("Location: ../blogpost.php?error=image_too_large");
            exit();
        }
        
        // Generate unique filename
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $unique_filename = uniqid('blog_', true) . '.' . $file_extension;
        $upload_dir = '../assets/img/blog/';
        
        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $target_file = $upload_dir . $unique_filename;
        
        // Move uploaded file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = 'assets/img/blog/' . $unique_filename;
        } else {
            header("Location: ../blogpost.php?error=upload_failed");
            exit();
        }
    } else {
        header("Location: ../blogpost.php?error=no_image");
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
    $sql = "INSERT INTO blogposts (uuid, user_uuid, title, category, image, body, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        header("Location: ../blogpost.php?error=database_error");
        exit();
    }
    
    $stmt->bind_param("ssssssss", $post_uuid, $user_uuid, $title, $category, $image_path, $body, $created_at, $updated_at);
    
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
