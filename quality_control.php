<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quality Control Form</title>
    <link rel="stylesheet" href="css/quality_control.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4">Quality Control Sheet</h2>
        <form id="qualityControlForm" method="POST" action="process_quality_control.php">
            <!-- Header Information -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="plant">Plant:</label>
                        <input type="text" class="form-control" id="plant" name="plant" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="shift">Shift:</label>
                        <select class="form-control" id="shift" name="shift" required>
                            <option value="">Select Shift</option>
                            <option value="Morning">Morning</option>
                            <option value="Afternoon">Afternoon</option>
                            <option value="Night">Night</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="shiftHours">Shift Hours:</label>
                        <input type="text" class="form-control" id="shiftHours" name="shiftHours" required>
                    </div>
                </div>
            </div>

            <!-- Product Information -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="productName">Product Name:</label>
                        <input type="text" class="form-control" id="productName" name="productName" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="color">Color:</label>
                        <input type="text" class="form-control" id="color" name="color" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="partNo">Part No:</label>
                        <input type="text" class="form-control" id="partNo" name="partNo" required>
                    </div>
                </div>
            </div>

            <!-- ID Numbers and Other Info -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="idNumber1">ID Number 1:</label>
                        <input type="text" class="form-control" id="idNumber1" name="idNumber1">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="idNumber2">ID Number 2:</label>
                        <input type="text" class="form-control" id="idNumber2" name="idNumber2">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="idNumber3">ID Number 3:</label>
                        <input type="text" class="form-control" id="idNumber3" name="idNumber3">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="ejo">EJO #:</label>
                        <input type="text" class="form-control" id="ejo" name="ejo">
                    </div>
                </div>
            </div>

            <!-- Assembly Line Info -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="assemblyLine">Assembly Line # / Table #:</label>
                        <input type="text" class="form-control" id="assemblyLine" name="assemblyLine" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="manpower">Manpower Allocation:</label>
                        <input type="number" class="form-control" id="manpower" name="manpower" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="reg">REG:</label>
                        <input type="text" class="form-control" id="reg" name="reg">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="ot">OT:</label>
                        <input type="text" class="form-control" id="ot" name="ot">
                    </div>
                </div>
            </div>

            <!-- Quality Control Table -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered" id="qualityTable">
                    <thead>
                        <tr>
                            <th>Part Name</th>
                            <th>Defect</th>
                            <th colspan="8">Time</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="qualityTableBody">
                        <!-- Initial empty row -->
                        <tr>
                            <td><input type="text" class="form-control" name="partName[]"></td>
                            <td><input type="text" class="form-control" name="defect[]"></td>
                            <td><input type="number" class="form-control time-input" name="time1[]"></td>
                            <td><input type="number" class="form-control time-input" name="time2[]"></td>
                            <td><input type="number" class="form-control time-input" name="time3[]"></td>
                            <td><input type="number" class="form-control time-input" name="time4[]"></td>
                            <td><input type="number" class="form-control time-input" name="time5[]"></td>
                            <td><input type="number" class="form-control time-input" name="time6[]"></td>
                            <td><input type="number" class="form-control time-input" name="time7[]"></td>
                            <td><input type="number" class="form-control time-input" name="time8[]"></td>
                            <td><input type="number" class="form-control total" name="total[]" readonly></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">Total Reject</td>
                            <td colspan="8"><input type="number" class="form-control" id="totalReject" name="totalReject" readonly></td>
                            <td><input type="number" class="form-control" id="totalRejectSum" name="totalRejectSum" readonly></td>
                        </tr>
                        <tr>
                            <td colspan="2">Total Good</td>
                            <td colspan="8"><input type="number" class="form-control" id="totalGood" name="totalGood"></td>
                            <td><input type="number" class="form-control" id="totalGoodSum" name="totalGoodSum" readonly></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Downtime Section -->
            <div class="mb-4">
                <h4>Downtime</h4>
                <div class="table-responsive">
                    <table class="table table-bordered" id="downtimeTable">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Time (minutes)</th>
                            </tr>
                        </thead>
                        <tbody id="downtimeTableBody">
                            <tr>
                                <td><input type="text" class="form-control" name="downtimeDesc[]"></td>
                                <td><input type="number" class="form-control downtime-minutes" name="downtimeMinutes[]"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-secondary" id="addDowntimeRow">Add Downtime Row</button>
            </div>

            <!-- Remarks -->
            <div class="form-group mb-4">
                <label for="remarks">Remarks:</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="4"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit Form</button>
                <button type="button" class="btn btn-secondary" id="addRow">Add Quality Row</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/quality_control.js"></script>
</body>
</html> 