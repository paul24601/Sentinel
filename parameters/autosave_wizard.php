<?php
// Set timezone to Philippine Time (UTC+8)
date_default_timezone_set('Asia/Manila');

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session and check authentication
session_start();

// Check if the user is logged in
if (!isset($_SESSION['full_name']) || empty($_SESSION['full_name'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Session expired. Please log in again.']);
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Load centralized database configuration
require_once __DIR__ . '/../includes/database.php';

// Handle different actions
$action = $_GET['action'] ?? $_POST['action'] ?? 'save';

try {
    $conn = DatabaseManager::getConnection('sentinel_main');
    $conn->query("SET time_zone = '+08:00'");
    
    $user_id = $_SESSION['id_number'] ?? $_SESSION['full_name'];
    
    switch ($action) {
        case 'save':
            // Save autosave data
            $post_data = $_POST;
            unset($post_data['action']); // Remove action from data to save
            $wizard_data = json_encode($post_data);
            $current_step = intval($_POST['current_step'] ?? 1);
            
            // Validate that we have some data to save
            if (empty($post_data) || (count($post_data) == 1 && isset($post_data['current_step']))) {
                echo json_encode([
                    'success' => false,
                    'message' => 'No data to save'
                ]);
                break;
            }
            
            // Check if autosave record exists
            $sql = "SELECT id FROM wizard_autosave WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Update existing autosave
                $sql = "UPDATE wizard_autosave SET wizard_data = ?, current_step = ?, last_saved = NOW() WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sis", $wizard_data, $current_step, $user_id);
            } else {
                // Create new autosave
                $sql = "INSERT INTO wizard_autosave (user_id, wizard_data, current_step, last_saved) VALUES (?, ?, ?, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $user_id, $wizard_data, $current_step);
            }
            
            $stmt->execute();
            
            echo json_encode([
                'success' => true,
                'message' => 'Progress saved successfully',
                'timestamp' => date('Y-m-d H:i:s'),
                'saved_fields' => count($post_data)
            ]);
            break;
            
        case 'load':
            // Load autosave data
            $sql = "SELECT wizard_data, current_step, last_saved FROM wizard_autosave WHERE user_id = ? AND last_saved > DATE_SUB(NOW(), INTERVAL 24 HOUR)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                echo json_encode([
                    'success' => true,
                    'data' => json_decode($row['wizard_data'], true),
                    'current_step' => $row['current_step'],
                    'last_saved' => $row['last_saved']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No recent autosave data found'
                ]);
            }
            break;
            
        case 'clear':
            // Clear autosave data
            $sql = "DELETE FROM wizard_autosave WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            
            echo json_encode([
                'success' => true,
                'message' => 'Autosave data cleared'
            ]);
            break;
            
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
    }
    
} catch (Exception $e) {
    error_log("Autosave error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'An error occurred while processing autosave.',
        'details' => $e->getMessage()
    ]);
}
?>
