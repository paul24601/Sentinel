<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: ../login.html");
    exit();
}

// Check if record_id is provided
if (!isset($_GET['record_id']) || empty($_GET['record_id'])) {
    echo "<div style='text-align:center; margin-top:50px;'>";
    echo "<h2>Error: No Record ID Provided</h2>";
    echo "<p>Please specify a valid record ID to print.</p>";
    echo "<a href='submission.php'>Return to Records</a>";
    echo "</div>";
    exit();
}

$record_id = $_GET['record_id'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";
$dbname = "injectionmoldingparameters";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the record details
$sql = "SELECT * FROM parameter_records WHERE record_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $record_id);
$stmt->execute();
$recordData = $stmt->get_result()->fetch_assoc();

if (!$recordData) {
    echo "<div style='text-align:center; margin-top:50px;'>";
    echo "<h2>Error: Record Not Found</h2>";
    echo "<p>The requested record could not be found.</p>";
    echo "<a href='submission.php'>Return to Records</a>";
    echo "</div>";
    exit();
}

// Fetch all data for this record
function fetchRecordData($conn, $table, $record_id) {
    $sql = "SELECT * FROM $table WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $record_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Fetch data from all related tables
$productMachineInfo = fetchRecordData($conn, 'productmachineinfo', $record_id);
$productDetails = fetchRecordData($conn, 'productdetails', $record_id);
$materialComposition = fetchRecordData($conn, 'materialcomposition', $record_id);
$colorantDetails = fetchRecordData($conn, 'colorantdetails', $record_id);
$moldOperationSpecs = fetchRecordData($conn, 'moldoperationspecs', $record_id);
$timerParameters = fetchRecordData($conn, 'timerparameters', $record_id);
$barrelHeaterTemperatures = fetchRecordData($conn, 'barrelheatertemperatures', $record_id);
$moldHeaterTemperatures = fetchRecordData($conn, 'moldheatertemperatures', $record_id);
$plasticizingParameters = fetchRecordData($conn, 'plasticizingparameters', $record_id);
$injectionParameters = fetchRecordData($conn, 'injectionparameters', $record_id);
$ejectionParameters = fetchRecordData($conn, 'ejectionparameters', $record_id);
$personalData = fetchRecordData($conn, 'personnel', $record_id);

// Fetch attachments
$sql = "SELECT * FROM attachments WHERE record_id = ? AND FileType LIKE 'image%' LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $record_id);
$stmt->execute();
$attachments = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Record: <?= htmlspecialchars($recordData['title']) ?></title>
    <!-- Bootstrap CSS for printing -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            body {
                font-size: 12px;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-before: always;
            }
            table {
                font-size: 10px;
            }
            .section-title {
                background-color: #f5f5f5 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        
        .section-title {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 5px solid #0d6efd;
        }
        
        .data-row {
            margin-bottom: 8px;
        }
        
        .data-label {
            font-weight: bold;
            margin-right: 5px;
        }
        
        .print-header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Print Controls (not printed) -->
        <div class="row mb-4 no-print">
            <div class="col-12">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i> Print
                </button>
                <a href="submission.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Records
                </a>
            </div>
        </div>
        
        <!-- Print Header -->
        <div class="print-header">
            <h1>Injection Molding Parameters</h1>
            <h3><?= htmlspecialchars($recordData['title']) ?></h3>
            <p>Record ID: <?= htmlspecialchars($record_id) ?></p>
            <p>Date: <?= date('Y-m-d', strtotime($recordData['submission_date'])) ?></p>
        </div>
        
        <!-- Record Meta Information -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="section-title">
                    <h4>Record Information</h4>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="data-row">
                            <span class="data-label">Submitted By:</span>
                            <span><?= htmlspecialchars($recordData['submitted_by']) ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="data-row">
                            <span class="data-label">Submission Date:</span>
                            <span><?= htmlspecialchars($recordData['submission_date']) ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="data-row">
                            <span class="data-label">Status:</span>
                            <span><?= htmlspecialchars(ucfirst($recordData['status'])) ?></span>
                        </div>
                    </div>
                </div>
                <?php if ($recordData['description']): ?>
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="data-row">
                            <p class="data-label">Description:</p>
                            <p><?= nl2br(htmlspecialchars($recordData['description'])) ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Main Data Sections -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="section-title">
                    <h4>Product and Machine Information</h4>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="data-row">
                            <span class="data-label">Date:</span>
                            <span><?= htmlspecialchars($productMachineInfo['Date']) ?></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="data-row">
                            <span class="data-label">Time:</span>
                            <span><?= htmlspecialchars($productMachineInfo['Time']) ?></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="data-row">
                            <span class="data-label">Machine:</span>
                            <span><?= htmlspecialchars($productMachineInfo['MachineName']) ?></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="data-row">
                            <span class="data-label">Run Number:</span>
                            <span><?= htmlspecialchars($productMachineInfo['RunNumber']) ?></span>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <div class="data-row">
                            <span class="data-label">Category:</span>
                            <span><?= htmlspecialchars($productMachineInfo['Category']) ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="data-row">
                            <span class="data-label">IRN:</span>
                            <span><?= htmlspecialchars($productMachineInfo['IRN']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Details -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="section-title">
                    <h4>Product Details</h4>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="data-row">
                            <span class="data-label">Product Name:</span>
                            <span><?= htmlspecialchars($productDetails['ProductName']) ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="data-row">
                            <span class="data-label">Color:</span>
                            <span><?= htmlspecialchars($productDetails['Color']) ?></span>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4">
                        <div class="data-row">
                            <span class="data-label">Mold Name:</span>
                            <span><?= htmlspecialchars($productDetails['MoldName']) ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="data-row">
                            <span class="data-label">Product Number:</span>
                            <span><?= htmlspecialchars($productDetails['ProductNumber']) ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="data-row">
                            <span class="data-label">Active Cavity:</span>
                            <span><?= htmlspecialchars($productDetails['CavityActive']) ?></span>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <div class="data-row">
                            <span class="data-label">Gross Weight:</span>
                            <span><?= htmlspecialchars($productDetails['GrossWeight']) ?> g</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="data-row">
                            <span class="data-label">Net Weight:</span>
                            <span><?= htmlspecialchars($productDetails['NetWeight']) ?> g</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Continue with other sections... -->
        
        <!-- Personnel Information -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="section-title">
                    <h4>Personnel</h4>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="data-row">
                            <span class="data-label">Adjuster:</span>
                            <span><?= htmlspecialchars($personalData['AdjusterName']) ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="data-row">
                            <span class="data-label">Quality Assurance Engineer:</span>
                            <span><?= htmlspecialchars($personalData['QAEName']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Attachments - Images -->
        <?php if ($attachments->num_rows > 0): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="section-title">
                    <h4>Images</h4>
                </div>
                <div class="row">
                    <?php while ($image = $attachments->fetch_assoc()): 
                        $relativePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $image['FilePath']);
                    ?>
                    <div class="col-md-3 mb-3">
                        <img src="<?= htmlspecialchars($relativePath) ?>" 
                             alt="<?= htmlspecialchars($image['FileName']) ?>" 
                             class="img-thumbnail" style="max-height: 150px;">
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Footer -->
        <div class="row mt-5">
            <div class="col-12 text-center">
                <p>This document was generated on <?= date('Y-m-d H:i:s') ?></p>
                <p>© <?= date('Y') ?> Sentinel Digitization</p>
            </div>
        </div>
    </div>
    
    <script>
        // Auto-print when page loads (comment out for development)
        window.onload = function() {
            // Uncomment to automatically print when opened
            // window.print();
        };
    </script>
</body>
</html> 