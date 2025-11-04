<?php
// Include database connection
require_once 'connection.php';

// Function to create newsletter_emails table if it doesn't exist
function createNewsletterTable($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS newsletter_emails (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        subscribed_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status VARCHAR(20) DEFAULT 'active'
    )";
    
    return $conn->query($sql);
}

// Function to save email to database
function saveNewsletterEmail($conn, $email) {
    // First ensure table exists
    createNewsletterTable($conn);
    
    // Sanitize email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Invalid email format'];
    }
    
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO newsletter_emails (email) VALUES (?)");
    $stmt->bind_param("s", $email);
    
    try {
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Email subscribed successfully!'];
        } else {
            // Check if duplicate entry
            if ($conn->errno == 1062) {
                return ['success' => false, 'message' => 'This email is already subscribed'];
            }
            return ['success' => false, 'message' => 'Failed to subscribe email'];
        }
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    } finally {
        $stmt->close();
    }
}

// Function to get all newsletter emails
function getAllNewsletterEmails($conn) {
    createNewsletterTable($conn);
    
    $sql = "SELECT id, email, subscribed_date, status FROM newsletter_emails ORDER BY subscribed_date DESC";
    $result = $conn->query($sql);
    
    $emails = [];
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $emails[] = $row;
        }
    }
    
    return $emails;
}

// Function to delete newsletter email
function deleteNewsletterEmail($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM newsletter_emails WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    try {
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Email deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete email'];
        }
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    } finally {
        $stmt->close();
    }
}

// Function to get newsletter statistics
function getNewsletterStats($conn) {
    createNewsletterTable($conn);
    
    $stats = [
        'total' => 0,
        'active' => 0,
        'today' => 0
    ];
    
    // Total subscribers
    $result = $conn->query("SELECT COUNT(*) as count FROM newsletter_emails");
    if ($result) {
        $row = $result->fetch_assoc();
        $stats['total'] = $row['count'];
    }
    
    // Active subscribers
    $result = $conn->query("SELECT COUNT(*) as count FROM newsletter_emails WHERE status = 'active'");
    if ($result) {
        $row = $result->fetch_assoc();
        $stats['active'] = $row['count'];
    }
    
    // Today's subscribers
    $result = $conn->query("SELECT COUNT(*) as count FROM newsletter_emails WHERE DATE(subscribed_date) = CURDATE()");
    if ($result) {
        $row = $result->fetch_assoc();
        $stats['today'] = $row['count'];
    }
    
    return $stats;
}
?>
