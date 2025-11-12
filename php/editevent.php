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
    
    // Keep existing images
    $image_path = $event['image'];
    $image_2_path = $event['image_2'];
    $image_3_path = $event['image_3'];
    $image_4_path = $event['image_4'];
    $image_5_path = $event['image_5'];
    
    // Handle image uploads
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    $upload_dir = '../assets/img/events/';
    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Function to handle single image upload
    function uploadEventImage($file, $upload_dir, $allowed_types, $max_size) {
        if (!isset($file) || $file['error'] != 0) {
            return null;
        }
        
        $file_type = $file['type'];
        $file_size = $file['size'];
        
        if (!in_array($file_type, $allowed_types)) {
            return false;
        }
        
        if ($file_size > $max_size) {
            return false;
        }
        
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $unique_filename = uniqid('event_', true) . '.' . $file_extension;
        $target_file = $upload_dir . $unique_filename;
        
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return 'assets/img/events/' . $unique_filename;
        }
        
        return false;
    }
    
    // Upload and replace images if new ones provided
    $new_image = uploadEventImage($_FILES['image'] ?? null, $upload_dir, $allowed_types, $max_size);
    if ($new_image !== null) {
        if ($new_image === false) {
            header('Location: editevent.php?uuid=' . $uuid . '&error=upload_failed');
            exit();
        }
        if (!empty($event['image']) && file_exists('../' . $event['image'])) {
            unlink('../' . $event['image']);
        }
        $image_path = $new_image;
    }
    
    $new_image_2 = uploadEventImage($_FILES['image_2'] ?? null, $upload_dir, $allowed_types, $max_size);
    if ($new_image_2 !== null) {
        if ($new_image_2 === false) {
            header('Location: editevent.php?uuid=' . $uuid . '&error=upload_failed');
            exit();
        }
        if (!empty($event['image_2']) && file_exists('../' . $event['image_2'])) {
            unlink('../' . $event['image_2']);
        }
        $image_2_path = $new_image_2;
    }
    
    $new_image_3 = uploadEventImage($_FILES['image_3'] ?? null, $upload_dir, $allowed_types, $max_size);
    if ($new_image_3 !== null) {
        if ($new_image_3 === false) {
            header('Location: editevent.php?uuid=' . $uuid . '&error=upload_failed');
            exit();
        }
        if (!empty($event['image_3']) && file_exists('../' . $event['image_3'])) {
            unlink('../' . $event['image_3']);
        }
        $image_3_path = $new_image_3;
    }
    
    $new_image_4 = uploadEventImage($_FILES['image_4'] ?? null, $upload_dir, $allowed_types, $max_size);
    if ($new_image_4 !== null) {
        if ($new_image_4 === false) {
            header('Location: editevent.php?uuid=' . $uuid . '&error=upload_failed');
            exit();
        }
        if (!empty($event['image_4']) && file_exists('../' . $event['image_4'])) {
            unlink('../' . $event['image_4']);
        }
        $image_4_path = $new_image_4;
    }
    
    $new_image_5 = uploadEventImage($_FILES['image_5'] ?? null, $upload_dir, $allowed_types, $max_size);
    if ($new_image_5 !== null) {
        if ($new_image_5 === false) {
            header('Location: editevent.php?uuid=' . $uuid . '&error=upload_failed');
            exit();
        }
        if (!empty($event['image_5']) && file_exists('../' . $event['image_5'])) {
            unlink('../' . $event['image_5']);
        }
        $image_5_path = $new_image_5;
    }
    
    $now = date('Y-m-d H:i:s');
    $update = $conn->prepare("UPDATE activitiespost SET title=?, image=?, image_2=?, image_3=?, image_4=?, image_5=?, summary=?, description=?, activity_date=?, location=?, updated_at=? WHERE uuid=?");
    $update->bind_param('ssssssssssss', $title, $image_path, $image_2_path, $image_3_path, $image_4_path, $image_5_path, $summary, $description, $activity_date, $location, $now, $uuid);
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
        <div class="mb-3">
            <label class="form-label">Event Image 1</label>
            <?php if (!empty($event['image'])): ?>
            <div class="mb-2">
                <img src="../<?php echo htmlspecialchars($event['image']); ?>" class="img-fluid rounded" style="max-height: 150px;">
            </div>
            <?php endif; ?>
            <input type="file" class="form-control" name="image" accept="image/*">
            <small class="text-muted">Optional - Leave empty to keep current</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Event Image 2</label>
            <?php if (!empty($event['image_2'])): ?>
            <div class="mb-2">
                <img src="../<?php echo htmlspecialchars($event['image_2']); ?>" class="img-fluid rounded" style="max-height: 150px;">
            </div>
            <?php endif; ?>
            <input type="file" class="form-control" name="image_2" accept="image/*">
            <small class="text-muted">Optional - Leave empty to keep current</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Event Image 3</label>
            <?php if (!empty($event['image_3'])): ?>
            <div class="mb-2">
                <img src="../<?php echo htmlspecialchars($event['image_3']); ?>" class="img-fluid rounded" style="max-height: 150px;">
            </div>
            <?php endif; ?>
            <input type="file" class="form-control" name="image_3" accept="image/*">
            <small class="text-muted">Optional - Leave empty to keep current</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Event Image 4</label>
            <?php if (!empty($event['image_4'])): ?>
            <div class="mb-2">
                <img src="../<?php echo htmlspecialchars($event['image_4']); ?>" class="img-fluid rounded" style="max-height: 150px;">
            </div>
            <?php endif; ?>
            <input type="file" class="form-control" name="image_4" accept="image/*">
            <small class="text-muted">Optional - Leave empty to keep current</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Event Image 5</label>
            <?php if (!empty($event['image_5'])): ?>
            <div class="mb-2">
                <img src="../<?php echo htmlspecialchars($event['image_5']); ?>" class="img-fluid rounded" style="max-height: 150px;">
            </div>
            <?php endif; ?>
            <input type="file" class="form-control" name="image_5" accept="image/*">
            <small class="text-muted">Optional - Leave empty to keep current</small>
        </div>
        <button type="submit" class="btn btn-success">Update Event</button>
        <a href="../eventpost.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
