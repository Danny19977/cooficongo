<?php
// Protect this page - redirect to login if not authenticated
require_once __DIR__ . '/php/auth_check.php';
require_once 'php/blogdisplay.php';

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
    <title>Blog Post - CooFICongo</title>
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
                            <a class="nav-link sidebar-link active" id="blog-tab" href="blogpost.php">
                                <i class="bi bi-journal-text me-2"></i> Blog Post
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link" id="events-tab" href="event.php">
                                <i class="bi bi-calendar-event me-2"></i> Events
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link" id="gallery-tab" href="gallerypost.php">
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
                        <i class="bi bi-speedometer2 fs-3 text-success"></i>
                        <span class="fs-4 fw-bold" style="font-family: var(--heading-font); color: var(--heading-color); letter-spacing: 1px;">Blog Post</span>
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
                        <div class="col-lg-7">
                            <div class="card dashboard-card-glass hover-lift shadow-lg border-0">
                                <div class="card-body">
                                    <h2 class="mb-3 fw-bold" style="font-family: var(--heading-font); color: var(--accent-color); letter-spacing: 1px;">Add Blog Post</h2>
                                    
                                    <!-- Success/Error Messages -->
                                    <div id="messageContainer"></div>
                                    
                                    <form action="php/addpost.php" method="post" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="title" class="form-label fw-semibold">Title</label>
                                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter blog title" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="category" class="form-label fw-semibold">Category</label>
                                            <input type="text" class="form-control" id="category" name="category" placeholder="Enter category" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="image" class="form-label fw-semibold">Main Image *</label>
                                            <input class="form-control" type="file" id="image" name="image" accept="image/*" required>
                                            <div class="form-text">Required - Main blog post image</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="image_1" class="form-label fw-semibold">Additional Image 1</label>
                                            <input class="form-control" type="file" id="image_1" name="image_1" accept="image/*">
                                            <div class="form-text">Optional - Additional supporting image</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="image_2" class="form-label fw-semibold">Additional Image 2</label>
                                            <input class="form-control" type="file" id="image_2" name="image_2" accept="image/*">
                                            <div class="form-text">Optional - Additional supporting image</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="image_3" class="form-label fw-semibold">Additional Image 3</label>
                                            <input class="form-control" type="file" id="image_3" name="image_3" accept="image/*">
                                            <div class="form-text">Optional - Additional supporting image</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="blog" class="form-label fw-semibold">Blog Content</label>
                                            <textarea class="form-control" id="blog" name="blog" rows="6" placeholder="Write your blog post here..." required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-lg mt-2 shadow-sm"><i class="bi bi-upload me-2"></i>Submit Post</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <!-- Blog Stats Card -->
                            <div class="card dashboard-card-glass hover-glow shadow border-0 animate-on-scroll fade-in-right mb-4">
                                <div class="card-body text-center">
                                    <i class="bi bi-bar-chart-line fs-1 text-success mb-3"></i>
                                    <h6 class="fw-bold">Blog Stats</h6>
                                    <div class="d-flex justify-content-center gap-4 mt-3">
                                        <div>
                                            <span class="fs-4 fw-bold text-success" id="totalPostsCount">
                                                <?php echo $stats['total_posts']; ?>
                                            </span><br>
                                            <span class="text-muted small">Posts</span>
                                        </div>
                                        <div>
                                            <span class="fs-4 fw-bold text-success" id="draftsCount">
                                                <?php echo $stats['drafts']; ?>
                                            </span><br>
                                            <span class="text-muted small">Drafts</span>
                                        </div>
                                        <div>
                                            <span class="fs-4 fw-bold text-success" id="viewsCount">
                                                <?php echo number_format($stats['total_views']); ?>
                                            </span><br>
                                            <span class="text-muted small">Views</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Blog Post Preview Card -->
                            <div class="card dashboard-card-glass hover-glow shadow border-0">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3 text-center">
                                        <i class="bi bi-eye text-success me-2"></i>Blog Post Preview
                                    </h6>
                                    
                                    <!-- Blog Post Container -->
                                    <div id="blogPostContainer">
                                        <?php if (!empty($blogPosts)): ?>
                                            <?php foreach ($blogPosts as $index => $post): ?>
                                                <!-- Blog Post <?php echo $index + 1; ?> -->
                                                <div class="blog-post-item <?php echo $index > 0 ? 'd-none' : ''; ?>" data-post="<?php echo $index + 1; ?>">
                                                    <div class="mb-2">
                                                        <img src="<?php echo htmlspecialchars($post['image']); ?>" 
                                                             class="img-fluid rounded mb-3" 
                                                             alt="<?php echo htmlspecialchars($post['title']); ?>" 
                                                             style="max-height: 200px; width: 100%; object-fit: cover;"
                                                             onerror="this.src='assets/img/blog/blog-1.jpg'">
                                                    </div>
                                                    <h5 class="fw-bold text-success"><?php echo htmlspecialchars($post['title']); ?></h5>
                                                    <p class="text-muted small mb-2">
                                                        <i class="bi bi-folder me-1"></i>Category: <?php echo htmlspecialchars($post['category']); ?>
                                                    </p>
                                                    <p class="card-text" style="font-size: 0.9rem; line-height: 1.6;">
                                                        <?php 
                                                            $body = htmlspecialchars($post['body']);
                                                            echo strlen($body) > 150 ? substr($body, 0, 150) . '...' : $body;
                                                        ?>
                                                    </p>
                                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                                        <div class="text-muted small">
                                                            <i class="bi bi-calendar3 me-1"></i>Posted: 
                                                            <?php 
                                                                $date = new DateTime($post['created_at']);
                                                                echo $date->format('M d, Y');
                                                            ?>
                                                        </div>
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="editPost('<?php echo htmlspecialchars($post['uuid']); ?>')">
                                                                <i class="bi bi-pencil-square"></i> Edit
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deletePost('<?php echo htmlspecialchars($post['uuid']); ?>', '<?php echo htmlspecialchars(addslashes($post['title'])); ?>')">
                                                                <i class="bi bi-trash"></i> Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <!-- No Posts Message -->
                                            <div class="text-center py-4">
                                                <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                                                <p class="text-muted">No blog posts yet. Create your first post!</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Pagination Controls -->
                                    <?php if (!empty($blogPosts)): ?>
                                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                        <button class="btn btn-outline-success btn-sm" id="prevPost" onclick="navigatePosts(-1)">
                                            <i class="bi bi-arrow-left"></i> Previous
                                        </button>
                                        <span class="text-muted small fw-semibold">
                                            <span id="currentPost">1</span> / <span id="totalPosts"><?php echo count($blogPosts); ?></span>
                                        </span>
                                        <button class="btn btn-outline-success btn-sm" id="nextPost" onclick="navigatePosts(1)">
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
            
            // Fetch and update blog stats dynamically
            fetchBlogStats();
            
            if (urlParams.has('success')) {
                const success = urlParams.get('success');
                let message = '';
                
                if (success === 'post_added') {
                    message = 'Blog post added successfully!';
                } else if (success === 'post_deleted') {
                    message = 'Blog post deleted successfully!';
                } else if (success === 'post_updated') {
                    message = 'Blog post updated successfully!';
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
                        message = 'Please log in to add a blog post.';
                        break;
                    case 'empty_fields':
                        message = 'Please fill in all required fields.';
                        break;
                    case 'invalid_image_type':
                        message = 'Invalid image type. Please upload JPEG, PNG, GIF, or WebP.';
                        break;
                    case 'image_too_large':
                        message = 'Image is too large. Maximum size is 5MB.';
                        break;
                    case 'upload_failed':
                        message = 'Failed to upload image. Please try again.';
                        break;
                    case 'no_image':
                        message = 'Please select an image to upload.';
                        break;
                    case 'database_error':
                        message = 'Database error occurred. Please try again.';
                        break;
                    case 'insert_failed':
                        message = 'Failed to save blog post. Please try again.';
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

        // Blog Post Pagination
        let currentPostIndex = 1;
        const totalPosts = document.querySelectorAll('.blog-post-item').length;
        
        // Update total posts display if element exists
        const totalPostsElement = document.getElementById('totalPosts');
        if (totalPostsElement) {
            totalPostsElement.textContent = totalPosts;
        }

        function navigatePosts(direction) {
            // Only navigate if there are posts
            if (totalPosts === 0) return;
            
            // Hide current post
            const currentElement = document.querySelector(`.blog-post-item[data-post="${currentPostIndex}"]`);
            if (currentElement) {
                currentElement.classList.add('d-none');
            }
            
            // Update index
            currentPostIndex += direction;
            
            // Loop around if at boundaries
            if (currentPostIndex > totalPosts) {
                currentPostIndex = 1;
            } else if (currentPostIndex < 1) {
                currentPostIndex = totalPosts;
            }
            
            // Show new post
            const newElement = document.querySelector(`.blog-post-item[data-post="${currentPostIndex}"]`);
            if (newElement) {
                newElement.classList.remove('d-none');
            }
            
            // Update counter
            const currentPostElement = document.getElementById('currentPost');
            if (currentPostElement) {
                currentPostElement.textContent = currentPostIndex;
            }
            
            // Update button states
            updateButtonStates();
        }

        function updateButtonStates() {
            const prevBtn = document.getElementById('prevPost');
            const nextBtn = document.getElementById('nextPost');
            
            // Optional: Disable buttons at boundaries (remove this if you want infinite loop)
            // prevBtn.disabled = currentPostIndex === 1;
            // nextBtn.disabled = currentPostIndex === totalPosts;
        }

        // Initialize
        if (totalPosts > 0) {
            updateButtonStates();
        }

        // Fetch Blog Stats Function
        function fetchBlogStats() {
            fetch('php/getstats.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.stats) {
                        // Update stats with animation
                        animateValue('totalPostsCount', 0, data.stats.total_posts, 1000);
                        animateValue('draftsCount', 0, data.stats.drafts, 1000);
                        animateValue('viewsCount', 0, data.stats.total_views, 1500, true);
                    }
                })
                .catch(error => {
                    console.error('Error fetching blog stats:', error);
                });
        }

        // Animate number counting
        function animateValue(id, start, end, duration, formatNumber = false) {
            const element = document.getElementById(id);
            if (!element) return;
            
            const range = end - start;
            const increment = range / (duration / 16); // 60fps
            let current = start;
            
            const timer = setInterval(() => {
                current += increment;
                if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                    current = end;
                    clearInterval(timer);
                }
                
                const displayValue = Math.floor(current);
                element.textContent = formatNumber ? formatNumberWithCommas(displayValue) : displayValue;
            }, 16);
        }

        // Format number with commas (e.g., 1000 -> 1,000)
        function formatNumberWithCommas(num) {
            if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'k';
            }
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Edit Post Function
        function editPost(uuid) {
            // Redirect to edit page or open edit modal
            window.location.href = `php/editpost.php?uuid=${uuid}`;
        }

        // Delete Post Function
        function deletePost(uuid, title) {
            if (confirm(`Are you sure you want to delete the post "${title}"?\n\nThis action cannot be undone.`)) {
                // Show loading indicator
                const messageContainer = document.getElementById('messageContainer');
                messageContainer.innerHTML = `
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-hourglass-split me-2"></i>Deleting post...
                    </div>
                `;
                
                // Send delete request
                fetch(`php/deletepost.php?uuid=${uuid}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload page to show updated list
                        window.location.href = 'blogpost.php?success=post_deleted';
                    } else {
                        messageContainer.innerHTML = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>${data.message || 'Failed to delete post.'}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    messageContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>An error occurred while deleting the post.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                });
            }
        }
    </script>
</body>
</html>
