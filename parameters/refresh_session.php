<?php
require_once 'session_config.php';

header('Content-Type: application/json');

// Get the request data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$response = ['status' => 'error', 'message' => 'Unknown error'];

if (!isset($data['action'])) {
    $data['action'] = 'refresh'; // Default action for backward compatibility
}

// Enhanced session validation
function isSessionValid() {
    if (!isset($_SESSION['full_name']) || !isset($_SESSION['id_number']) || !isset($_SESSION['role'])) {
        return false;
    }
    
    if (empty(trim($_SESSION['full_name'])) || empty(trim($_SESSION['id_number']))) {
        return false;
    }
    
    return true;
}

switch ($data['action']) {
    case 'refresh':
        if (isSessionValid()) {
            session_regenerate_id(true);
            $_SESSION['last_activity'] = time();
            $response = [
                'status' => 'success',
                'message' => 'Session refreshed',
                'user' => $_SESSION['full_name'],
                'remaining_time' => 1800 - (time() - $_SESSION['last_activity'])
            ];
        } else {
            http_response_code(401);
            $response = [
                'status' => 'invalid',
                'message' => 'Invalid session data'
            ];
        }
        break;
        
    case 'validate':
        if (isSessionValid()) {
            // Check if session is still within time limit
            $time_since_activity = time() - ($_SESSION['last_activity'] ?? 0);
            
            if ($time_since_activity > 1800) {
                http_response_code(401);
                $response = [
                    'status' => 'expired',
                    'message' => 'Session has expired'
                ];
            } else {
                $_SESSION['last_activity'] = time();
                $response = [
                    'status' => 'success',
                    'message' => 'Session is valid',
                    'user' => $_SESSION['full_name'],
                    'time_remaining' => 1800 - $time_since_activity
                ];
            }
        } else {
            http_response_code(401);
            $response = [
                'status' => 'invalid',
                'message' => 'Session validation failed'
            ];
        }
        break;
        
    case 'check_user':
        $response = [
            'status' => 'success',
            'user_info' => [
                'full_name' => $_SESSION['full_name'] ?? 'Unknown',
                'id_number' => $_SESSION['id_number'] ?? 'Unknown',
                'role' => $_SESSION['role'] ?? 'Unknown',
                'session_valid' => isSessionValid()
            ]
        ];
        break;
        
    default:
        // Default behavior for backward compatibility
        if (!isset($_SESSION['full_name'])) {
            http_response_code(401);
            $response = ['status' => 'error', 'message' => 'Not logged in'];
        } else {
            session_regenerate_id(true);
            $_SESSION['last_activity'] = time();
            $response = ['status' => 'success'];
        }
        break;
}

echo json_encode($response);
?> 