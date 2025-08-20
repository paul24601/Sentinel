<?php
/**
 * Layout Helper Component
 * Centralized layout management and utilities
 *
 * @author Sentinel Development Team
 * @date August 20, 2025
 */

// Define getBasePath function if it doesn't exist
if (!function_exists('getBasePath')) {
    function getBasePath($file = null) {
        if ($file === null) {
            $file = $_SERVER['SCRIPT_FILENAME'];
        }
        
        $currentDir = dirname($_SERVER['SCRIPT_NAME']);
        
        // Check if we're in a subdirectory
        if (strpos($currentDir, '/dms') !== false || strpos($currentDir, '/parameters') !== false || 
            strpos($currentDir, '/admin') !== false || strpos($currentDir, '/production_report') !== false) {
            return '../';
        } else {
            return '';
        }
    }
}

/**
 * Generate standard page header with all necessary includes
 */
function generatePageHeader($page_title = 'Sentinel OJT', $additional_css = [], $include_datatables = false, $include_charts = false) {
    $base_path = getBasePath(__FILE__);

    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($page_title) . '</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS -->';

    if ($include_datatables) {
        echo '
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">';
    }

    if ($include_charts) {
        echo '
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
    }

    foreach ($additional_css as $css_file) {
        echo '
    <link rel="stylesheet" href="' . htmlspecialchars($css_file) . '">';
    }

    echo '
    <style>
        body {
            padding-top: 56px;
            background-color: #f8f9fa;
        }
        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s;
        }
        @media (min-width: 768px) {
            .main-content {
                margin-left: 16.66667%;
            }
        }
        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
        }
    </style>
</head>
<body>';
}

/**
 * Generate page footer with scripts
 */
function generatePageFooter($additional_scripts = [], $include_datatables = false, $include_charts = false) {
    echo '
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';

    if ($include_datatables) {
        echo '
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>';
    }

    foreach ($additional_scripts as $script_file) {
        echo '
    <script src="' . htmlspecialchars($script_file) . '"></script>';
    }
    
    echo '
</body>
</html>';
}
?>
