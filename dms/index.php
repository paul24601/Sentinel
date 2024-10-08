<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMS System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI for Autocomplete -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <script>
        $(function () {
            // Enable popovers
            $('[data-bs-toggle="popover"]').popover();

            $("#product_name").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "autocomplete.php",
                        dataType: "json",
                        data: {
                            term: request.term // Send the search term to the server
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                select: function (event, ui) {
                    // Populate other fields with numeric and non-numeric data
                    $('#machine').val(ui.item.machine);
                    $('#prn').val(ui.item.prn);
                    $('#mold_code').val(ui.item.mold_code);
                    $('#cycle_time_target').val(ui.item.cycle_time_target); // Numeric value
                    $('#cycle_time_actual').val(ui.item.cycle_time_actual); // Numeric value
                    $('#weight_standard').val(ui.item.weight_standard); // Numeric value
                    $('#weight_gross').val(ui.item.weight_gross); // Numeric value
                    $('#weight_net').val(ui.item.weight_net); // Numeric value
                    $('#cavity_designed').val(ui.item.cavity_designed); // Integer value
                    $('#cavity_active').val(ui.item.cavity_active); // Integer value
                    $('#remarks').val(ui.item.remarks);
                    $('#name').val(ui.item.name);
                    $('#shift').val(ui.item.shift);
                },
                focus: function (event, ui) {
                    // When hovering over a suggestion, preview the data in the fields
                    $('#machine').val(ui.item.machine);
                    $('#prn').val(ui.item.prn);
                    $('#mold_code').val(ui.item.mold_code);
                    $('#cycle_time_target').val(ui.item.cycle_time_target);
                    $('#cycle_time_actual').val(ui.item.cycle_time_actual);
                    $('#weight_standard').val(ui.item.weight_standard);
                    $('#weight_gross').val(ui.item.weight_gross);
                    $('#weight_net').val(ui.item.weight_net);
                    $('#cavity_designed').val(ui.item.cavity_designed);
                    $('#cavity_active').val(ui.item.cavity_active);
                    $('#remarks').val(ui.item.remarks);
                    $('#name').val(ui.item.name);
                    $('#shift').val(ui.item.shift);
                    return false; // Prevent replacing the text in the search box
                }
            });
        });

    </script>
</head>

<body class="bg-primary-subtle">
    <div class="container my-5 opacity-90">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h2>Daily Monitoring Sheet</h2>
            </div>

            <div class="card-body">
                <!-- Form starts here -->
                <form action="process_form.php" method="POST">
                    <!-- Date -->
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" max="<?php echo date('Y-m-d'); ?>"
                            required>
                    </div>

                    <!-- Product Name -->
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <!-- Popover Note -->
                        <span 
                            tabindex="0"
                            class="text-body-secondary"
                            data-bs-toggle="popover"
                            data-bs-trigger="hover focus"
                            data-bs-content="The first data submitted for the corresponding Product Name would be shown.">
                            <i class="bi bi-info-circle"></i>
                        </span>
                        <input type="text" id="product_name" name="product_name" class="form-control mt-2"
                            placeholder="Enter Product Name">
                    </div>

                    <!-- Machine -->
                    <div class="mb-3">
                        <label for="machine" class="form-label">Machine</label>
                        <input type="text" class="form-control" id="machine" name="machine"
                            placeholder="Enter machine name/number" required>
                    </div>

                    <!-- PRN -->
                    <div class="mb-3">
                        <label for="prn" class="form-label">PRN</label>
                        <input type="text" class="form-control" id="prn" name="prn" placeholder="Enter PRN">
                    </div>

                    <!-- Mold Code -->
                    <div class="mb-3">
                        <label for="mold_code" class="form-label">Mold Code</label>
                        <input type="text" class="form-control" id="mold_code" name="mold_code"
                            placeholder="Enter Mold Code" required>
                    </div>

                    <!-- Cycle Time -->
                    <div class="mb-4">
                        <h5 class="mb-3">Cycle Time</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="cycle-time-target" class="form-label">Target</label>
                                <input type="number" class="form-control" id="cycle_time_target"
                                    name="cycle_time_target" placeholder="Enter target cycle time" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label for="cycle-time-actual" class="form-label">Actual</label>
                                <input type="number" class="form-control" id="cycle_time_actual"
                                    name="cycle_time_actual" placeholder="Enter actual cycle time" min="0" required>
                            </div>
                        </div>
                    </div>

                    <!-- Weight -->
                    <div class="mb-4">
                        <h5 class="mb-3">WEIGHT (grams/pc)</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="weight-standard" class="form-label">Standard</label>
                                <input type="number" step="0.01" class="form-control" id="weight_standard"
                                    name="weight_standard" placeholder="Enter standard weight" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="weight-gross" class="form-label">Gross</label>
                                <input type="number" step="0.01" class="form-control" id="weight_gross"
                                    name="weight_gross" placeholder="Enter gross weight" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="weight-net" class="form-label">Net</label>
                                <input type="number" step="0.01" class="form-control" id="weight_net" name="weight_net"
                                    placeholder="Enter net weight" min="0" required>
                            </div>
                        </div>
                    </div>

                    <!-- Number of Cavity -->
                    <div class="mb-4">
                        <h5 class="mb-3">Number of Cavity</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="cavity-designed" class="form-label">Designed</label>
                                <input type="number" class="form-control" id="cavity_designed" name="cavity_designed"
                                    placeholder="Enter designed number of cavities" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label for="cavity-active" class="form-label">Active</label>
                                <input type="number" class="form-control" id="cavity_active" name="cavity_active"
                                    placeholder="Enter active number of cavities" min="0" required>
                            </div>
                        </div>
                    </div>

                     <!-- Remarks -->
                     <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <!-- Popover Note -->
                        <span 
                            tabindex="0"
                            class="text-body-secondary"
                            data-bs-toggle="popover"
                            data-bs-trigger="hover focus"
                            data-bs-content="Add remarks if Product has NO STANDARD CYCLE TIME AND WEIGHT.">
                            <i class="bi bi-info-circle"></i>
                        </span>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3"
                            placeholder="Enter any remarks"></textarea>
                    </div>

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name"
                            required>
                    </div>

                    <!-- Shift -->
                    <div class="mb-3">
                        <label for="shift" class="form-label">Shift</label>
                        <select class="form-select" id="shift" name="shift" required>
                            <option value="" selected disabled>Select your shift</option>
                            <option value="1st shift">1st Shift</option>
                            <option value="2nd shift">2nd Shift</option>
                            <option value="3rd shift">3rd Shift</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                    <div class="row">
                        <div class="d-grid col-12 col-md-6">
                            <a href="process_form.php" class="btn btn-secondary mt-3">View Submitted Records</a>
                        </div>
                        <div class="d-grid col-12 col-md-6">
                            <a href="../admin.php" class="btn btn-primary mt-3">View DMS Analytics</a>
                        </div>
                    </div>

                </form>
            </div>
            <div class="card-footer text-muted text-center">
                &copy; 2024 Sentinel OJT
            </div>

        </div>
    </div>

        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
