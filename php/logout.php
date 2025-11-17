<?php
/**
 * Logout Script
 * Destroys the user session and redirects to login page
 */

// Start session
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Clear the remember me cookie
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/', '', false, true);
    
    // Optional: Delete the token from database
    if (isset($_COOKIE['remember_token'])) {
        require_once __DIR__ . '/connection.php';
        $cookie_parts = explode(':', $_COOKIE['remember_token'], 2);
        if (count($cookie_parts) === 2) {
            $user_uuid = $cookie_parts[0];
            $stmt = $conn->prepare("DELETE FROM remember_tokens WHERE user_uuid = ?");
            if ($stmt) {
                $stmt->bind_param('s', $user_uuid);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
}

// Redirect to login page
header('Location: login.php?logout=success');
exit();
?>
