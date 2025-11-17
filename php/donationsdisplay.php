<?php
/**
 * Donations Display and Management
 * View and manage all donations received
 */

session_start();
require_once 'connection.php';

// Check if user is logged in (you can modify this based on your auth system)
// Uncomment the following lines if you want to restrict access
/*
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Login.html');
    exit;
}
*/

// Function to get all donations with optional filters
function get_donations($conn, $limit = 100, $offset = 0, $status = null, $payment_method = null) {
    $sql = "SELECT * FROM donations WHERE 1=1";
    $params = array();
    $types = "";
    
    if ($status) {
        $sql .= " AND status = ?";
        $params[] = $status;
        $types .= "s";
    }
    
    if ($payment_method) {
        $sql .= " AND payment_method = ?";
        $params[] = $payment_method;
        $types .= "s";
    }
    
    $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";
    
    $stmt = $conn->prepare($sql);
    
    if ($types) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $donations = array();
    
    while ($row = $result->fetch_assoc()) {
        $donations[] = $row;
    }
    
    $stmt->close();
    return $donations;
}

// Function to get donation statistics
function get_donation_stats($conn) {
    $stats = array(
        'total_donations' => 0,
        'total_amount' => 0,
        'completed_donations' => 0,
        'pending_donations' => 0,
        'donations_by_method' => array()
    );
    
    // Get total donations count
    $result = $conn->query("SELECT COUNT(*) as count FROM donations");
    if ($row = $result->fetch_assoc()) {
        $stats['total_donations'] = $row['count'];
    }
    
    // Get total amount
    $result = $conn->query("SELECT SUM(amount) as total FROM donations WHERE status = 'completed'");
    if ($row = $result->fetch_assoc()) {
        $stats['total_amount'] = $row['total'] ?? 0;
    }
    
    // Get completed donations
    $result = $conn->query("SELECT COUNT(*) as count FROM donations WHERE status = 'completed'");
    if ($row = $result->fetch_assoc()) {
        $stats['completed_donations'] = $row['count'];
    }
    
    // Get pending donations
    $result = $conn->query("SELECT COUNT(*) as count FROM donations WHERE status = 'pending'");
    if ($row = $result->fetch_assoc()) {
        $stats['pending_donations'] = $row['count'];
    }
    
    // Get donations by payment method
    $result = $conn->query("SELECT payment_method, COUNT(*) as count, SUM(amount) as total 
                            FROM donations 
                            GROUP BY payment_method");
    while ($row = $result->fetch_assoc()) {
        $stats['donations_by_method'][$row['payment_method']] = array(
            'count' => $row['count'],
            'total' => $row['total']
        );
    }
    
    return $stats;
}

// Handle AJAX requests
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    switch ($_GET['action']) {
        case 'get_donations':
            $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 100;
            $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
            $status = isset($_GET['status']) ? $_GET['status'] : null;
            $payment_method = isset($_GET['payment_method']) ? $_GET['payment_method'] : null;
            
            $donations = get_donations($conn, $limit, $offset, $status, $payment_method);
            echo json_encode(array('success' => true, 'donations' => $donations));
            break;
            
        case 'get_stats':
            $stats = get_donation_stats($conn);
            echo json_encode(array('success' => true, 'stats' => $stats));
            break;
            
        case 'get_donation':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $stmt = $conn->prepare("SELECT * FROM donations WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($row = $result->fetch_assoc()) {
                    echo json_encode(array('success' => true, 'donation' => $row));
                } else {
                    echo json_encode(array('success' => false, 'message' => 'Donation not found'));
                }
                
                $stmt->close();
            } else {
                echo json_encode(array('success' => false, 'message' => 'Invalid request'));
            }
            break;
            
        default:
            echo json_encode(array('success' => false, 'message' => 'Invalid action'));
    }
    
    $conn->close();
    exit;
}

// Get donations for display
$donations = get_donations($conn, 50, 0);
$stats = get_donation_stats($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donations Management - Cooficongo</title>
    
    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Open Sans', sans-serif;
        }
        
        .container {
            max-width: 1400px;
            padding: 30px 15px;
        }
        
        .page-header {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-card h3 {
            font-size: 2rem;
            font-weight: 700;
            color: #2ea359;
            margin-bottom: 5px;
        }
        
        .stats-card p {
            color: #7f8c8d;
            margin: 0;
        }
        
        .donations-table {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .table {
            margin: 0;
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .badge-success {
            background: #27ae60;
            color: white;
        }
        
        .badge-warning {
            background: #f39c12;
            color: white;
        }
        
        .badge-danger {
            background: #e74c3c;
            color: white;
        }
        
        .filter-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1><i class="bi bi-cash-coin"></i> Donations Management</h1>
            <p>View and manage all donations received</p>
        </div>
        
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card">
                    <h3><?php echo $stats['total_donations']; ?></h3>
                    <p><i class="bi bi-heart-fill text-danger"></i> Total Donations</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3>$<?php echo number_format($stats['total_amount'], 2); ?></h3>
                    <p><i class="bi bi-currency-dollar text-success"></i> Total Amount</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3><?php echo $stats['completed_donations']; ?></h3>
                    <p><i class="bi bi-check-circle-fill text-success"></i> Completed</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3><?php echo $stats['pending_donations']; ?></h3>
                    <p><i class="bi bi-clock-fill text-warning"></i> Pending</p>
                </div>
            </div>
        </div>
        
        <!-- Donations by Payment Method -->
        <?php if (!empty($stats['donations_by_method'])): ?>
        <div class="filter-section">
            <h4>Donations by Payment Method</h4>
            <div class="row">
                <?php foreach ($stats['donations_by_method'] as $method => $data): ?>
                <div class="col-md-3">
                    <strong><?php echo htmlspecialchars($method); ?>:</strong><br>
                    <?php echo $data['count']; ?> donations - $<?php echo number_format($data['total'], 2); ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Donations Table -->
        <div class="donations-table">
            <h3 class="mb-4">Recent Donations</h3>
            
            <?php if (empty($donations)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No donations received yet.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Donor Name</th>
                                <th>Email</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($donations as $donation): ?>
                            <tr>
                                <td><code><?php echo htmlspecialchars($donation['transaction_id']); ?></code></td>
                                <td><?php echo htmlspecialchars($donation['donor_name']); ?></td>
                                <td><?php echo htmlspecialchars($donation['email']); ?></td>
                                <td><strong>$<?php echo number_format($donation['amount'], 2); ?></strong></td>
                                <td>
                                    <?php 
                                    $method = htmlspecialchars($donation['payment_method']);
                                    if ($donation['card_type']) {
                                        echo $method . ' (' . htmlspecialchars($donation['card_type']) . ')';
                                        if ($donation['masked_card_number']) {
                                            echo '<br><small>' . htmlspecialchars($donation['masked_card_number']) . '</small>';
                                        }
                                    } else {
                                        echo $method;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    $status = $donation['status'];
                                    $badge_class = 'badge-warning';
                                    if ($status === 'completed') $badge_class = 'badge-success';
                                    elseif ($status === 'failed') $badge_class = 'badge-danger';
                                    ?>
                                    <span class="badge <?php echo $badge_class; ?>">
                                        <?php echo ucfirst(htmlspecialchars($status)); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y h:i A', strtotime($donation['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
