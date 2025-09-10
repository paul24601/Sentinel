<?php
require_once 'session_config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/admin_notifications.php';

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: ../login.html");
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Get admin notifications for current user
$admin_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
$notification_count = getUnviewedNotificationCount($_SESSION['id_number'], $_SESSION['full_name']);

// Handle clone data if coming from clone request
$clonedData = [];
if (isset($_SESSION['clone_data'])) {
    $clonedData = $_SESSION['clone_data'];
    unset($_SESSION['clone_data']); // Clear after use
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parameters - Mobile Wizard | Sentinel</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="mobile_wizard_styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">
                <i class="bi bi-shield-check"></i> Sentinel
            </a>
            <button class="btn btn-outline-light btn-sm" onclick="exitWizard()">
                <i class="bi bi-x-lg"></i> Exit
            </button>
        </div>
    </nav>

    <!-- Progress Bar -->
    <div class="progress-container bg-light py-3">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Parameters Setup</h6>
                <span class="badge bg-primary" id="progress-text">Step 1 of 12</span>
            </div>
            <div class="progress mt-2" style="height: 8px;">
                <div class="progress-bar" role="progressbar" style="width: 8.33%" id="progress-bar"></div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <!-- Step Content Container -->
                <div id="wizard-content">
                    <!-- Steps will be loaded here -->
                </div>
                
                <!-- Navigation Buttons -->
                <div class="wizard-navigation mt-4">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" id="prev-btn" disabled>
                            <i class="bi bi-chevron-left"></i> Previous
                        </button>
                        <button type="button" class="btn btn-primary" id="next-btn">
                            Next <i class="bi bi-chevron-right"></i>
                        </button>
                        <button type="button" class="btn btn-success d-none" id="submit-btn">
                            <i class="bi bi-check-circle"></i> Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-save Notification -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="autosave-toast" class="toast" role="alert">
            <div class="toast-body">
                <i class="bi bi-cloud-check text-success"></i>
                Progress saved automatically
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="mobile_wizard.js"></script>
    
    <script>
        // Initialize wizard with cloned data if available
        const clonedData = <?php echo json_encode($clonedData); ?>;
        window.wizardData = clonedData;
    </script>
</body>
</html>
