<?php
session_start();
require_once 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_uuid'])) {
    header("Location: ../Login.html?error=not_logged_in");
    exit();
}

$user_uuid = $_SESSION['user_uuid'];
$post_uuid = isset($_GET['uuid']) ? $_GET['uuid'] : null;

// If form is submitted (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_uuid = $_POST['uuid'];
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $body = trim($_POST['blog']);
    
    // Validate inputs
    if (empty($title) || empty($category) || empty($body) || empty($post_uuid)) {
        header("Location: editpost.php?uuid=$post_uuid&error=empty_fields");
        exit();
    }
    
    // Get current post data
    $sql = "SELECT image, image_1, image_2, image_3, user_uuid FROM blogposts WHERE uuid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $post_uuid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        header("Location: ../blogpost.php?error=post_not_found");
        exit();
    }
    
    $current_post = $result->fetch_assoc();
    $stmt->close();
    
    // Check ownership
    if ($current_post['user_uuid'] !== $user_uuid) {
        header("Location: ../blogpost.php?error=permission_denied");
        exit();
    }
    
    $image_path = $current_post['image'];
    $image_1_path = $current_post['image_1'];
    $image_2_path = $current_post['image_2'];
    $image_3_path = $current_post['image_3'];
    
    // Function to handle single image upload
    function uploadImage($file, $upload_dir, $allowed_types, $max_size) {
        if (!isset($file) || $file['error'] != 0) {
            return null; // No file uploaded
        }
        
        $file_type = $file['type'];
        $file_size = $file['size'];
        
        if (!in_array($file_type, $allowed_types)) {
            return false; // Invalid type
        }
        
        if ($file_size > $max_size) {
            return false; // Too large
        }
        
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $unique_filename = uniqid('blog_', true) . '.' . $file_extension;
        $target_file = $upload_dir . $unique_filename;
        
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return 'assets/img/blog/' . $unique_filename;
        }
        
        return false; // Upload failed
    }
    
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    $upload_dir = '../assets/img/blog/';
    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Handle main image upload if new image is provided
    $new_image = uploadImage($_FILES['image'] ?? null, $upload_dir, $allowed_types, $max_size);
    if ($new_image !== null) {
        if ($new_image === false) {
            header("Location: editpost.php?uuid=$post_uuid&error=upload_failed");
            exit();
        }
        // Delete old image
        if (!empty($current_post['image']) && file_exists('../' . $current_post['image'])) {
            unlink('../' . $current_post['image']);
        }
        $image_path = $new_image;
    }
    
    // Handle additional images
    $new_image_1 = uploadImage($_FILES['image_1'] ?? null, $upload_dir, $allowed_types, $max_size);
    if ($new_image_1 !== null) {
        if ($new_image_1 === false) {
            header("Location: editpost.php?uuid=$post_uuid&error=upload_failed");
            exit();
        }
        if (!empty($current_post['image_1']) && file_exists('../' . $current_post['image_1'])) {
            unlink('../' . $current_post['image_1']);
        }
        $image_1_path = $new_image_1;
    }
    
    $new_image_2 = uploadImage($_FILES['image_2'] ?? null, $upload_dir, $allowed_types, $max_size);
    if ($new_image_2 !== null) {
        if ($new_image_2 === false) {
            header("Location: editpost.php?uuid=$post_uuid&error=upload_failed");
            exit();
        }
        if (!empty($current_post['image_2']) && file_exists('../' . $current_post['image_2'])) {
            unlink('../' . $current_post['image_2']);
        }
        $image_2_path = $new_image_2;
    }
    
    $new_image_3 = uploadImage($_FILES['image_3'] ?? null, $upload_dir, $allowed_types, $max_size);
    if ($new_image_3 !== null) {
        if ($new_image_3 === false) {
            header("Location: editpost.php?uuid=$post_uuid&error=upload_failed");
            exit();
        }
        if (!empty($current_post['image_3']) && file_exists('../' . $current_post['image_3'])) {
            unlink('../' . $current_post['image_3']);
        }
        $image_3_path = $new_image_3;
    }
    
    // Update post in database
    $updated_at = date('Y-m-d H:i:s');
    $update_sql = "UPDATE blogposts SET title = ?, category = ?, image = ?, image_1 = ?, image_2 = ?, image_3 = ?, body = ?, updated_at = ? WHERE uuid = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssssss", $title, $category, $image_path, $image_1_path, $image_2_path, $image_3_path, $body, $updated_at, $post_uuid);
    
    if ($update_stmt->execute()) {
        $update_stmt->close();
        $conn->close();
        header("Location: ../blogpost.php?success=post_updated");
        exit();
    } else {
        $update_stmt->close();
        $conn->close();
        header("Location: editpost.php?uuid=$post_uuid&error=update_failed");
        exit();
    }
}

// GET request - display edit form
if (!$post_uuid) {
    header("Location: ../blogpost.php?error=no_post_id");
    exit();
}

// Fetch post data
$sql = "SELECT * FROM blogposts WHERE uuid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $post_uuid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../blogpost.php?error=post_not_found");
    exit();
}

$post = $result->fetch_assoc();
$stmt->close();

// Check ownership
if ($post['user_uuid'] !== $user_uuid) {
    header("Location: ../blogpost.php?error=permission_denied");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog Post - CooFICongo</title>
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/main.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%); min-height: 100vh;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0" style="border-radius: 20px;">
                    <div class="card-body p-5">
                        <div class="d-flex align-items-center mb-4">
                            <a href="../blogpost.php" class="btn btn-outline-success me-3">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <h2 class="mb-0 fw-bold" style="color: #2ea359;">Edit Blog Post</h2>
                        </div>
                        
                        <!-- Error Messages -->
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <?php
                                switch($_GET['error']) {
                                    case 'empty_fields':
                                        echo 'Please fill in all required fields.';
                                        break;
                                    case 'invalid_image_type':
                                        echo 'Invalid image type. Please upload JPEG, PNG, GIF, or WebP.';
                                        break;
                                    case 'image_too_large':
                                        echo 'Image is too large. Maximum size is 5MB.';
                                        break;
                                    case 'update_failed':
                                        echo 'Failed to update blog post. Please try again.';
                                        break;
                                    default:
                                        echo 'An error occurred. Please try again.';
                                }
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form action="editpost.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="uuid" value="<?php echo htmlspecialchars($post['uuid']); ?>">
                            
                            <div class="mb-3">
                                <label for="title" class="form-label fw-semibold">Title</label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       value="<?php echo htmlspecialchars($post['title']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="category" class="form-label fw-semibold">Category</label>
                                <input type="text" class="form-control" id="category" name="category" 
                                       value="<?php echo htmlspecialchars($post['category']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label fw-semibold">Main Image</label>
                                <div class="mb-2">
                                    <img src="../<?php echo htmlspecialchars($post['image']); ?>" 
                                         class="img-fluid rounded" alt="Current Image" 
                                         style="max-height: 200px;">
                                </div>
                                <input class="form-control" type="file" id="image" name="image" accept="image/*">
                                <small class="text-muted">Leave empty to keep current image</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="image_1" class="form-label fw-semibold">Additional Image 1</label>
                                <?php if (!empty($post['image_1'])): ?>
                                <div class="mb-2">
                                    <img src="../<?php echo htmlspecialchars($post['image_1']); ?>" 
                                         class="img-fluid rounded" alt="Additional Image 1" 
                                         style="max-height: 150px;">
                                </div>
                                <?php endif; ?>
                                <input class="form-control" type="file" id="image_1" name="image_1" accept="image/*">
                                <small class="text-muted">Optional - Leave empty to keep current</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="image_2" class="form-label fw-semibold">Additional Image 2</label>
                                <?php if (!empty($post['image_2'])): ?>
                                <div class="mb-2">
                                    <img src="../<?php echo htmlspecialchars($post['image_2']); ?>" 
                                         class="img-fluid rounded" alt="Additional Image 2" 
                                         style="max-height: 150px;">
                                </div>
                                <?php endif; ?>
                                <input class="form-control" type="file" id="image_2" name="image_2" accept="image/*">
                                <small class="text-muted">Optional - Leave empty to keep current</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="image_3" class="form-label fw-semibold">Additional Image 3</label>
                                <?php if (!empty($post['image_3'])): ?>
                                <div class="mb-2">
                                    <img src="../<?php echo htmlspecialchars($post['image_3']); ?>" 
                                         class="img-fluid rounded" alt="Additional Image 3" 
                                         style="max-height: 150px;">
                                </div>
                                <?php endif; ?>
                                <input class="form-control" type="file" id="image_3" name="image_3" accept="image/*">
                                <small class="text-muted">Optional - Leave empty to keep current</small>
                            </div>
                            
                            <div class="mb-4">
                                <label for="blog" class="form-label fw-semibold">Blog Content</label>
                                <textarea class="form-control" id="blog" name="blog" rows="8" required><?php echo htmlspecialchars($post['body']); ?></textarea>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>Update Post
                                </button>
                                <a href="../blogpost.php" class="btn btn-outline-secondary btn-lg">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
