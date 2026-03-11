<?php
/**
 * Authentication Check
 * Include this file at the top of any protected page to ensure user is logged in
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in via session
if (!isset($_SESSION['user_uuid']) || empty($_SESSION['user_uuid'])) {
    // Check for "Remember Me" cookie
    if (isset($_COOKIE['remember_token'])) {
        try {
            require_once __DIR__ . '/connection.php';
            if (!isset($conn) || !($conn instanceof mysqli)) {
                throw new RuntimeException('Database connection unavailable');
            }

            $cookie_parts = explode(':', $_COOKIE['remember_token'], 2);
            if (count($cookie_parts) === 2) {
                $user_uuid = $cookie_parts[0];
                $token = $cookie_parts[1];

                // Verify token from database
                $stmt = $conn->prepare("
                    SELECT rt.token_hash, u.uuid, u.username, u.role 
                    FROM remember_tokens rt 
                    JOIN users u ON rt.user_uuid = u.uuid 
                    WHERE rt.user_uuid = ? AND rt.expires_at > NOW()
                ");

                if ($stmt) {
                    $stmt->bind_param('s', $user_uuid);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) {
                        // Verify the token hash
                        if (password_verify($token, $row['token_hash'])) {
                            // Valid token - restore session
                            session_regenerate_id(true);
                            $_SESSION['user_uuid'] = $row['uuid'];
                            $_SESSION['username'] = $row['username'];
                            $_SESSION['role'] = $row['role'] ?? 'User';

                            $stmt->close();
                            // User is now logged in via remember me
                            return;
                        }
                    }
                    $stmt->close();
                }
            }
        } catch (Throwable $e) {
            // Ignore remember-me validation failures and fall through to login redirect.
        }

        // Invalid, expired, or unverifiable token - clear cookie
        setcookie('remember_token', '', time() - 3600, '/', '', false, true);
    }
    
    // User is not logged in, redirect to login page
    header('Location: php/login.php');
    exit();
}

// Optional: You can add role-based checks here
// For example, to restrict access to admin-only pages:
// if (isset($_SESSION['role']) && $_SESSION['role'] !== 'Admin') {
//     header('Location: unauthorized.php');
//     exit();
// }
?>
