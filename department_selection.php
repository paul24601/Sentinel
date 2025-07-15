<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id_number'])) {
    header("Location: login.html");
    exit();
}

// Handle department selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['department'])) {
    $selected_department = $_POST['department'];
    $_SESSION['selected_department'] = $selected_department;
    
    // Redirect based on selected department
    switch ($selected_department) {
        case 'injection':
            header("Location: injection_department.php");
            break;
        case 'ts':
            header("Location: ts_department.php");
            break;
        case 'production':
            header("Location: production_department.php");
            break;
        case 'puvc':
            header("Location: puvc_department.php");
            break;
        default:
            header("Location: index.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Selection - Sentinel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .department-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: none;
            overflow: hidden;
        }
        
        .department-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        .department-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            display: block;
        }
        
        .injection-icon { color: #e74c3c; }
        .ts-icon { color: #3498db; }
        .production-icon { color: #2ecc71; }
        .puvc-icon { color: #f39c12; }
        
        .welcome-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .btn-department {
            width: 100%;
            padding: 1.5rem;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 15px;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-injection {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }
        
        .btn-ts {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }
        
        .btn-production {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
        }
        
        .btn-puvc {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
        }
        
        .btn-department:hover {
            transform: scale(1.05);
            color: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .logout-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <!-- Logout Button -->
    <div class="logout-btn">
        <a href="logout.php" class="btn btn-outline-light">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="container py-5">
        <!-- Welcome Section -->
        <div class="welcome-section text-center">
            <h1 class="display-4 mb-3">
                <i class="fas fa-building"></i> Welcome to Sentinel
            </h1>
            <p class="lead mb-0">
                Hello, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong>! 
                Please select the department or system you want to access.
            </p>
            <small class="text-muted">Role: <?php echo htmlspecialchars($_SESSION['role']); ?></small>
        </div>

        <!-- Department Selection -->
        <div class="row g-4">
            <!-- Injection Department -->
            <div class="col-lg-6 col-md-6">
                <div class="department-card h-100 p-4 text-center">
                    <form method="POST" action="">
                        <input type="hidden" name="department" value="injection">
                        <button type="submit" class="btn btn-department btn-injection">
                            <i class="fas fa-industry department-icon injection-icon"></i>
                            <h3>Injection Department</h3>
                            <p class="mb-0">Access injection molding parameters and monitoring</p>
                        </button>
                    </form>
                </div>
            </div>

            <!-- TS Department -->
            <div class="col-lg-6 col-md-6">
                <div class="department-card h-100 p-4 text-center">
                    <form method="POST" action="">
                        <input type="hidden" name="department" value="ts">
                        <button type="submit" class="btn btn-department btn-ts">
                            <i class="fas fa-cogs department-icon ts-icon"></i>
                            <h3>TS Department</h3>
                            <p class="mb-0">Technical support and system management</p>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Production Department -->
            <div class="col-lg-6 col-md-6">
                <div class="department-card h-100 p-4 text-center">
                    <form method="POST" action="">
                        <input type="hidden" name="department" value="production">
                        <button type="submit" class="btn btn-department btn-production">
                            <i class="fas fa-chart-line department-icon production-icon"></i>
                            <h3>Production Department</h3>
                            <p class="mb-0">Production reports and data management</p>
                        </button>
                    </form>
                </div>
            </div>

            <!-- PUVC Department -->
            <div class="col-lg-6 col-md-6">
                <div class="department-card h-100 p-4 text-center">
                    <form method="POST" action="">
                        <input type="hidden" name="department" value="puvc">
                        <button type="submit" class="btn btn-department btn-puvc">
                            <i class="fas fa-shield-alt department-icon puvc-icon"></i>
                            <h3>PUVC Department</h3>
                            <p class="mb-0">Quality control and validation processes</p>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-5">
            <p class="text-white-50">
                <i class="fas fa-info-circle"></i> 
                Select a department to access its specific features and data
            </p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 