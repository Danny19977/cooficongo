<?php
// Include newsletter functions
require_once '../php/newsletter.php';

// Check if email is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Save email to database
    $result = saveNewsletterEmail($conn, $email);
    
    if ($result['success']) {
        echo 'OK'; // Success message for AJAX form
    } else {
        echo $result['message']; // Error message
    }
} else {
    echo 'No email provided';
}
?>
