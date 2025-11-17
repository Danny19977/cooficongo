<?php
// Protect this page - redirect to login if not authenticated
require_once __DIR__ . '/php/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CooFICongo</title>
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
                        <h5 class="mt-3 mb-0"
                            style="font-family: var(--heading-font); letter-spacing: 1px;">
                            CooFICongo</h5>
                        <span class="badge bg-success bg-gradient mt-2 px-3 py-1 shadow-sm">Dashboard</span>
                    </div>
                    <ul class="nav flex-column w-100 mt-4">
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link active" id="blog-tab" href="blogpost.php">
                                <i class="bi bi-journal-text me-2"></i> Blog Post
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link" id="events-tab" href="eventpost.php">
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
                            <li class="nav-item mb-2">
                                <a class="nav-link sidebar-link" href="php/logout.php">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
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
                        <i class="bi bi-speedometer2 fs-3 text-success"></i>
                        <span class="fs-4 fw-bold"
                            style="font-family: var(--heading-font); color: var(--heading-color); letter-spacing: 1px;">Dashboard</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></strong></span>
                        <span class="badge bg-success bg-gradient px-3 py-2 shadow-sm"><?php echo htmlspecialchars($_SESSION['role'] ?? 'User'); ?></span>
                        <img src="assets/img/team/team-1.jpg" alt="User"
                            class="rounded-circle border border-2 border-success"
                            style="width: 40px; height: 40px; object-fit: cover;">
                    </div>
                </div>
                <div id="page-blog" class="dashboard-page animate-on-scroll fade-in-up">
                    <div class="row g-4">
                        <div class="col-lg-7">
                            <div class="card dashboard-card-glass hover-lift shadow-lg border-0">
                                <div class="card-body">
                                    <h2 class="mb-3 fw-bold"
                                        style="font-family: var(--heading-font); color: var(--accent-color); letter-spacing: 1px;">
                                        Blog Post</h2>
                                    <h5 class="card-title mb-2">Welcome to the Blog Dashboard <i
                                            class="bi bi-stars text-warning"></i></h5>
                                    <p class="card-text">Here you can manage and view your blog posts. <span
                                            class="badge bg-success bg-gradient">New</span></p>
                                    <button class="btn btn-success btn-lg mt-3 shadow-sm"><i
                                            class="bi bi-plus-circle me-2"></i>New Post</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div
                                class="card dashboard-card-glass hover-glow shadow border-0 animate-on-scroll fade-in-right">
                                <div class="card-body text-center">
                                    <i class="bi bi-bar-chart-line fs-1 text-success mb-3"></i>
                                    <h6 class="fw-bold">Blog Stats</h6>
                                    <div class="d-flex justify-content-center gap-4 mt-3">
                                        <div>
                                            <span class="fs-4 fw-bold text-success">12</span><br><span
                                                class="text-muted small">Posts</span>
                                        </div>
                                        <div>
                                            <span class="fs-4 fw-bold text-success">3</span><br><span
                                                class="text-muted small">Drafts</span>
                                        </div>
                                        <div>
                                            <span class="fs-4 fw-bold text-success">1.2k</span><br><span
                                                class="text-muted small">Views</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="page-events" class="dashboard-page d-none animate-on-scroll fade-in-up">
                    <div class="row g-4">
                        <div class="col-lg-7">
                            <div class="card dashboard-card-glass hover-lift shadow-lg border-0">
                                <div class="card-body">
                                    <h2 class="mb-3 fw-bold"
                                        style="font-family: var(--heading-font); color: var(--accent-color); letter-spacing: 1px;">
                                        Events</h2>
                                    <h5 class="card-title mb-2">Events Management <i
                                            class="bi bi-calendar2-week text-info"></i></h5>
                                    <p class="card-text">Here you can manage and view your events. <span
                                            class="badge bg-info bg-gradient">Upcoming</span></p>
                                    <button class="btn btn-info btn-lg mt-3 shadow-sm"><i
                                            class="bi bi-plus-circle me-2"></i>New Event</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div
                                class="card dashboard-card-glass hover-glow shadow border-0 animate-on-scroll fade-in-left">
                                <div class="card-body text-center">
                                    <i class="bi bi-graph-up-arrow fs-1 text-info mb-3"></i>
                                    <h6 class="fw-bold">Event Stats</h6>
                                    <div class="d-flex justify-content-center gap-4 mt-3">
                                        <div>
                                            <span class="fs-4 fw-bold text-info">5</span><br><span
                                                class="text-muted small">Events</span>
                                        </div>
                                        <div>
                                            <span class="fs-4 fw-bold text-info">2</span><br><span
                                                class="text-muted small">Upcoming</span>
                                        </div>
                                        <div>
                                            <span class="fs-4 fw-bold text-info">350</span><br><span
                                                class="text-muted small">Attendees</span>
                                        </div>
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
        function showPage(page) {
            document.getElementById('page-blog').classList.add('d-none');
            document.getElementById('page-events').classList.add('d-none');
            document.getElementById('blog-tab').classList.remove('active');
            document.getElementById('events-tab').classList.remove('active');
            if (page === 'blog') {
                document.getElementById('page-blog').classList.remove('d-none');
                document.getElementById('blog-tab').classList.add('active');
            } else {
                document.getElementById('page-events').classList.remove('d-none');
                document.getElementById('events-tab').classList.add('active');
            }
            // Animate on tab switch
            setTimeout(() => {
                document.querySelectorAll('.dashboard-page').forEach(el => {
                    el.classList.remove('animated');
                });
                const active = page === 'blog' ? document.getElementById('page-blog') : document.getElementById('page-events');
                active.classList.add('animated');
            }, 50);
        }
        // Animate on load
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                document.querySelectorAll('.animate-on-scroll').forEach(el => {
                    el.classList.add('animated');
                });
            }, 200);
        });
    </script>
</body>

</html>
