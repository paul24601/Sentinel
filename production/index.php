<?php
session_start(); // Start session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    // Redirect to login if not logged in
    header("Location: ../login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Report Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h2>Production Report Form</h2>
            </div>
            <div class="card-body">
                <!-- Form starts here -->
                <form action="process_production_report.php" method="POST">
                    <!-- Plant -->
                    <div class="mb-3">
                        <label for="plant" class="form-label">Plant</label>
                        <input type="text" class="form-control" id="plant" name="plant" placeholder="Enter Plant Name" required>
                    </div>
                    
                    <!-- Date -->
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" max="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <!-- Shift -->
                    <div class="mb-3">
                        <label for="shift" class="form-label">Shift</label>
                        <select class="form-select" id="shift" name="shift" required>
                            <option value="" disabled selected>Select Shift</option>
                            <option value="1st Shift">1st Shift</option>
                            <option value="2nd Shift">2nd Shift</option>
                            <option value="3rd Shift">3rd Shift</option>
                        </select>
                    </div>

                    <!-- Shift Hours -->
                    <div class="mb-3">
                        <label for="shift_hours" class="form-label">Shift Hours</label>
                        <input type="number" class="form-control" id="shift_hours" name="shift_hours" placeholder="Enter Shift Hours" min="0" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Submit Report</button>
                    </div>

                    <!-- Additional Links (Optional) -->
                    <div class="row mt-3">
                        <div class="col-6 d-grid">
                            <a href="view_reports.php" class="btn btn-secondary">View Reports</a>
                        </div>
                        <div class="col-6 d-grid">
                            <a href="../admin_dashboard.php" class="btn btn-info">Admin Dashboard</a>
                        </div>
                    </div>

                    <!-- Logout Button -->
                    <div class="d-grid mt-3">
                        <a href="../logout.php" class="btn btn-danger">Log Out</a>
                    </div>
                </form>
            </div>
            <div class="card-footer text-muted text-center">
                &copy; 2024 Sentinel OJT
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
