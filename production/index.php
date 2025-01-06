<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Report Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-bordered td,
        .table-bordered th {
            vertical-align: middle;
            text-align: center;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h2>Production Report Form</h2>
            </div>
            <div class="card-body">
                <form action="process_production_report.php" method="POST">
                    <!-- Plant, Date, Shift, and Hours Section -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="plant" class="form-label">Plant:</label>
                            <input required type="text" class="form-control" id="plant" name="plant" required>
                        </div>
                        <div class="col-md-3">
                            <label for="date" class="form-label">Date:</label>
                            <input required type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="col-md-3">
                            <label for="shift" class="form-label">Shift:</label>
                            <input required type="text" class="form-control" id="shift" name="shift" required>
                        </div>
                        <div class="col-md-3">
                            <label for="shift_hours" class="form-label">Shift Hours:</label>
                            <input required type="number" class="form-control" id="shift_hours" name="shift_hours" min="0"
                                required>
                        </div>
                    </div>

                    <!-- Product Details Section -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="product_name" class="form-label">Product Name:</label>
                            <input required type="text" class="form-control" id="product_name" name="product_name" required>
                        </div>
                        <div class="col-md-3">
                            <label for="color" class="form-label">Color:</label>
                            <input required type="text" class="form-control" id="color" name="color" required>
                        </div>
                        <div class="col-md-3">
                            <label for="part_number" class="form-label">Part No.:</label>
                            <input required type="text" class="form-control" id="part_number" name="part_number" required>
                        </div>
                        <div class="col-md-3">
                            <label for="process" class="form-label">Process:</label>
                            <input required type="text" class="form-control" id="process" name="process" required>
                        </div>
                    </div>

                    <!-- ID Numbers Section -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="id_number_1" class="form-label">ID Number 1:</label>
                            <input required type="text" class="form-control" id="id_number_1" name="id_number_1" required>
                        </div>
                        <div class="col-md-4">
                            <label for="id_number_2" class="form-label">ID Number 2:</label>
                            <input required type="text" class="form-control" id="id_number_2" name="id_number_2" required>
                        </div>
                        <div class="col-md-4">
                            <label for="id_number_3" class="form-label">ID Number 3:</label>
                            <input required type="text" class="form-control" id="id_number_3" name="id_number_3" required>
                        </div>
                    </div>

                    <!-- Manpower Allocation Section -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="fjo" class="form-label">FJO #:</label>
                            <input required type="text" class="form-control" id="fjo" name="fjo" required>
                        </div>
                        <div class="col-md-3">
                            <label for="manpower_allocation" class="form-label">Manpower Allocation:</label>
                            <input required type="text" class="form-control" id="manpower_allocation" name="manpower_allocation"
                                required>
                        </div>
                        <div class="col-md-3">
                            <label for="reg" class="form-label">REG:</label>
                            <input required type="text" class="form-control" id="reg" name="reg" required>
                        </div>
                        <div class="col-md-3">
                            <label for="ot" class="form-label">OT:</label>
                            <input required type="text" class="form-control" id="ot" name="ot" required>
                        </div>
                    </div>

                    <!-- Assembly Line Section -->
                    <div class="mb-4">
                        <label for="assembly_line" class="form-label">Assembly Line # / Table #:</label>
                        <input required type="text" class="form-control" id="assembly_line" name="assembly_line" required>
                    </div>

                    <!-- Production Details Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Part Name</th>
                                    <th>Defect</th>
                                    <th colspan="12">Time</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < 10; $i++): ?>
                                    <tr>
                                        <td><input required type="text" class="form-control" name="part_name_<?php echo $i; ?>"
                                                placeholder="Enter Part Name"></td>
                                        <td><input required type="text" class="form-control" name="defect_<?php echo $i; ?>"
                                                placeholder="Enter Defect"></td>
                                        <?php for ($j = 0; $j < 12; $j++): ?>
                                            <td><input required type="number" class="form-control"
                                                    name="time_<?php echo $i . '_' . $j; ?>" min="0" placeholder="0"></td>
                                        <?php endfor; ?>
                                        <td><input required type="number" class="form-control" name="total_<?php echo $i; ?>" min="0"
                                                placeholder="0" readonly></td>
                                    </tr>
                                <?php endfor; ?>
                                <tr>
                                    <td colspan="13">Total Reject</td>
                                    <td><input required type="number" class="form-control" name="total_reject" placeholder="0"
                                            readonly></td>
                                </tr>
                                <tr>
                                    <td colspan="13">Total Good</td>
                                    <td><input required type="number" class="form-control" name="total_good" placeholder="0"
                                            readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Downtime Data Table Section -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Downtime Reason</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Total Downtime (minutes)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <tr>
                                        <td><input required type="text" class="form-control" name="downtime_reason_<?php echo $i; ?>"
                                                placeholder="Enter Downtime Reason"></td>
                                        <td><input required type="time" class="form-control" name="start_time_<?php echo $i; ?>"
                                                required></td>
                                        <td><input required type="time" class="form-control" name="end_time_<?php echo $i; ?>"
                                                required></td>
                                        <td><input required type="number" class="form-control"
                                                name="total_downtime_<?php echo $i; ?>" min="0" placeholder="0" readonly>
                                        </td>
                                    </tr>
                                <?php endfor; ?>
                                <tr>
                                    <td colspan="3">Total Downtime</td>
                                    <td><input required type="number" class="form-control" name="total_downtime_all"
                                            placeholder="0" readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Remarks Section -->
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks:</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3"
                            placeholder="Enter any remarks"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Submit Report</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-muted text-center">
                &copy; 2024 Production Report
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>