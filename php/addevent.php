<?php
require_once 'connection.php';
session_start();

if (!isset($_SESSION['user_uuid'])) {
    header('Location: ../eventpost.php?error=not_logged_in');
    exit();
}

$title = trim($_POST['title'] ?? '');
$summary = trim($_POST['summary'] ?? '');
$description = trim($_POST['description'] ?? '');
$activity_date = trim($_POST['activity_date'] ?? '');
$location = trim($_POST['location'] ?? '');
$user_uuid = $_SESSION['user_uuid'];

if (empty($title) || empty($summary) || empty($description) || empty($activity_date) || empty($location)) {
    header('Location: ../eventpost.php?error=empty_fields');
    exit();
}

$uuid = bin2hex(random_bytes(16));
$now = date('Y-m-d H:i:s');

$stmt = $conn->prepare("INSERT INTO activitiespost (uuid, user_uuid, title, summary, description, activity_date, location, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param('sssssssss', $uuid, $user_uuid, $title, $summary, $description, $activity_date, $location, $now, $now);

if ($stmt->execute()) {
    header('Location: ../eventpost.php?success=event_added');
} else {
    header('Location: ../eventpost.php?error=insert_failed');
}
$stmt->close();
$conn->close();
