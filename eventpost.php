<?php
session_start();
require_once 'php/activitiesdisplay.php';

// Get user role from session, default to 'Guest' if not logged in
$userRole = $_SESSION['role'] ?? 'Guest';

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
    <title>Event Post - CooFICongo</title>
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
                        <h5 class="mt-3 mb-0" style="font-family: var(--heading-font); letter-spacing: 1px;">CooFICongo</h5>
                        <span class="badge bg-success bg-gradient mt-2 px-3 py-1 shadow-sm">Dashboard</span>
                    </div>
                    <ul class="nav flex-column w-100 mt-4">
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link" id="blog-tab" href="blogpost.php">
                                <i class="bi bi-journal-text me-2"></i> Blog Post
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link active" id="events-tab" href="event.php">
                                <i class="bi bi-calendar-event me-2"></i> Events
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
                        <span class="fs-4 fw-bold" style="font-family: var(--heading-font); color: var(--heading-color); letter-spacing: 1px;">Event Post</span>
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
                                    <h2 class="mb-3 fw-bold" style="font-family: var(--heading-font); color: var(--accent-color); letter-spacing: 1px;">Add Event</h2>
                                    <!-- Success/Error Messages -->
                                    <div id="messageContainer"></div>
                                    <form action="php/addevent.php" method="post" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="title" class="form-label fw-semibold">Title</label>
                                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter event title" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="summary" class="form-label fw-semibold">Summary</label>
                                            <input type="text" class="form-control" id="summary" name="summary" placeholder="Enter event summary" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label fw-semibold">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe the event..." required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="activity_date" class="form-label fw-semibold">Date</label>
                                            <input type="date" class="form-control" id="activity_date" name="activity_date" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="location" class="form-label fw-semibold">Location</label>
                                            <input type="text" class="form-control" id="location" name="location" placeholder="Event location" required>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-lg mt-2 shadow-sm"><i class="bi bi-upload me-2"></i>Submit Event</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <!-- Event Stats Card -->
                            <div class="card dashboard-card-glass hover-glow shadow border-0 animate-on-scroll fade-in-right mb-4">
                                <div class="card-body text-center">
                                    <i class="bi bi-bar-chart-line fs-1 text-success mb-3"></i>
                                    <h6 class="fw-bold">Event Stats</h6>
                                    <div class="d-flex justify-content-center gap-4 mt-3">
                                        <div>
                                            <span class="fs-4 fw-bold text-success" id="totalEventsCount">
                                                <?php echo $stats['total_events']; ?>
                                            </span><br>
                                            <span class="text-muted small">Events</span>
                                        </div>
                                        <div>
                                            <span class="fs-4 fw-bold text-success" id="upcomingCount">
                                                <?php echo $stats['upcoming']; ?>
                                            </span><br>
                                            <span class="text-muted small">Upcoming</span>
                                        </div>
                                        <div>
                                            <span class="fs-4 fw-bold text-success" id="pastCount">
                                                <?php echo $stats['past']; ?>
                                            </span><br>
                                            <span class="text-muted small">Past</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Event Preview Card -->
                            <div class="card dashboard-card-glass hover-glow shadow border-0">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3 text-center">
                                        <i class="bi bi-eye text-success me-2"></i>Event Preview
                                    </h6>
                                    <!-- Event Container -->
                                    <div id="eventContainer">
                                        <?php if (!empty($activitiesPosts)): ?>
                                            <?php foreach ($activitiesPosts as $index => $event): ?>
                                                <!-- Event <?php echo $index + 1; ?> -->
                                                <div class="event-item <?php echo $index > 0 ? 'd-none' : ''; ?>" data-event="<?php echo $index + 1; ?>">
                                                    <h5 class="fw-bold text-success"><?php echo htmlspecialchars($event['title']); ?></h5>
                                                    <p class="text-muted small mb-2">
                                                        <i class="bi bi-calendar-event me-1"></i>Date: <?php echo htmlspecialchars($event['activity_date']); ?>
                                                        <br><i class="bi bi-geo-alt me-1"></i>Location: <?php echo htmlspecialchars($event['location']); ?>
                                                    </p>
                                                    <p class="card-text" style="font-size: 0.9rem; line-height: 1.6;">
                                                        <?php 
                                                            $desc = htmlspecialchars($event['description']);
                                                            echo strlen($desc) > 150 ? substr($desc, 0, 150) . '...' : $desc;
                                                        ?>
                                                    </p>
                                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                                        <div class="text-muted small">
                                                            <i class="bi bi-clock me-1"></i>Created: 
                                                            <?php 
                                                                $date = new DateTime($event['created_at']);
                                                                echo $date->format('M d, Y');
                                                            ?>
                                                        </div>
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="editEvent('<?php echo htmlspecialchars($event['uuid']); ?>')">
                                                                <i class="bi bi-pencil-square"></i> Edit
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteEvent('<?php echo htmlspecialchars($event['uuid']); ?>', '<?php echo htmlspecialchars(addslashes($event['title'])); ?>')">
                                                                <i class="bi bi-trash"></i> Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <!-- No Events Message -->
                                            <div class="text-center py-4">
                                                <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                                                <p class="text-muted">No events yet. Create your first event!</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <!-- Pagination Controls -->
                                    <?php if (!empty($activitiesPosts)): ?>
                                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                        <button class="btn btn-outline-success btn-sm" id="prevEvent" onclick="navigateEvents(-1)">
                                            <i class="bi bi-arrow-left"></i> Previous
                                        </button>
                                        <span class="text-muted small fw-semibold">
                                            <span id="currentEvent">1</span> / <span id="totalEvents"><?php echo count($activitiesPosts); ?></span>
                                        </span>
                                        <button class="btn btn-outline-success btn-sm" id="nextEvent" onclick="navigateEvents(1)">
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
            // Fetch and update event stats dynamically
            fetchEventStats();
            if (urlParams.has('success')) {
                const success = urlParams.get('success');
                let message = '';
                if (success === 'event_added') {
                    message = 'Event added successfully!';
                } else if (success === 'event_deleted') {
                    message = 'Event deleted successfully!';
                } else if (success === 'event_updated') {
                    message = 'Event updated successfully!';
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
                        message = 'Please log in to add an event.';
                        break;
                    case 'empty_fields':
                        message = 'Please fill in all required fields.';
                        break;
                    case 'database_error':
                        message = 'Database error occurred. Please try again.';
                        break;
                    case 'insert_failed':
                        message = 'Failed to save event. Please try again.';
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
        // Event Pagination
        let currentEventIndex = 1;
        const totalEvents = document.querySelectorAll('.event-item').length;
        // Update total events display if element exists
        const totalEventsElement = document.getElementById('totalEvents');
        if (totalEventsElement) {
            totalEventsElement.textContent = totalEvents;
        }
        function navigateEvents(direction) {
            // Only navigate if there are events
            if (totalEvents === 0) return;
            // Hide current event
            const currentElement = document.querySelector(`.event-item[data-event="${currentEventIndex}"]`);
            if (currentElement) {
                currentElement.classList.add('d-none');
            }
            // Update index
            currentEventIndex += direction;
            // Loop around if at boundaries
            if (currentEventIndex > totalEvents) {
                currentEventIndex = 1;
            } else if (currentEventIndex < 1) {
                currentEventIndex = totalEvents;
            }
            // Show new event
            const newElement = document.querySelector(`.event-item[data-event="${currentEventIndex}"]`);
            if (newElement) {
                newElement.classList.remove('d-none');
            }
            // Update counter
            const currentEventElement = document.getElementById('currentEvent');
            if (currentEventElement) {
                currentEventElement.textContent = currentEventIndex;
            }
            // Update button states
            updateButtonStates();
        }
        function updateButtonStates() {
            const prevBtn = document.getElementById('prevEvent');
            const nextBtn = document.getElementById('nextEvent');
            // Optional: Disable buttons at boundaries (remove this if you want infinite loop)
            // prevBtn.disabled = currentEventIndex === 1;
            // nextBtn.disabled = currentEventIndex === totalEvents;
        }
        // Initialize
        if (totalEvents > 0) {
            updateButtonStates();
        }
        // Fetch Event Stats Function
        function fetchEventStats() {
            fetch('php/geteventstats.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.stats) {
                        // Update stats with animation
                        animateValue('totalEventsCount', 0, data.stats.total_events, 1000);
                        animateValue('upcomingCount', 0, data.stats.upcoming, 1000);
                        animateValue('pastCount', 0, data.stats.past, 1500, true);
                    }
                })
                .catch(error => {
                    console.error('Error fetching event stats:', error);
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
        // Edit Event Function
        function editEvent(uuid) {
            // Redirect to edit page or open edit modal
            window.location.href = `php/editevent.php?uuid=${uuid}`;
        }
        // Delete Event Function
        function deleteEvent(uuid, title) {
            if (confirm(`Are you sure you want to delete the event "${title}"?\n\nThis action cannot be undone.`)) {
                // Show loading indicator
                const messageContainer = document.getElementById('messageContainer');
                messageContainer.innerHTML = `
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-hourglass-split me-2"></i>Deleting event...
                    </div>
                `;
                // Send delete request
                fetch(`php/deleteevent.php?uuid=${uuid}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload page to show updated list
                        window.location.href = 'eventpost.php?success=event_deleted';
                    } else {
                        messageContainer.innerHTML = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>${data.message || 'Failed to delete event.'}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    messageContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>An error occurred while deleting the event.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                });
            }
        }
    </script>
</body>
</html>
