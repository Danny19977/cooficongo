<?php
session_start();
require_once 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_uuid'])) {
    header("Location: ../Login.html?error=not_logged_in");
    exit();
}

// Get gallery UUID from URL
if (!isset($_GET['uuid'])) {
    header("Location: ../gallerypost.php?error=no_uuid");
    exit();
}

$gallery_uuid = $_GET['uuid'];
$user_uuid = $_SESSION['user_uuid'];

// Fetch gallery data
$sql = "SELECT * FROM gallery WHERE uuid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $gallery_uuid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    header("Location: ../gallerypost.php?error=gallery_not_found");
    exit();
}

$gallery = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $category = trim($_POST['category']);
    
    // Validate inputs
    if (empty($category)) {
        $error = "Category is required";
    } else {
        // Handle new file uploads
        $allowed_image_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowed_video_types = ['video/mp4', 'video/mpeg', 'video/quicktime', 'video/x-msvideo', 'video/webm'];
        $max_image_size = 5 * 1024 * 1024; // 5MB
        $max_video_size = 50 * 1024 * 1024; // 50MB
        $image_upload_dir = '../assets/img/gallery/';
        $video_upload_dir = '../assets/videos/gallery/';
        
        // Function to handle single file upload
        function uploadFile($file, $upload_dir, $allowed_types, $max_size, $file_prefix = 'gallery_') {
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
            $unique_filename = uniqid($file_prefix, true) . '.' . $file_extension;
            $target_file = $upload_dir . $unique_filename;
            
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                return str_replace('../', '', $target_file);
            }
            
            return false;
        }
        
        // Process images and videos
        $updated_files = [];
        $files_to_delete = [];
        
        // Process images (1-10)
        for ($i = 1; $i <= 10; $i++) {
            $file_key = 'image_' . $i;
            $delete_key = 'delete_' . $file_key;
            
            // Check if user wants to delete this file
            if (isset($_POST[$delete_key]) && $_POST[$delete_key] == '1') {
                if (!empty($gallery[$file_key])) {
                    $files_to_delete[] = $gallery[$file_key];
                }
                $updated_files[$file_key] = null;
            }
            // Check if new file uploaded
            elseif (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] != 4) {
                $result = uploadFile($_FILES[$file_key], $image_upload_dir, $allowed_image_types, $max_image_size, 'gallery_img_');
                
                if ($result === false) {
                    $error = "Failed to upload image " . $i;
                    break;
                } elseif ($result !== null) {
                    // Delete old file if exists
                    if (!empty($gallery[$file_key])) {
                        $files_to_delete[] = $gallery[$file_key];
                    }
                    $updated_files[$file_key] = $result;
                }
            }
        }
        
        // Process videos (1-10) if no errors so far
        if (!isset($error)) {
            for ($i = 1; $i <= 10; $i++) {
                $file_key = 'video_' . $i;
                $delete_key = 'delete_' . $file_key;
                
                // Check if user wants to delete this file
                if (isset($_POST[$delete_key]) && $_POST[$delete_key] == '1') {
                    if (!empty($gallery[$file_key])) {
                        $files_to_delete[] = $gallery[$file_key];
                    }
                    $updated_files[$file_key] = null;
                }
                // Check if new file uploaded
                elseif (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] != 4) {
                    $result = uploadFile($_FILES[$file_key], $video_upload_dir, $allowed_video_types, $max_video_size, 'gallery_vid_');
                    
                    if ($result === false) {
                        $error = "Failed to upload video " . $i;
                        break;
                    } elseif ($result !== null) {
                        // Delete old file if exists
                        if (!empty($gallery[$file_key])) {
                            $files_to_delete[] = $gallery[$file_key];
                        }
                        $updated_files[$file_key] = $result;
                    }
                }
            }
        }
        
        // Update database if no errors
        if (!isset($error)) {
            // Build UPDATE query dynamically
            $update_fields = ['category = ?'];
            $params = [$category];
            $types = 's';
            
            foreach ($updated_files as $key => $value) {
                $update_fields[] = $key . ' = ?';
                $params[] = $value;
                $types .= 's';
            }
            
            $update_fields[] = 'updated_at = ?';
            $params[] = date('Y-m-d H:i:s');
            $types .= 's';
            
            $params[] = $gallery_uuid;
            $types .= 's';
            
            $sql = "UPDATE gallery SET " . implode(', ', $update_fields) . " WHERE uuid = ?";
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param($types, ...$params);
                
                if ($stmt->execute()) {
                    // Delete old files
                    foreach ($files_to_delete as $file) {
                        if (file_exists('../' . $file)) {
                            unlink('../' . $file);
                        }
                    }
                    
                    $stmt->close();
                    $conn->close();
                    header("Location: ../gallerypost.php?success=gallery_updated");
                    exit();
                } else {
                    $error = "Failed to update gallery";
                }
                $stmt->close();
            } else {
                $error = "Database error";
            }
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Gallery - CooFICongo</title>
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/main.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%); min-height: 100vh;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card dashboard-card-glass shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="fw-bold" style="font-family: var(--heading-font); color: var(--accent-color);">
                                <i class="bi bi-pencil-square me-2"></i>Edit Gallery
                            </h2>
                            <a href="../gallerypost.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Gallery
                            </a>
                        </div>
                        
                        <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Flexibility Notice -->
                        <div class="alert alert-info border-0 mb-4">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-info-circle-fill fs-5 me-3 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading mb-2 fw-bold">Flexible Editing</h6>
                                    <p class="mb-0 small">
                                        You can add, replace, or delete any images and videos. Leave slots empty if you don't need them. 
                                        Only upload/replace the items you want to change.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label for="category" class="form-label fw-semibold">Category / Album Name *</label>
                                <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($gallery['category']); ?>" required>
                            </div>
                            
                            <!-- Images Section -->
                            <div class="mb-4">
                                <h4 class="mb-3"><i class="bi bi-card-image text-success me-2"></i>Images (All Optional)</h4>
                                <div class="alert alert-light py-2 mb-3">
                                    <small><i class="bi bi-lightbulb me-1"></i>You can leave any image slot empty. Only upload what you need.</small>
                                </div>
                                <div class="row g-3">
                                    <?php for($i = 1; $i <= 10; $i++): 
                                        $img_key = 'image_' . $i;
                                        $has_image = !empty($gallery[$img_key]);
                                    ?>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <label class="form-label fw-semibold">Image <?php echo $i; ?></label>
                                                <?php if ($has_image): ?>
                                                <div class="mb-2">
                                                    <img src="../<?php echo htmlspecialchars($gallery[$img_key]); ?>" 
                                                         class="img-fluid rounded" 
                                                         style="max-height: 150px; object-fit: cover;">
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="delete_<?php echo $img_key; ?>" value="1" 
                                                               id="delete_<?php echo $img_key; ?>">
                                                        <label class="form-check-label text-danger" for="delete_<?php echo $img_key; ?>">
                                                            Delete this image
                                                        </label>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <input class="form-control" type="file" name="<?php echo $img_key; ?>" accept="image/*">
                                                <div class="form-text">
                                                    <?php echo $has_image ? 'Upload new image to replace' : 'Upload image'; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <!-- Videos Section -->
                            <div class="mb-4">
                                <h4 class="mb-3"><i class="bi bi-play-circle text-primary me-2"></i>Videos (All Optional)</h4>
                                <div class="alert alert-light py-2 mb-3">
                                    <small><i class="bi bi-lightbulb me-1"></i>Videos are completely optional. Only upload if you have video content.</small>
                                </div>
                                <div class="row g-3">
                                    <?php for($i = 1; $i <= 10; $i++): 
                                        $vid_key = 'video_' . $i;
                                        $has_video = !empty($gallery[$vid_key]);
                                    ?>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <label class="form-label fw-semibold">Video <?php echo $i; ?></label>
                                                <?php if ($has_video): ?>
                                                <div class="mb-2">
                                                    <video class="w-100 rounded" style="max-height: 150px;" controls>
                                                        <source src="../<?php echo htmlspecialchars($gallery[$vid_key]); ?>">
                                                    </video>
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="delete_<?php echo $vid_key; ?>" value="1" 
                                                               id="delete_<?php echo $vid_key; ?>">
                                                        <label class="form-check-label text-danger" for="delete_<?php echo $vid_key; ?>">
                                                            Delete this video
                                                        </label>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <input class="form-control" type="file" name="<?php echo $vid_key; ?>" accept="video/*">
                                                <div class="form-text">
                                                    <?php echo $has_video ? 'Upload new video to replace' : 'Upload video'; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>Update Gallery
                                </button>
                                <a href="../gallerypost.php" class="btn btn-outline-secondary btn-lg">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .dashboard-card-glass { 
            background: rgba(255,255,255,0.92); 
            border-radius: 22px; 
            box-shadow: 0 8px 32px 0 rgba(46,163,89,0.10); 
            border: 1px solid #e0f2f1; 
        }
    </style>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
