<?php
// Protect this page - redirect to login if not authenticated
require_once __DIR__ . '/php/auth_check.php';
require_once 'php/gallerydisplay.php';

// Get user role from session
$userRole = $_SESSION['role'] ?? 'User';

// Fetch user profile picture if logged in
$userProfilePic = 'assets/img/team/team-1.jpg'; // Default
if (isset($_SESSION['user_uuid'])) {
    // Create new connection for profile picture
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cooficongo";
    
    $pic_conn = new mysqli($servername, $username, $password, $dbname);
    
    if (!$pic_conn->connect_error) {
        $user_uuid = $_SESSION['user_uuid'];
        $pic_sql = "SELECT profile_picture FROM users WHERE uuid = ?";
        $pic_stmt = $pic_conn->prepare($pic_sql);
        $pic_stmt->bind_param("s", $user_uuid);
        $pic_stmt->execute();
        $pic_result = $pic_stmt->get_result();
        if ($pic_row = $pic_result->fetch_assoc()) {
            if (!empty($pic_row['profile_picture'])) {
                $userProfilePic = $pic_row['profile_picture'];
            }
        }
        $pic_stmt->close();
        $pic_conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Management - CooFICongo</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%); min-height: 100vh;">
    <div class="container-fluid dashboard-bg p-0">
        <div class="row min-vh-100 g-0">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block sidebar-glass sidebar py-4 px-0 position-relative">
                <div class="sidebar-sticky d-flex flex-column align-items-center h-100">
                    <div class="text-center mb-4 mt-2 animate-on-scroll fade-in-down">
                        <img src="assets/img/logo.png" alt="Logo">
                        <h5 class="mt-3 mb-0" style="font-family: var(--heading-font); letter-spacing: 1px; color: white;">CooFICongo</h5>
                        <span class="badge bg-success bg-gradient mt-2 px-3 py-1 shadow-sm">Dashboard</span>
                    </div>
                    <ul class="nav flex-column w-100 mt-4">
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link" id="blog-tab" href="blogpost.php">
                                <i class="bi bi-journal-text me-2"></i> Blog Post
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link" id="events-tab" href="event.php">
                                <i class="bi bi-calendar-event me-2"></i> Events
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link active" id="gallery-tab" href="gallerypost.php">
                                <i class="bi bi-images me-2"></i> Gallery
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link" id="newsletters-tab" href="newsletters.php">
                                <i class="bi bi-envelope-at me-2"></i> Newsletters
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link" id="contact-tab" href="contactview.php">
                                <i class="bi bi-envelope-check me-2"></i> Contact Messages
                            </a>
                        </li>
                    </ul>
                    <div class="mt-auto w-100">
                        <ul class="nav flex-column w-100">
                            <li class="nav-item mb-2">
                                <a class="nav-link sidebar-link" id="profile-tab" href="profile.php">
                                    <i class="bi bi-person-circle me-2"></i> Profile
                                </a>
                            </li>
                        </ul>
                        <div class="mb-2 text-center small text-muted">
                            <i class="bi bi-c-circle"></i> 2025 Freelance Solutions
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-5 py-4 position-relative dashboard-main">
                <!-- Header Bar -->
                <div class="d-flex align-items-center justify-content-between mb-4 animate-on-scroll fade-in-down dashboard-header-glass">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-images fs-3 text-success"></i>
                        <span class="fs-4 fw-bold" style="font-family: var(--heading-font); color: var(--heading-color); letter-spacing: 1px;">Gallery Management</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-success bg-gradient px-3 py-2 shadow-sm">
                            <?php echo htmlspecialchars(ucfirst($userRole)); ?>
                        </span>
                        <img src="<?php echo htmlspecialchars($userProfilePic); ?>" alt="User" class="rounded-circle border border-2 border-success" style="width: 40px; height: 40px; object-fit: cover;">
                    </div>
                </div>
                <div class="dashboard-page animate-on-scroll fade-in-up">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="card dashboard-card-glass hover-lift shadow-lg border-0">
                                <div class="card-body">
                                    <h2 class="mb-3 fw-bold" style="font-family: var(--heading-font); color: var(--accent-color); letter-spacing: 1px;">Add Gallery Item</h2>
                                    
                                    <!-- Flexibility Notice -->
                                    <div class="alert alert-success border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-lightbulb-fill fs-4 text-success me-3 mt-1"></i>
                                            <div>
                                                <h6 class="alert-heading mb-2 fw-bold text-success">Flexible Upload</h6>
                                                <p class="mb-0 small">
                                                    <strong>You're in control!</strong> Upload as many or as few photos and videos as you want. 
                                                    Only one image is required to create a gallery. All other fields are completely optional.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Success/Error Messages -->
                                    <div id="messageContainer"></div>
                                    
                                    <form action="php/addgallery.php" method="post" enctype="multipart/form-data" id="galleryForm">
                                        <div class="mb-3">
                                            <label for="category" class="form-label fw-semibold">Category / Album Name *</label>
                                            <input type="text" class="form-control" id="category" name="category" placeholder="e.g., Event Photos, Community Activities, etc." required>
                                            <div class="form-text">Enter a category or album name for this gallery collection</div>
                                        </div>
                                        
                                        <!-- Image Uploads Section -->
                                        <div class="mb-4">
                                            <h5 class="mb-3"><i class="bi bi-card-image text-success me-2"></i>Images (At least 1 required, up to 10 total)</h5>
                                            <div class="alert alert-info py-2 mb-3">
                                                <i class="bi bi-info-circle me-2"></i>
                                                <small>Upload as many or as few images as you want. Only the first image is required.</small>
                                            </div>
                                            <div class="row g-3">
                                                <?php for($i = 1; $i <= 10; $i++): ?>
                                                <div class="col-md-6">
                                                    <label for="image_<?php echo $i; ?>" class="form-label">
                                                        Image <?php echo $i; ?> 
                                                        <?php if($i == 1): ?>
                                                            <span class="badge bg-danger">Required</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Optional</span>
                                                        <?php endif; ?>
                                                    </label>
                                                    <input class="form-control" type="file" id="image_<?php echo $i; ?>" name="image_<?php echo $i; ?>" accept="image/*" <?php echo $i == 1 ? 'required' : ''; ?>>
                                                </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>

                                        <!-- Video Uploads Section -->
                                        <div class="mb-4">
                                            <h5 class="mb-3"><i class="bi bi-play-circle text-primary me-2"></i>Videos (All Optional - Up to 10)</h5>
                                            <div class="alert alert-info py-2 mb-3">
                                                <i class="bi bi-info-circle me-2"></i>
                                                <small>Videos are completely optional. Upload only if you have video content to share.</small>
                                            </div>
                                            <div class="row g-3">
                                                <?php for($i = 1; $i <= 10; $i++): ?>
                                                <div class="col-md-6">
                                                    <label for="video_<?php echo $i; ?>" class="form-label">
                                                        Video <?php echo $i; ?> 
                                                        <span class="badge bg-secondary">Optional</span>
                                                    </label>
                                                    <input class="form-control" type="file" id="video_<?php echo $i; ?>" name="video_<?php echo $i; ?>" accept="video/*">
                                                </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-success btn-lg mt-3 shadow-sm">
                                            <i class="bi bi-upload me-2"></i>Upload Gallery
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <!-- Gallery Stats Card -->
                            <div class="card dashboard-card-glass hover-glow shadow border-0 animate-on-scroll fade-in-right mb-4">
                                <div class="card-body text-center">
                                    <i class="bi bi-bar-chart-line fs-1 text-success mb-3"></i>
                                    <h6 class="fw-bold">Gallery Stats</h6>
                                    <div class="d-flex justify-content-center gap-4 mt-3">
                                        <div>
                                            <span class="fs-4 fw-bold text-success" id="totalGalleriesCount">
                                                <?php echo $stats['total_galleries']; ?>
                                            </span><br>
                                            <span class="text-muted small">Albums</span>
                                        </div>
                                        <div>
                                            <span class="fs-4 fw-bold text-success" id="totalImagesCount">
                                                <?php echo $stats['total_images']; ?>
                                            </span><br>
                                            <span class="text-muted small">Images</span>
                                        </div>
                                        <div>
                                            <span class="fs-4 fw-bold text-primary" id="totalVideosCount">
                                                <?php echo $stats['total_videos']; ?>
                                            </span><br>
                                            <span class="text-muted small">Videos</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Gallery Preview Card -->
                            <div class="card dashboard-card-glass hover-glow shadow border-0">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3 text-center">
                                        <i class="bi bi-eye text-success me-2"></i>Gallery Preview
                                    </h6>
                                    
                                    <!-- Gallery Container -->
                                    <div id="galleryContainer">
                                        <?php if (!empty($galleries)): ?>
                                            <?php foreach ($galleries as $index => $gallery): ?>
                                                <!-- Gallery Item <?php echo $index + 1; ?> -->
                                                <div class="gallery-item <?php echo $index > 0 ? 'd-none' : ''; ?>" data-gallery="<?php echo $index + 1; ?>">
                                                    <div class="mb-3">
                                                        <?php
                                                        // Find first available image
                                                        $firstImage = null;
                                                        for($i = 1; $i <= 10; $i++) {
                                                            $imgKey = 'image_' . $i;
                                                            if (!empty($gallery[$imgKey])) {
                                                                $firstImage = $gallery[$imgKey];
                                                                break;
                                                            }
                                                        }
                                                        ?>
                                                        <?php if ($firstImage): ?>
                                                        <img src="<?php echo htmlspecialchars($firstImage); ?>" 
                                                             class="img-fluid rounded mb-3" 
                                                             alt="Gallery preview" 
                                                             style="max-height: 200px; width: 100%; object-fit: cover;"
                                                             onerror="this.src='assets/img/logo.png'">
                                                        <?php endif; ?>
                                                    </div>
                                                    <h5 class="fw-bold text-success"><?php echo htmlspecialchars($gallery['category']); ?></h5>
                                                    <p class="text-muted small mb-2">
                                                        <i class="bi bi-images me-1"></i>
                                                        <?php 
                                                        $imageCount = 0;
                                                        for($i = 1; $i <= 10; $i++) {
                                                            if (!empty($gallery['image_' . $i])) $imageCount++;
                                                        }
                                                        echo $imageCount;
                                                        ?> Images
                                                        
                                                        <i class="bi bi-play-circle ms-2 me-1"></i>
                                                        <?php 
                                                        $videoCount = 0;
                                                        for($i = 1; $i <= 10; $i++) {
                                                            if (!empty($gallery['video_' . $i])) $videoCount++;
                                                        }
                                                        echo $videoCount;
                                                        ?> Videos
                                                    </p>
                                                    <div class="text-muted small mb-3">
                                                        <i class="bi bi-calendar3 me-1"></i>Created: 
                                                        <?php 
                                                            $date = new DateTime($gallery['created_at']);
                                                            echo $date->format('M d, Y');
                                                        ?>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="editGallery('<?php echo htmlspecialchars($gallery['uuid']); ?>')">
                                                            <i class="bi bi-pencil-square"></i> Edit
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger flex-fill" onclick="deleteGallery('<?php echo htmlspecialchars($gallery['uuid']); ?>', '<?php echo htmlspecialchars(addslashes($gallery['category'])); ?>')">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <!-- No Galleries Message -->
                                            <div class="text-center py-4">
                                                <i class="bi bi-images fs-1 text-muted mb-3 d-block"></i>
                                                <p class="text-muted">No gallery items yet. Upload your first collection!</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Pagination Controls -->
                                    <?php if (!empty($galleries)): ?>
                                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                        <button class="btn btn-outline-success btn-sm" id="prevGallery" onclick="navigateGalleries(-1)">
                                            <i class="bi bi-arrow-left"></i> Previous
                                        </button>
                                        <span class="text-muted small fw-semibold">
                                            <span id="currentGallery">1</span> / <span id="totalGalleries"><?php echo count($galleries); ?></span>
                                        </span>
                                        <button class="btn btn-outline-success btn-sm" id="nextGallery" onclick="navigateGalleries(1)">
                                            Next <i class="bi bi-arrow-right"></i>
                                        </button>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <style>
        .dashboard-bg { background: linear-gradient(120deg, #e8f5e9 0%, #f1f8e9 100%); min-height: 100vh; }
        .sidebar-glass { background: linear-gradient(160deg, #116530 0%, #2ea359 100%); box-shadow: 0 8px 32px 0 rgba(17, 101, 48, 0.15); border-top-right-radius: 32px; border-bottom-right-radius: 32px; min-height: 100vh; position: relative; z-index: 2; }
        .sidebar-link { color: #fff !important; font-weight: 500; font-size: 1.1rem; border-radius: 8px 0 0 8px; padding: 12px 24px; margin-left: 8px; transition: background 0.2s, color 0.2s, box-shadow 0.2s; }
        .sidebar-link.active, .sidebar-link:hover { background: rgba(255,255,255,0.15); color: #ffe082 !important; box-shadow: 0 4px 16px 0 rgba(17,101,48,0.10); }
        .dashboard-main { background: transparent; min-height: 100vh; z-index: 1; }
        .dashboard-header-glass { background: rgba(255,255,255,0.85); border-radius: 18px; box-shadow: 0 4px 24px 0 rgba(46,163,89,0.08); padding: 18px 32px; margin-bottom: 32px; border: 1px solid #e0f2f1; }
        .dashboard-card-glass { background: rgba(255,255,255,0.92); border-radius: 22px; box-shadow: 0 8px 32px 0 rgba(46,163,89,0.10); border: 1px solid #e0f2f1; transition: box-shadow 0.3s, transform 0.3s; }
        .dashboard-card-glass:hover { box-shadow: 0 16px 48px 0 rgba(46,163,89,0.18); transform: translateY(-6px) scale(1.01); }
        .dashboard-page { animation: fadeInUp 1s; }
        @media (max-width: 991px) { .sidebar-glass { border-radius: 0 0 32px 32px; min-height: auto; } .dashboard-main { padding: 2rem 1rem !important; } }
    </style>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // Display success/error messages from URL parameters
        window.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const messageContainer = document.getElementById('messageContainer');
            
            if (urlParams.has('success')) {
                const success = urlParams.get('success');
                let message = '';
                
                if (success === 'gallery_added') {
                    message = 'Gallery item added successfully!';
                } else if (success === 'gallery_deleted') {
                    message = 'Gallery item deleted successfully!';
                } else if (success === 'gallery_updated') {
                    message = 'Gallery item updated successfully!';
                }
                
                if (message) {
                    messageContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                }
            } else if (urlParams.has('error')) {
                const error = urlParams.get('error');
                let message = '';
                
                switch(error) {
                    case 'not_logged_in':
                        message = 'Please log in to manage gallery.';
                        break;
                    case 'empty_fields':
                        message = 'Please fill in all required fields.';
                        break;
                    case 'no_image':
                        message = 'Please upload at least one image.';
                        break;
                    case 'upload_failed':
                        message = 'Failed to upload files. Please try again.';
                        break;
                    case 'database_error':
                        message = 'Database error occurred. Please try again.';
                        break;
                    default:
                        message = 'An error occurred. Please try again.';
                }
                
                if (message) {
                    messageContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                }
            }
            
            // Remove URL parameters after displaying message
            if (urlParams.has('success') || urlParams.has('error')) {
                const cleanUrl = window.location.pathname;
                window.history.replaceState({}, document.title, cleanUrl);
            }
        });

        // Gallery Pagination
        let currentGalleryIndex = 1;
        const totalGalleries = document.querySelectorAll('.gallery-item').length;
        
        // Update total galleries display if element exists
        const totalGalleriesElement = document.getElementById('totalGalleries');
        if (totalGalleriesElement) {
            totalGalleriesElement.textContent = totalGalleries;
        }

        function navigateGalleries(direction) {
            // Only navigate if there are galleries
            if (totalGalleries === 0) return;
            
            // Hide current gallery
            const currentElement = document.querySelector(`.gallery-item[data-gallery="${currentGalleryIndex}"]`);
            if (currentElement) {
                currentElement.classList.add('d-none');
            }
            
            // Update index
            currentGalleryIndex += direction;
            
            // Loop around if at boundaries
            if (currentGalleryIndex > totalGalleries) {
                currentGalleryIndex = 1;
            } else if (currentGalleryIndex < 1) {
                currentGalleryIndex = totalGalleries;
            }
            
            // Show new gallery
            const newElement = document.querySelector(`.gallery-item[data-gallery="${currentGalleryIndex}"]`);
            if (newElement) {
                newElement.classList.remove('d-none');
            }
            
            // Update counter
            const currentGalleryElement = document.getElementById('currentGallery');
            if (currentGalleryElement) {
                currentGalleryElement.textContent = currentGalleryIndex;
            }
        }

        // Edit Gallery Function
        function editGallery(uuid) {
            window.location.href = `php/editgallery.php?uuid=${uuid}`;
        }

        // Delete Gallery Function
        function deleteGallery(uuid, category) {
            if (confirm(`Are you sure you want to delete the gallery "${category}"?\n\nThis will remove all images and videos in this album.\nThis action cannot be undone.`)) {
                // Show loading indicator
                const messageContainer = document.getElementById('messageContainer');
                messageContainer.innerHTML = `
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-hourglass-split me-2"></i>Deleting gallery...
                    </div>
                `;
                
                // Send delete request
                fetch(`php/deletegallery.php?uuid=${uuid}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'gallerypost.php?success=gallery_deleted';
                    } else {
                        messageContainer.innerHTML = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>${data.message || 'Failed to delete gallery.'}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    messageContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>An error occurred while deleting the gallery.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                });
            }
        }

        // Simple form validation enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const galleryForm = document.getElementById('galleryForm');
            console.log('Gallery form found:', galleryForm);
            
            if (galleryForm) {
                galleryForm.addEventListener('submit', function(e) {
                    console.log('Form submit event triggered');
                    
                    const category = document.getElementById('category').value.trim();
                    const image1Input = document.getElementById('image_1');
                    
                    console.log('Category value:', category);
                    console.log('Image 1 input:', image1Input);
                    console.log('Image 1 files:', image1Input.files);
                    
                    // Check if category is empty
                    if (!category) {
                        console.log('Validation failed: No category');
                        e.preventDefault();
                        e.stopPropagation();
                        alert('Please enter a category/album name.');
                        document.getElementById('category').focus();
                        return false;
                    }
                    
                    // Check if image 1 is selected
                    if (!image1Input.files || image1Input.files.length === 0) {
                        console.log('Validation failed: No image selected');
                        e.preventDefault();
                        e.stopPropagation();
                        alert('Please upload at least one image (Image 1 is required).');
                        image1Input.focus();
                        return false;
                    }
                    
                    console.log('Validation passed - submitting form');
                    
                    // Show loading message
                    const messageContainer = document.getElementById('messageContainer');
                    if (messageContainer) {
                        messageContainer.innerHTML = `
                            <div class="alert alert-info" role="alert">
                                <div class="spinner-border spinner-border-sm me-2" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                Uploading gallery... Please wait.
                            </div>
                        `;
                    }
                    
                    // Allow form to submit
                    console.log('Form will now submit to:', galleryForm.action);
                    return true;
                });
            } else {
                console.error('Gallery form not found!');
            }
        });
    </script>
</body>
</html>
