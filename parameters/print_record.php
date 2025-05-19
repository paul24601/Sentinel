<?php
session_start();
require_once '../includes/db_connection.php';

if (!isset($_GET['record_id'])) {
    die("No record ID provided");
}

$record_id = $_GET['record_id'];

// Fetch all data for the record
$sql = "SELECT * FROM parameter_records WHERE record_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $record_id);
$stmt->execute();
$record = $stmt->get_result()->fetch_assoc();

// Fetch all related data
$tables = [
    'productmachineinfo',
    'productdetails',
    'materialcomposition',
    'colorantdetails',
    'moldoperationspecs',
    'timerparameters',
    'barrelheatertemperatures',
    'moldheatertemperatures',
    'plasticizingparameters',
    'injectionparameters',
    'ejectionparameters',
    'corepullsettings',
    'additionalinformation',
    'personnel',
    'attachments'
];

$data = [];
foreach ($tables as $table) {
    $sql = "SELECT * FROM $table WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $record_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data[$table] = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Record - <?= htmlspecialchars($record_id) ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-before: always;
            }
            body {
                font-size: 12pt;
            }
            .card {
                border: 1px solid #000 !important;
            }
            .card-header {
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
            }
        }
        .card {
            margin-bottom: 20px;
        }
        .card-header {
            font-weight: bold;
        }
        .table th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="no-print mb-4">
            <button onclick="window.print()" class="btn btn-primary">Print Record</button>
            <a href="submission.php?record_id=<?= htmlspecialchars($record_id) ?>" class="btn btn-secondary">Back to Record</a>
        </div>

        <!-- Header Information -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><?= htmlspecialchars($record['title']) ?></h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Record ID:</strong> <?= htmlspecialchars($record_id) ?></p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Submitted by:</strong> <?= htmlspecialchars($record['submitted_by']) ?></p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Submission Date:</strong> <?= htmlspecialchars($record['submission_date']) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product & Machine Info -->
        <div class="card mb-4">
            <div class="card-header">Product & Machine Information</div>
            <div class="card-body">
                <div class="row">
                    <?php if ($data['productmachineinfo']): ?>
                    <div class="col-md-6">
                        <p><strong>Date:</strong> <?= htmlspecialchars($data['productmachineinfo']['Date']) ?></p>
                        <p><strong>Time:</strong> <?= htmlspecialchars($data['productmachineinfo']['Time']) ?></p>
                        <p><strong>Machine Name:</strong> <?= htmlspecialchars($data['productmachineinfo']['MachineName']) ?></p>
                        <p><strong>Run Number:</strong> <?= htmlspecialchars($data['productmachineinfo']['RunNumber']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Category:</strong> <?= htmlspecialchars($data['productmachineinfo']['Category']) ?></p>
                        <p><strong>IRN:</strong> <?= htmlspecialchars($data['productmachineinfo']['IRN']) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="card mb-4">
            <div class="card-header">Product Details</div>
            <div class="card-body">
                <div class="row">
                    <?php if ($data['productdetails']): ?>
                    <div class="col-md-6">
                        <p><strong>Product Name:</strong> <?= htmlspecialchars($data['productdetails']['ProductName']) ?></p>
                        <p><strong>Color:</strong> <?= htmlspecialchars($data['productdetails']['Color']) ?></p>
                        <p><strong>Mold Name:</strong> <?= htmlspecialchars($data['productdetails']['MoldName']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Product Number:</strong> <?= htmlspecialchars($data['productdetails']['ProductNumber']) ?></p>
                        <p><strong>Cavity Active:</strong> <?= htmlspecialchars($data['productdetails']['CavityActive']) ?></p>
                        <p><strong>Gross Weight:</strong> <?= htmlspecialchars($data['productdetails']['GrossWeight']) ?></p>
                        <p><strong>Net Weight:</strong> <?= htmlspecialchars($data['productdetails']['NetWeight']) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Material Composition -->
        <div class="card mb-4">
            <div class="card-header">Material Composition</div>
            <div class="card-body">
                <?php if ($data['materialcomposition']): ?>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Drying Time:</strong> <?= htmlspecialchars($data['materialcomposition']['DryingTime']) ?></p>
                        <p><strong>Drying Temperature:</strong> <?= htmlspecialchars($data['materialcomposition']['DryingTemperature']) ?></p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Type</th>
                                <th>Brand</th>
                                <th>Mixture %</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 1; $i <= 4; $i++): ?>
                            <tr>
                                <td>Material <?= $i ?></td>
                                <td><?= htmlspecialchars($data['materialcomposition']["Material{$i}_Type"]) ?></td>
                                <td><?= htmlspecialchars($data['materialcomposition']["Material{$i}_Brand"]) ?></td>
                                <td><?= htmlspecialchars($data['materialcomposition']["Material{$i}_MixturePercentage"]) ?></td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Continue with other sections... -->
        <!-- Add similar card sections for all other data -->

        <!-- Attachments -->
        <?php if ($data['attachments']): ?>
        <div class="card mb-4">
            <div class="card-header">Attachments</div>
            <div class="card-body">
                <div class="row">
                    <?php
                    $sql = "SELECT * FROM attachments WHERE record_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $record_id);
                    $stmt->execute();
                    $attachments = $stmt->get_result();
                    while ($attachment = $attachments->fetch_assoc()):
                    ?>
                    <div class="col-md-4 mb-3">
                        <p><strong>File:</strong> <?= htmlspecialchars($attachment['FileName']) ?></p>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
</html> 