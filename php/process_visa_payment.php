<?php
/**
 * Process Visa Payment
 * Handles credit card payment processing and stores donation records
 */

header('Content-Type: application/json');

// Start session if needed
session_start();

// Include database connection
require_once 'connection.php';

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to validate card number using Luhn algorithm
function validate_card_number($card_number) {
    $card_number = str_replace(' ', '', $card_number);
    
    if (!preg_match('/^\d{13,19}$/', $card_number)) {
        return false;
    }
    
    $sum = 0;
    $is_even = false;
    
    for ($i = strlen($card_number) - 1; $i >= 0; $i--) {
        $digit = intval($card_number[$i]);
        
        if ($is_even) {
            $digit *= 2;
            if ($digit > 9) {
                $digit -= 9;
            }
        }
        
        $sum += $digit;
        $is_even = !$is_even;
    }
    
    return ($sum % 10 === 0);
}

// Function to validate expiry date
function validate_expiry_date($expiry_date) {
    $parts = explode('/', $expiry_date);
    
    if (count($parts) !== 2) {
        return false;
    }
    
    $month = intval($parts[0]);
    $year = intval('20' . $parts[1]);
    
    if ($month < 1 || $month > 12) {
        return false;
    }
    
    $current_year = intval(date('Y'));
    $current_month = intval(date('m'));
    
    if ($year < $current_year || ($year === $current_year && $month < $current_month)) {
        return false;
    }
    
    return true;
}

// Function to get card type
function get_card_type($card_number) {
    $card_number = str_replace(' ', '', $card_number);
    
    // Visa
    if (preg_match('/^4/', $card_number)) {
        return 'Visa';
    }
    // Mastercard
    if (preg_match('/^5[1-5]/', $card_number)) {
        return 'Mastercard';
    }
    // American Express
    if (preg_match('/^3[47]/', $card_number)) {
        return 'American Express';
    }
    // Discover
    if (preg_match('/^6(?:011|5)/', $card_number)) {
        return 'Discover';
    }
    
    return 'Unknown';
}

// Response array
$response = array(
    'success' => false,
    'message' => ''
);

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method.';
    echo json_encode($response);
    exit;
}

// Get and sanitize POST data
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
$cardholder_name = isset($_POST['cardholder_name']) ? sanitize_input($_POST['cardholder_name']) : '';
$card_number = isset($_POST['card_number']) ? sanitize_input($_POST['card_number']) : '';
$expiry_date = isset($_POST['expiry_date']) ? sanitize_input($_POST['expiry_date']) : '';
$cvv = isset($_POST['cvv']) ? sanitize_input($_POST['cvv']) : '';
$email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
$phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';

// Validate required fields
if (empty($amount) || $amount <= 0) {
    $response['message'] = 'Invalid donation amount.';
    echo json_encode($response);
    exit;
}

if (empty($cardholder_name)) {
    $response['message'] = 'Cardholder name is required.';
    echo json_encode($response);
    exit;
}

if (empty($card_number)) {
    $response['message'] = 'Card number is required.';
    echo json_encode($response);
    exit;
}

if (empty($expiry_date)) {
    $response['message'] = 'Expiry date is required.';
    echo json_encode($response);
    exit;
}

if (empty($cvv)) {
    $response['message'] = 'CVV is required.';
    echo json_encode($response);
    exit;
}

if (empty($email)) {
    $response['message'] = 'Email address is required.';
    echo json_encode($response);
    exit;
}

if (empty($phone)) {
    $response['message'] = 'Phone number is required.';
    echo json_encode($response);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = 'Invalid email address format.';
    echo json_encode($response);
    exit;
}

// Validate card number
if (!validate_card_number($card_number)) {
    $response['message'] = 'Invalid card number.';
    echo json_encode($response);
    exit;
}

// Validate expiry date
if (!validate_expiry_date($expiry_date)) {
    $response['message'] = 'Invalid or expired card.';
    echo json_encode($response);
    exit;
}

// Validate CVV
if (!preg_match('/^\d{3,4}$/', $cvv)) {
    $response['message'] = 'Invalid CVV.';
    echo json_encode($response);
    exit;
}

// Get card type
$card_type = get_card_type($card_number);

// Mask card number for storage (only store last 4 digits)
$card_number_clean = str_replace(' ', '', $card_number);
$masked_card_number = '**** **** **** ' . substr($card_number_clean, -4);

// Generate transaction ID
$transaction_id = 'TXN' . strtoupper(uniqid());

// In a real application, you would integrate with a payment gateway here
// For this example, we'll simulate a successful payment
// IMPORTANT: Never store full card details in your database!

try {
    // Create donations table if it doesn't exist
    $create_table_sql = "CREATE TABLE IF NOT EXISTS donations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        transaction_id VARCHAR(50) UNIQUE NOT NULL,
        donor_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(50) NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        payment_method VARCHAR(50) NOT NULL,
        card_type VARCHAR(50),
        masked_card_number VARCHAR(50),
        status VARCHAR(20) NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_transaction_id (transaction_id),
        INDEX idx_email (email),
        INDEX idx_created_at (created_at)
    )";
    
    $conn->query($create_table_sql);
    
    // Prepare SQL statement to insert donation record
    $stmt = $conn->prepare("INSERT INTO donations 
        (transaction_id, donor_name, email, phone, amount, payment_method, card_type, masked_card_number, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $payment_method = 'Visa/Credit Card';
    $status = 'completed'; // In production, this would be 'pending' until payment gateway confirms
    
    $stmt->bind_param("ssssdssss", 
        $transaction_id, 
        $cardholder_name, 
        $email, 
        $phone, 
        $amount, 
        $payment_method, 
        $card_type, 
        $masked_card_number, 
        $status
    );
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Payment processed successfully!';
        $response['transaction_id'] = $transaction_id;
        $response['amount'] = $amount;
        
        // Log successful transaction
        error_log("Donation processed: Transaction ID: $transaction_id, Amount: $amount, Email: $email");
        
        // In a real application, you would:
        // 1. Send confirmation email to donor
        // 2. Send notification to admin
        // 3. Generate receipt
        // 4. Update dashboard statistics
        
        // Optional: Send email notification (uncomment if you have email configured)
        /*
        $to = $email;
        $subject = "Donation Confirmation - Transaction #$transaction_id";
        $message = "Dear $cardholder_name,\n\n";
        $message .= "Thank you for your generous donation of $$amount.\n\n";
        $message .= "Transaction ID: $transaction_id\n";
        $message .= "Payment Method: $payment_method\n";
        $message .= "Date: " . date('Y-m-d H:i:s') . "\n\n";
        $message .= "Your support helps us make a difference in our community.\n\n";
        $message .= "Best regards,\nCooficongo Team";
        $headers = "From: donations@cooficongo.org";
        
        mail($to, $subject, $message, $headers);
        */
        
    } else {
        $response['message'] = 'Failed to record donation. Please try again.';
        error_log("Database error: " . $stmt->error);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    $response['message'] = 'An error occurred while processing your payment.';
    error_log("Exception: " . $e->getMessage());
}

$conn->close();

// Return JSON response
echo json_encode($response);
?>
