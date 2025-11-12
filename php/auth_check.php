<?php
/**
 * Authentication Check
 * Include this file at the top of any protected page to ensure user is logged in
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_uuid']) || empty($_SESSION['user_uuid'])) {
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
