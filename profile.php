<?php
session_start();
require_once 'php/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_uuid'])) {
    header("Location: Login.html?error=not_logged_in");
    exit();
}

$user_uuid = $_SESSION['user_uuid'];

// Fetch user data from database
$sql = "SELECT uuid, username, email, phone, role, bio, profile_picture, created_at FROM users WHERE uuid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_uuid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: Login.html?error=user_not_found");
    exit();
}

$user = $result->fetch_assoc();
$stmt->close();

// Get user statistics
$stats = [
    'posts' => 0,
    'events' => 0,
    'views' => 0
];

// Count user's blog posts
$posts_sql = "SELECT COUNT(*) as count FROM blogposts WHERE user_uuid = ?";
$posts_stmt = $conn->prepare($posts_sql);
$posts_stmt->bind_param("s", $user_uuid);
$posts_stmt->execute();
$posts_result = $posts_stmt->get_result();
$stats['posts'] = $posts_result->fetch_assoc()['count'];
$posts_stmt->close();

// Calculate views (placeholder - multiply posts by 100)
$stats['views'] = $stats['posts'] * 100;

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - CooFICongo</title>
    <!-- Favicons -->
    <link href="assets/img/logo2_icon.png" rel="icon">
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
                        <h5 class="mt-3 mb-0" style="font-family: var(--heading-font); letter-spacing: 1px;">CooFICongo
                        </h5>
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
                                <a class="nav-link sidebar-link active" id="profile-tab" href="profile.php">
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
                <div
                    class="d-flex align-items-center justify-content-between mb-4 animate-on-scroll fade-in-down dashboard-header-glass">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-person-circle fs-3 text-success"></i>
                        <span class="fs-4 fw-bold"
                            style="font-family: var(--heading-font); color: var(--heading-color); letter-spacing: 1px;">My
                            Profile</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-success bg-gradient px-3 py-2 shadow-sm">
                            <?php echo htmlspecialchars(ucfirst($user['role'])); ?>
                        </span>
                        <img src="<?php echo !empty($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : 'assets/img/team/team-1.jpg'; ?>"
                            alt="User" class="rounded-circle border border-2 border-success"
                            style="width: 40px; height: 40px; object-fit: cover;">
                    </div>
                </div>
                <div class="dashboard-page animate-on-scroll fade-in-up">
                    <div class="row g-4">
                        <!-- Profile Information Card -->
                        <div class="col-lg-7">
                            <div class="card dashboard-card-glass hover-lift shadow-lg border-0 mb-4">
                                <div class="card-body">
                                    <h2 class="mb-3 fw-bold"
                                        style="font-family: var(--heading-font); color: var(--accent-color); letter-spacing: 1px;">
                                        <i class="bi bi-person-badge me-2"></i>Profile Information
                                    </h2>

                                    <!-- Success/Error Messages -->
                                    <div id="messageContainer"></div>

                                    <form action="php/updateprofile.php" method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="username" class="form-label fw-semibold">Username</label>
                                                <input type="text" class="form-control" id="username" name="username"
                                                    placeholder="Enter username"
                                                    value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label fw-semibold">Email</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    placeholder="Enter email"
                                                    value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="phone" class="form-label fw-semibold">Phone Number</label>
                                                <input type="tel" class="form-control" id="phone" name="phone"
                                                    placeholder="Enter phone number"
                                                    value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="role" class="form-label fw-semibold">Role</label>
                                                <input type="text" class="form-control" id="role" name="role"
                                                    value="<?php echo htmlspecialchars(ucfirst($user['role'])); ?>"
                                                    disabled>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="bio" class="form-label fw-semibold">Bio</label>
                                            <textarea class="form-control" id="bio" name="bio" rows="3"
                                                placeholder="Tell us about yourself..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="profile_image" class="form-label fw-semibold">Profile
                                                Picture</label>
                                            <input class="form-control" type="file" id="profile_image"
                                                name="profile_image" accept="image/*">
                                            <small class="text-muted">Upload a new profile picture (optional)</small>
                                        </div>

                                        <button type="submit" class="btn btn-success btn-lg mt-2 shadow-sm">
                                            <i class="bi bi-check-circle me-2"></i>Update Profile
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Change Password Card -->
                            <div class="card dashboard-card-glass hover-lift shadow-lg border-0">
                                <div class="card-body">
                                    <h2 class="mb-3 fw-bold"
                                        style="font-family: var(--heading-font); color: var(--accent-color); letter-spacing: 1px;">
                                        <i class="bi bi-shield-lock me-2"></i>Change Password
                                    </h2>

                                    <form action="php/changepassword.php" method="post">
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label fw-semibold">Current
                                                Password</label>
                                            <input type="password" class="form-control" id="current_password"
                                                name="current_password" placeholder="Enter current password" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="new_password" class="form-label fw-semibold">New
                                                Password</label>
                                            <input type="password" class="form-control" id="new_password"
                                                name="new_password" placeholder="Enter new password" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="confirm_password" class="form-label fw-semibold">Confirm New
                                                Password</label>
                                            <input type="password" class="form-control" id="confirm_password"
                                                name="confirm_password" placeholder="Confirm new password" required>
                                        </div>

                                        <button type="submit" class="btn btn-warning btn-lg mt-2 shadow-sm">
                                            <i class="bi bi-key me-2"></i>Change Password
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Stats & Info Sidebar -->
                        <div class="col-lg-5">
                            <!-- Profile Picture Card -->
                            <div
                                class="card dashboard-card-glass hover-glow shadow border-0 animate-on-scroll fade-in-right mb-4">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <img src="<?php echo !empty($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : 'assets/img/team/team-1.jpg'; ?>"
                                            alt="Profile Picture" class="rounded-circle border border-3 border-success"
                                            style="width: 150px; height: 150px; object-fit: cover;">
                                    </div>
                                    <h4 class="fw-bold text-success mb-1">
                                        <?php echo htmlspecialchars($user['username']); ?></h4>
                                    <p class="text-muted mb-2">
                                        <i
                                            class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($user['email']); ?>
                                    </p>
                                    <span
                                        class="badge bg-success bg-gradient px-3 py-2"><?php echo htmlspecialchars(ucfirst($user['role'])); ?></span>
                                </div>
                            </div>

                            <!-- Account Statistics -->
                            <div class="card dashboard-card-glass hover-glow shadow border-0 mb-4">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3 text-center">
                                        <i class="bi bi-graph-up text-success me-2"></i>Account Statistics
                                    </h6>
                                    <div class="d-flex justify-content-around mt-3">
                                        <div class="text-center">
                                            <span
                                                class="fs-4 fw-bold text-success"><?php echo $stats['posts']; ?></span><br>
                                            <span class="text-muted small">Posts</span>
                                        </div>
                                        <div class="text-center">
                                            <span
                                                class="fs-4 fw-bold text-success"><?php echo $stats['events']; ?></span><br>
                                            <span class="text-muted small">Events</span>
                                        </div>
                                        <div class="text-center">
                                            <span
                                                class="fs-4 fw-bold text-success"><?php echo $stats['views']; ?></span><br>
                                            <span class="text-muted small">Views</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Details -->
                            <div class="card dashboard-card-glass hover-glow shadow border-0">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-info-circle text-success me-2"></i>Account Details
                                    </h6>
                                    <div class="mb-3">
                                        <small class="text-muted">Member Since</small>
                                        <p class="mb-0 fw-semibold">
                                            <i class="bi bi-calendar3 me-1 text-success"></i>
                                            <?php
                                            $date = new DateTime($user['created_at']);
                                            echo $date->format('F d, Y');
                                            ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted">Last Login</small>
                                        <p class="mb-0 fw-semibold">
                                            <i class="bi bi-clock me-1 text-success"></i>
                                            <?php echo date('M d, Y - h:i A'); ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted">Account Status</small>
                                        <p class="mb-0">
                                            <span class="badge bg-success">Active</span>
                                        </p>
                                    </div>
                                    <hr>
                                    <div class="d-grid gap-2">
                                        <a href="login.html" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <style>
        .dashboard-bg {
            background: linear-gradient(120deg, #e8f5e9 0%, #f1f8e9 100%);
            min-height: 100vh;
        }

        .sidebar-glass {
            background: linear-gradient(160deg, #116530 0%, #2ea359 100%);
            box-shadow: 0 8px 32px 0 rgba(17, 101, 48, 0.15);
            border-top-right-radius: 32px;
            border-bottom-right-radius: 32px;
            min-height: 100vh;
            position: relative;
            z-index: 2;
        }

        .sidebar-link {
            color: #fff !important;
            font-weight: 500;
            font-size: 1.1rem;
            border-radius: 8px 0 0 8px;
            padding: 12px 24px;
            margin-left: 8px;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        }

        .sidebar-link.active,
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffe082 !important;
            box-shadow: 0 4px 16px 0 rgba(17, 101, 48, 0.10);
        }

        .dashboard-main {
            background: transparent;
            min-height: 100vh;
            z-index: 1;
        }

        .dashboard-header-glass {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 18px;
            box-shadow: 0 4px 24px 0 rgba(46, 163, 89, 0.08);
            padding: 18px 32px;
            margin-bottom: 32px;
            border: 1px solid #e0f2f1;
        }

        .dashboard-card-glass {
            background: rgba(255, 255, 255, 0.92);
            border-radius: 22px;
            box-shadow: 0 8px 32px 0 rgba(46, 163, 89, 0.10);
            border: 1px solid #e0f2f1;
            transition: box-shadow 0.3s, transform 0.3s;
        }

        .dashboard-card-glass:hover {
            box-shadow: 0 16px 48px 0 rgba(46, 163, 89, 0.18);
            transform: translateY(-6px) scale(1.01);
        }

        .dashboard-page {
            animation: fadeInUp 1s;
        }

        @media (max-width: 991px) {
            .sidebar-glass {
                border-radius: 0 0 32px 32px;
                min-height: auto;
            }

            .dashboard-main {
                padding: 2rem 1rem !important;
            }
        }
    </style>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // Display success/error messages from URL parameters
        window.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const messageContainer = document.getElementById('messageContainer');

            if (urlParams.has('success')) {
                const success = urlParams.get('success');
                let message = '';

                if (success === 'profile_updated') {
                    message = 'Profile updated successfully!';
                } else if (success === 'password_changed') {
                    message = 'Password changed successfully!';
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

                switch (error) {
                    case 'not_logged_in':
                        message = 'Please log in to view your profile.';
                        break;
                    case 'empty_fields':
                        message = 'Please fill in all required fields.';
                        break;
                    case 'password_mismatch':
                        message = 'New passwords do not match.';
                        break;
                    case 'incorrect_password':
                        message = 'Current password is incorrect.';
                        break;
                    case 'password_too_short':
                        message = 'Password must be at least 6 characters long.';
                        break;
                    case 'invalid_email':
                        message = 'Please enter a valid email address.';
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
                    case 'database_error':
                        message = 'Database error occurred. Please try again.';
                        break;
                    case 'update_failed':
                        message = 'Failed to update. Please try again.';
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
    </script>
</body>

</html>