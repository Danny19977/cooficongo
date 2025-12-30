<?php
session_start();

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
    <title>Contact Messages - CooFICongo</title>
    <!-- Favicons -->
    <link href="assets/img/logo2_icon.png" rel="icon">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">
</head>

<body style="background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%); min-height: 100vh;">

    <body style="background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%); min-height: 100vh;">
        <div class="container-fluid dashboard-bg p-0">
            <div class="row min-vh-100 g-0">
                <!-- Sidebar -->
                <nav class="col-md-2 d-none d-md-block sidebar-glass sidebar py-4 px-0 position-relative">
                    <div class="sidebar-sticky d-flex flex-column align-items-center h-100">
                        <div class="text-center mb-4 mt-2 animate-on-scroll fade-in-down">
                            <img src="assets/img/logo.png" alt="Logo">
                            <h5 class="mt-3 mb-0"
                                style="font-family: var(--heading-font); letter-spacing: 1px; color: white;">CooFICongo
                            </h5>
                            <span class="badge bg-success bg-gradient mt-2 px-3 py-1 shadow-sm">Dashboard</span>
                        </div>
                        <ul class="nav flex-column w-100 mt-4">
                            <li class="nav-item mb-2">
                                <a class="nav-link sidebar-link" href="blogpost.php">
                                    <i class="bi bi-journal-text me-2"></i> Blog Post
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link sidebar-link" href="eventpost.php">
                                    <i class="bi bi-calendar-event me-2"></i> Events
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link sidebar-link" href="gallerypost.php">
                                    <i class="bi bi-images me-2"></i> Gallery
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link sidebar-link" href="newsletters.php">
                                    <i class="bi bi-envelope-at me-2"></i> Newsletters
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link sidebar-link active" href="contactview.php">
                                    <i class="bi bi-envelope-check me-2"></i> Contact Messages
                                </a>
                            </li>
                        </ul>
                        <div class="mt-auto w-100">
                            <ul class="nav flex-column w-100">
                                <li class="nav-item mb-2">
                                    <a class="nav-link sidebar-link" href="profile.php">
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
                            <i class="bi bi-envelope-check fs-3 text-success"></i>
                            <span class="fs-4 fw-bold"
                                style="font-family: var(--heading-font); color: var(--heading-color); letter-spacing: 1px;">Contact
                                Messages</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-success bg-gradient px-3 py-2 shadow-sm">
                                <?php echo htmlspecialchars(ucfirst($userRole)); ?>
                            </span>
                            <img src="<?php echo htmlspecialchars($userProfilePic); ?>" alt="User"
                                class="rounded-circle border border-2 border-success"
                                style="width: 40px; height: 40px; object-fit: cover;">
                        </div>
                    </div>

                    <div class="dashboard-page animate-on-scroll fade-in-up">
                        <!-- Stats Cards -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <div class="card dashboard-card-glass border-0">
                                    <div class="card-body text-center">
                                        <i class="bi bi-envelope text-success" style="font-size: 3rem;"></i>
                                        <h3 class="mt-3 mb-0 fw-bold text-success" id="totalMessages"
                                            style="font-size: 2.5rem;">0</h3>
                                        <p class="text-muted mb-0">Total Messages</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card dashboard-card-glass border-0">
                                    <div class="card-body text-center">
                                        <i class="bi bi-calendar-day text-success" style="font-size: 3rem;"></i>
                                        <h3 class="mt-3 mb-0 fw-bold text-success" id="todayMessages"
                                            style="font-size: 2.5rem;">0</h3>
                                        <p class="text-muted mb-0">Today</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card dashboard-card-glass border-0">
                                    <div class="card-body text-center">
                                        <i class="bi bi-calendar-week text-success" style="font-size: 3rem;"></i>
                                        <h3 class="mt-3 mb-0 fw-bold text-success" id="weekMessages"
                                            style="font-size: 2.5rem;">0</h3>
                                        <p class="text-muted mb-0">This Week</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filter & Search -->
                        <div class="card dashboard-card-glass border-0 mb-4">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="text" id="searchInput" class="form-control"
                                            placeholder="Search by name, email, or subject...">
                                    </div>
                                    <div class="col-md-4">
                                        <select id="sortBy" class="form-select">
                                            <option value="newest">Newest First</option>
                                            <option value="oldest">Oldest First</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-success w-100" onclick="loadMessages()">
                                            <i class="bi bi-arrow-clockwise"></i> Refresh
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Messages Container -->
                        <div id="messagesContainer">
                            <!-- Messages will be loaded here -->
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4" id="paginationContainer" style="display: none;">
                            <!-- Pagination will be added here -->
                        </div>
                    </div>
                </main>
            </div>
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
            }

            .dashboard-page {
                animation: fadeInUp 1s;
            }

            .message-card {
                background: rgba(255, 255, 255, 0.92);
                border: 1px solid #e0f2f1;
                border-radius: 15px;
                padding: 20px;
                margin-bottom: 20px;
                transition: all 0.3s ease;
            }

            .message-card:hover {
                box-shadow: 0 8px 24px rgba(46, 163, 89, 0.15);
                border-color: #2ea359;
                transform: translateY(-2px);
            }

            .message-header {
                display: flex;
                justify-content: space-between;
                align-items: start;
                margin-bottom: 15px;
                padding-bottom: 15px;
                border-bottom: 2px solid #e0f2f1;
            }

            .message-info h5 {
                margin: 0 0 8px 0;
                color: #116530;
                font-weight: 700;
            }

            .message-info p {
                margin: 3px 0;
                color: #666;
                font-size: 0.9rem;
            }

            .message-date {
                color: #999;
                font-size: 0.85rem;
            }

            .message-subject {
                font-weight: 600;
                color: #2ea359;
                margin-bottom: 12px;
                font-size: 1.05rem;
            }

            .message-content {
                color: #555;
                line-height: 1.7;
                padding: 15px;
                background: #f8f9fa;
                border-radius: 10px;
                margin-bottom: 15px;
                border-left: 4px solid #2ea359;
            }

            .message-actions {
                display: flex;
                gap: 10px;
                justify-content: flex-end;
            }

            .btn-reply {
                background: linear-gradient(135deg, #116530 0%, #2ea359 100%);
                color: white;
                border: none;
                padding: 8px 20px;
                border-radius: 8px;
                font-weight: 500;
                transition: all 0.2s ease;
            }

            .btn-reply:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(46, 163, 89, 0.3);
            }

            .btn-delete {
                background: #dc3545;
                color: white;
                border: none;
                padding: 8px 20px;
                border-radius: 8px;
                font-weight: 500;
                transition: all 0.2s ease;
            }

            .btn-delete:hover {
                background: #c82333;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
            }

            .no-messages {
                text-align: center;
                padding: 60px 20px;
                color: #999;
            }

            .no-messages i {
                font-size: 5rem;
                margin-bottom: 20px;
                opacity: 0.3;
                color: #2ea359;
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
            let allMessages = [];
            let currentPage = 1;
            const messagesPerPage = 10;

            // Load messages on page load
            document.addEventListener('DOMContentLoaded', function () {
                loadMessages();

                // Add search event listener
                document.getElementById('searchInput').addEventListener('input', filterAndDisplayMessages);
                document.getElementById('sortBy').addEventListener('change', filterAndDisplayMessages);
            });

            function loadMessages() {
                fetch('php/contactdisplay.php')
                    .then(response => response.json())
                    .then(data => {
                        allMessages = data.messages || [];
                        updateStats(data.stats);
                        filterAndDisplayMessages();
                    })
                    .catch(error => {
                        console.error('Error loading messages:', error);
                        document.getElementById('messagesContainer').innerHTML = `
                        <div class="card dashboard-card-glass border-0">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 5rem;"></i>
                                <h3 class="mt-3 text-danger">Error Loading Messages</h3>
                                <p class="text-muted">Failed to load contact messages. Please try again.</p>
                            </div>
                        </div>
                    `;
                    });
            }

            function updateStats(stats) {
                document.getElementById('totalMessages').textContent = stats.total || 0;
                document.getElementById('todayMessages').textContent = stats.today || 0;
                document.getElementById('weekMessages').textContent = stats.week || 0;
            }

            function filterAndDisplayMessages() {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                const sortBy = document.getElementById('sortBy').value;

                // Filter messages
                let filtered = allMessages.filter(message => {
                    return message.fullname.toLowerCase().includes(searchTerm) ||
                        message.email.toLowerCase().includes(searchTerm) ||
                        message.subject.toLowerCase().includes(searchTerm);
                });

                // Sort messages
                filtered.sort((a, b) => {
                    if (sortBy === 'newest') {
                        return new Date(b.created_at) - new Date(a.created_at);
                    } else {
                        return new Date(a.created_at) - new Date(b.created_at);
                    }
                });

                // Display paginated messages
                displayMessages(filtered);
            }

            function displayMessages(messages) {
                const container = document.getElementById('messagesContainer');

                if (messages.length === 0) {
                    container.innerHTML = `
                    <div class="card dashboard-card-glass border-0">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-inbox"></i>
                            <h3 class="mt-3 text-muted">No Messages Found</h3>
                            <p class="text-muted">There are no contact messages matching your search.</p>
                        </div>
                    </div>
                `;
                    document.getElementById('paginationContainer').style.display = 'none';
                    return;
                }

                // Paginate
                const startIndex = (currentPage - 1) * messagesPerPage;
                const endIndex = startIndex + messagesPerPage;
                const paginatedMessages = messages.slice(startIndex, endIndex);

                // Display messages
                let html = '';
                paginatedMessages.forEach(message => {
                    const date = new Date(message.created_at);
                    const formattedDate = date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    html += `
                    <div class="message-card">
                        <div class="message-header">
                            <div class="message-info">
                                <h5><i class="bi bi-person-circle me-2"></i>${escapeHtml(message.fullname)}</h5>
                                <p><i class="bi bi-envelope me-1"></i> ${escapeHtml(message.email)}</p>
                            </div>
                            <div class="message-meta">
                                <small class="message-date">
                                    <i class="bi bi-clock me-1"></i>${formattedDate}
                                </small>
                            </div>
                        </div>
                        <div class="message-subject">
                            <i class="bi bi-tag me-2"></i>${escapeHtml(message.subject)}
                        </div>
                        <div class="message-content">
                            ${escapeHtml(message.message)}
                        </div>
                        <div class="message-actions">
                            <button class="btn-reply" onclick="replyToMessage('${escapeHtml(message.email)}', '${escapeHtml(message.subject)}')">
                                <i class="bi bi-reply me-1"></i> Reply
                            </button>
                            <button class="btn-delete" onclick="deleteMessage('${message.uuid}')">
                                <i class="bi bi-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>
                `;
                });

                container.innerHTML = html;

                // Update pagination
                updatePagination(messages.length);
            }

            function updatePagination(totalMessages) {
                const totalPages = Math.ceil(totalMessages / messagesPerPage);
                const paginationDiv = document.getElementById('paginationContainer');

                if (totalPages <= 1) {
                    paginationDiv.style.display = 'none';
                    return;
                }

                paginationDiv.style.display = 'flex';
                paginationDiv.className = 'd-flex justify-content-center gap-2 mt-4';

                let html = '';

                // Previous button
                html += `<button class="btn btn-outline-success ${currentPage === 1 ? 'disabled' : ''}" 
                ${currentPage === 1 ? 'disabled' : ''} onclick="changePage(${currentPage - 1})">
                <i class="bi bi-chevron-left"></i>
            </button>`;

                // Page numbers
                for (let i = 1; i <= totalPages; i++) {
                    if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                        html += `<button class="btn ${i === currentPage ? 'btn-success' : 'btn-outline-success'}" 
                        onclick="changePage(${i})">${i}</button>`;
                    } else if (i === currentPage - 3 || i === currentPage + 3) {
                        html += `<button class="btn btn-outline-success" disabled>...</button>`;
                    }
                }

                // Next button
                html += `<button class="btn btn-outline-success ${currentPage === totalPages ? 'disabled' : ''}" 
                ${currentPage === totalPages ? 'disabled' : ''} onclick="changePage(${currentPage + 1})">
                <i class="bi bi-chevron-right"></i>
            </button>`;

                paginationDiv.innerHTML = html;
            }

            function changePage(page) {
                currentPage = page;
                filterAndDisplayMessages();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            function replyToMessage(email, subject) {
                const replySubject = subject.startsWith('Re:') ? subject : `Re: ${subject}`;
                window.location.href = `mailto:${email}?subject=${encodeURIComponent(replySubject)}`;
            }

            function deleteMessage(uuid) {
                if (!confirm('Are you sure you want to delete this message? This action cannot be undone.')) {
                    return;
                }

                fetch('php/deletecontact.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ uuid: uuid })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadMessages();
                            // Show success message
                            const container = document.getElementById('messagesContainer');
                            const alert = document.createElement('div');
                            alert.className = 'alert alert-success alert-dismissible fade show';
                            alert.innerHTML = `
                            <i class="bi bi-check-circle me-2"></i>Message deleted successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                            container.insertBefore(alert, container.firstChild);
                            setTimeout(() => alert.remove(), 3000);
                        } else {
                            alert('Error: ' + (data.message || 'Failed to delete message'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the message.');
                    });
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
        </script>
    </body>

</html>