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
    $user_uuid = $_SESSION['user_uuid'];
    
    // Get form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    
    // Validate inputs
    if (empty($username) || empty($email)) {
        header("Location: ../profile.php?error=empty_fields");
        exit();
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../profile.php?error=invalid_email");
        exit();
    }
    
    // Get current user data
    $sql = "SELECT profile_picture FROM users WHERE uuid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_uuid);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_user = $result->fetch_assoc();
    $stmt->close();
    
    $profile_picture = $current_user['profile_picture'];
    
    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        $file_type = $_FILES['profile_image']['type'];
        $file_size = $_FILES['profile_image']['size'];
        
        // Validate file type and size
        if (!in_array($file_type, $allowed_types)) {
            header("Location: ../profile.php?error=invalid_image_type");
            exit();
        }
        
        if ($file_size > $max_size) {
            header("Location: ../profile.php?error=image_too_large");
            exit();
        }
        
        // Generate unique filename
        $file_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $unique_filename = uniqid('profile_', true) . '.' . $file_extension;
        $upload_dir = '../assets/img/profiles/';
        
        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $target_file = $upload_dir . $unique_filename;
        
        // Move uploaded file
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            // Delete old profile image if it exists and is not default
            if (!empty($current_user['profile_picture']) && 
                strpos($current_user['profile_picture'], 'assets/img/profiles/') !== false && 
                file_exists('../' . $current_user['profile_picture'])) {
                unlink('../' . $current_user['profile_picture']);
            }
            $profile_picture = 'assets/img/profiles/' . $unique_filename;
        } else {
            header("Location: ../profile.php?error=upload_failed");
            exit();
        }
    }
    
    // Update user profile in database
    $updated_at = date('Y-m-d H:i:s');
    $update_sql = "UPDATE users SET username = ?, email = ?, phone = ?, bio = ?, profile_picture = ?, updated_at = ? WHERE uuid = ?";
    $update_stmt = $conn->prepare($update_sql);
    
    if ($update_stmt === false) {
        header("Location: ../profile.php?error=database_error");
        exit();
    }
    
    $update_stmt->bind_param("sssssss", $username, $email, $phone, $bio, $profile_picture, $updated_at, $user_uuid);
    
    if ($update_stmt->execute()) {
        // Update session username
        $_SESSION['username'] = $username;
        
        $update_stmt->close();
        $conn->close();
        header("Location: ../profile.php?success=profile_updated");
        exit();
    } else {
        $update_stmt->close();
        $conn->close();
        header("Location: ../profile.php?error=update_failed");
        exit();
    }
} else {
    header("Location: ../profile.php");
    exit();
}
?>
