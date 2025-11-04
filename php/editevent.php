<?php
require_once 'connection.php';
session_start();

if (!isset($_SESSION['user_uuid'])) {
    header('Location: ../eventpost.php?error=not_logged_in');
    exit();
}

$uuid = $_GET['uuid'] ?? '';
if (empty($uuid)) {
    header('Location: ../eventpost.php?error=missing_id');
    exit();
}

// Fetch event
$stmt = $conn->prepare("SELECT * FROM activitiespost WHERE uuid = ?");
$stmt->bind_param('s', $uuid);
$stmt->execute();
$result = $stmt->get_result();
if (!$event = $result->fetch_assoc()) {
    $stmt->close();
    $conn->close();
    header('Location: ../eventpost.php?error=not_found');
    exit();
}
$stmt->close();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $activity_date = trim($_POST['activity_date'] ?? '');
    $location = trim($_POST['location'] ?? '');
    if (empty($title) || empty($summary) || empty($description) || empty($activity_date) || empty($location)) {
        header('Location: ../eventpost.php?error=empty_fields');
        exit();
    }
    $now = date('Y-m-d H:i:s');
    $update = $conn->prepare("UPDATE activitiespost SET title=?, summary=?, description=?, activity_date=?, location=?, updated_at=? WHERE uuid=?");
    $update->bind_param('sssssss', $title, $summary, $description, $activity_date, $location, $now, $uuid);
    if ($update->execute()) {
        $update->close();
        $conn->close();
        header('Location: ../eventpost.php?success=event_updated');
        exit();
    } else {
        $update->close();
        $conn->close();
        header('Location: ../eventpost.php?error=update_failed');
        exit();
    }
}
// Show edit form (minimal, for modal or direct page)
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Edit Event</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Summary</label>
            <input type="text" class="form-control" name="summary" value="<?php echo htmlspecialchars($event['summary']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="4" required><?php echo htmlspecialchars($event['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" class="form-control" name="activity_date" value="<?php echo htmlspecialchars($event['activity_date']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" class="form-control" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Update Event</button>
        <a href="../eventpost.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
