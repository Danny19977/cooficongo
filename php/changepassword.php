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
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate inputs
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        header("Location: ../profile.php?error=empty_fields");
        exit();
    }
    
    // Check if new passwords match
    if ($new_password !== $confirm_password) {
        header("Location: ../profile.php?error=password_mismatch");
        exit();
    }
    
    // Validate new password strength (minimum 6 characters)
    if (strlen($new_password) < 6) {
        header("Location: ../profile.php?error=password_too_short");
        exit();
    }
    
    // Get current password hash from database
    $sql = "SELECT password FROM users WHERE uuid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_uuid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        header("Location: ../profile.php?error=user_not_found");
        exit();
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    
    // Verify current password
    $password_verified = false;
    if (password_verify($current_password, $user['password'])) {
        $password_verified = true;
    } elseif ($current_password === $user['password']) {
        // Fallback for plain text passwords (not recommended)
        $password_verified = true;
    }
    
    if (!$password_verified) {
        header("Location: ../profile.php?error=incorrect_password");
        exit();
    }
    
    // Hash new password
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update password in database
    $updated_at = date('Y-m-d H:i:s');
    $update_sql = "UPDATE users SET password = ?, updated_at = ? WHERE uuid = ?";
    $update_stmt = $conn->prepare($update_sql);
    
    if ($update_stmt === false) {
        header("Location: ../profile.php?error=database_error");
        exit();
    }
    
    $update_stmt->bind_param("sss", $new_password_hash, $updated_at, $user_uuid);
    
    if ($update_stmt->execute()) {
        $update_stmt->close();
        $conn->close();
        header("Location: ../profile.php?success=password_changed");
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
