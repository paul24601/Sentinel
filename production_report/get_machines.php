<?php
header('Content-Type: application/json');
require_once '../includes/database.php';

try {
    $conn = DatabaseManager::getConnection('sentinel_production');
    
    // Define standard machines from the system
    $machines = [
        'CLF750A' => 'CLF 750A',
        'CLF750B' => 'CLF 750B', 
        'CLF750C' => 'CLF 750C'
    ];
    
    // Get search term if provided
    $search = isset($_GET['term']) ? strtolower($_GET['term']) : '';
    
    $result = [];
    
    if (empty($search)) {
        // Return all machines if no search term
        foreach ($machines as $code => $name) {
            $result[] = [
                'value' => $name,
                'label' => $name,
                'code' => $code
            ];
        }
    } else {
        // Filter machines based on search term
        foreach ($machines as $code => $name) {
            if (strpos(strtolower($name), $search) !== false || 
                strpos(strtolower($code), $search) !== false) {
                $result[] = [
                    'value' => $name,
                    'label' => $name,
                    'code' => $code
                ];
            }
        }
    }
    
    echo json_encode($result);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
