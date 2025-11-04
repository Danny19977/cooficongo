<?php
session_start();
require_once 'php/newsletter.php';

// Get all newsletter emails
$emails = getAllNewsletterEmails($conn);
$stats = getNewsletterStats($conn);

// Handle delete request
if (isset($_POST['delete_id'])) {
    $result = deleteNewsletterEmail($conn, $_POST['delete_id']);
    if ($result['success']) {
        header("Location: newsletters.php?success=deleted");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter Emails - CooFICongo</title>
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
                        <h5 class="mt-3 mb-0" style="font-family: var(--heading-font); letter-spacing: 1px;">
                            CooFICongo</h5>
                        <span class="badge bg-success bg-gradient mt-2 px-3 py-1 shadow-sm">Dashboard</span>
                    </div>
                    <ul class="nav flex-column w-100 mt-4">
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link" href="blogpost.php">
                                <i class="bi bi-journal-text me-2"></i> Blog Post
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link" href="event.php">
                                <i class="bi bi-calendar-event me-2"></i> Events
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link sidebar-link active" href="newsletters.php">
                                <i class="bi bi-envelope-at me-2"></i> Newsletters
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
                <div class="d-flex align-items-center justify-content-between mb-4 animate-on-scroll fade-in-down dashboard-header-glass">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-envelope-at fs-3 text-success"></i>
                        <span class="fs-4 fw-bold" style="font-family: var(--heading-font); color: var(--heading-color); letter-spacing: 1px;">Newsletter Emails</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-success bg-gradient px-3 py-2 shadow-sm">Admin</span>
                        <img src="assets/img/team/team-1.jpg" alt="User" class="rounded-circle border border-2 border-success" style="width: 40px; height: 40px; object-fit: cover;">
                    </div>
                </div>

                <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>Email deleted successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Statistics Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card dashboard-card-glass border-0 shadow">
                            <div class="card-body text-center">
                                <i class="bi bi-people fs-1 text-success mb-3"></i>
                                <h3 class="fw-bold text-success mb-1"><?php echo $stats['total']; ?></h3>
                                <p class="text-muted mb-0">Total Subscribers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card dashboard-card-glass border-0 shadow">
                            <div class="card-body text-center">
                                <i class="bi bi-check-circle fs-1 text-info mb-3"></i>
                                <h3 class="fw-bold text-info mb-1"><?php echo $stats['active']; ?></h3>
                                <p class="text-muted mb-0">Active Subscribers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card dashboard-card-glass border-0 shadow">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar-check fs-1 text-warning mb-3"></i>
                                <h3 class="fw-bold text-warning mb-1"><?php echo $stats['today']; ?></h3>
                                <p class="text-muted mb-0">New Today</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email List Table -->
                <div class="card dashboard-card-glass border-0 shadow-lg">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 fw-bold" style="color: var(--accent-color);">
                                <i class="bi bi-list-ul me-2"></i>All Subscribers
                            </h4>
                            <button class="btn btn-success" onclick="exportEmails()">
                                <i class="bi bi-download me-2"></i>Export CSV
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Email Address</th>
                                        <th scope="col">Subscribed Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($emails) > 0): ?>
                                        <?php foreach ($emails as $index => $email): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td>
                                                <i class="bi bi-envelope me-2 text-success"></i>
                                                <strong><?php echo htmlspecialchars($email['email']); ?></strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php echo date('M d, Y - h:i A', strtotime($email['subscribed_date'])); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $email['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                                    <?php echo ucfirst($email['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteEmail(<?php echo $email['id']; ?>, '<?php echo htmlspecialchars($email['email']); ?>')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                                <p class="text-muted mb-0">No newsletter subscribers yet</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this email?</p>
                    <p class="text-muted"><strong id="deleteEmailText"></strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" id="deleteForm">
                        <input type="hidden" name="delete_id" id="deleteEmailId">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
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

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            font-weight: 600;
            color: var(--heading-color);
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody tr {
            transition: background-color 0.2s;
        }

        .table tbody tr:hover {
            background-color: rgba(46, 163, 89, 0.05);
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
        function deleteEmail(id, email) {
            document.getElementById('deleteEmailId').value = id;
            document.getElementById('deleteEmailText').textContent = email;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        function exportEmails() {
            // Create CSV content
            let csv = 'Email,Subscribed Date,Status\n';
            
            <?php foreach ($emails as $email): ?>
            csv += '<?php echo htmlspecialchars($email['email']); ?>,<?php echo date('Y-m-d H:i:s', strtotime($email['subscribed_date'])); ?>,<?php echo $email['status']; ?>\n';
            <?php endforeach; ?>
            
            // Create download link
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'newsletter_subscribers_' + new Date().toISOString().split('T')[0] + '.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
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
