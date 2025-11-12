<?php
// submitcontact.php - Handle contact form submissions
session_start();
require_once 'connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to user

// Function to generate UUID
function generateUUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fullname = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validate inputs
    if (empty($fullname) || empty($email) || empty($subject) || empty($message)) {
        header("Location: ../contact.html?error=empty_fields");
        exit();
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../contact.html?error=invalid_email");
        exit();
    }
    
    // Sanitize inputs
    $fullname = htmlspecialchars($fullname, ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    
    // Generate UUID
    $uuid = generateUUID();
    $created_at = date('Y-m-d H:i:s');
    
    // Prepare SQL statement
    $sql = "INSERT INTO contact (uuid, fullname, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        error_log("Contact form SQL prepare error: " . $conn->error);
        header("Location: ../contact.html?error=database_error");
        exit();
    }
    
    $stmt->bind_param("ssssss", $uuid, $fullname, $email, $subject, $message, $created_at);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        
        // Redirect with success message
        header("Location: ../contact.html?success=message_sent");
        exit();
    } else {
        error_log("Contact form SQL execute error: " . $stmt->error);
        $stmt->close();
        $conn->close();
        header("Location: ../contact.html?error=submit_failed");
        exit();
    }
} else {
    // Not a POST request
    header("Location: ../contact.html");
    exit();
}
?>
