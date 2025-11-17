<?php
/**
 * System Test Script
 * Tests database connection, table existence, and required files
 */

header('Content-Type: application/json');

$response = array(
    'success' => false,
    'message' => ''
);

$test = isset($_GET['test']) ? $_GET['test'] : '';

switch ($test) {
    case 'database':
        // Test database connection
        try {
            require_once 'connection.php';
            
            if ($conn->connect_error) {
                $response['message'] = $conn->connect_error;
            } else {
                $response['success'] = true;
                $response['message'] = 'Connected to database: ' . $dbname;
            }
            
            $conn->close();
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        break;
        
    case 'table':
        // Test if donations table exists
        try {
            require_once 'connection.php';
            
            $result = $conn->query("SHOW TABLES LIKE 'donations'");
            
            if ($result->num_rows > 0) {
                // Get column count
                $columns = $conn->query("SHOW COLUMNS FROM donations");
                $response['success'] = true;
                $response['columns'] = $columns->num_rows;
                $response['message'] = 'Donations table exists';
            } else {
                $response['message'] = 'Donations table does not exist. Run create_donations_table.sql';
            }
            
            $conn->close();
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        break;
        
    case 'files':
        // Test if required files exist
        $required_files = array(
            'donation.html',
            'assets/css/donation.css',
            'assets/js/donation.js',
            'php/process_visa_payment.php',
            'php/donationsdisplay.php'
        );
        
        $missing_files = array();
        $found_files = array();
        
        foreach ($required_files as $file) {
            $full_path = dirname(__DIR__) . '/' . $file;
            
            if (file_exists($full_path)) {
                $found_files[] = $file;
            } else {
                $missing_files[] = $file;
            }
        }
        
        if (empty($missing_files)) {
            $response['success'] = true;
            $response['files'] = $found_files;
            $response['message'] = 'All required files exist';
        } else {
            $response['missing'] = $missing_files;
            $response['message'] = 'Some required files are missing';
        }
        break;
        
    default:
        $response['message'] = 'Invalid test parameter';
}

echo json_encode($response);
?>
