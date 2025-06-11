$(document).ready(function() {
    // Function to calculate row total
    function calculateRowTotal(row) {
        let total = 0;
        $(row).find('.time-input').each(function() {
            const value = parseInt($(this).val()) || 0;
            total += value;
        });
        $(row).find('.total').val(total);
        calculateTotals();
    }

    // Function to calculate overall totals
    function calculateTotals() {
        let totalRejectSum = 0;
        $('#qualityTableBody tr').each(function() {
            const total = parseInt($(this).find('.total').val()) || 0;
            totalRejectSum += total;
        });
        $('#totalRejectSum').val(totalRejectSum);
        
        // Calculate total good parts
        const totalGood = parseInt($('#totalGood').val()) || 0;
        $('#totalGoodSum').val(totalGood);
    }

    // Add new quality control row
    $('#addRow').click(function() {
        const newRow = `
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
        `;
        $('#qualityTableBody').append(newRow);
    });

    // Add new downtime row
    $('#addDowntimeRow').click(function() {
        const newRow = `
            <tr>
                <td><input type="text" class="form-control" name="downtimeDesc[]"></td>
                <td><input type="number" class="form-control downtime-minutes" name="downtimeMinutes[]"></td>
            </tr>
        `;
        $('#downtimeTableBody').append(newRow);
    });

    // Calculate row total when time inputs change
    $(document).on('input', '.time-input', function() {
        calculateRowTotal($(this).closest('tr'));
    });

    // Update total good sum when total good input changes
    $(document).on('input', '#totalGood', function() {
        calculateTotals();
    });

    // Form submission handling
    $('#qualityControlForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate required fields
        let isValid = true;
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            alert('Please fill in all required fields');
            return;
        }

        // Collect form data
        const formData = new FormData(this);

        // Submit form via AJAX
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    const result = JSON.parse(response);
                    if (result.success) {
                        alert('Production report submitted successfully!');
                        window.location.href = 'view.php?id=' + result.report_id;
                    } else {
                        alert('Error: ' + result.message);
                    }
                } catch (e) {
                    alert('Error submitting form. Please try again.');
                }
            },
            error: function() {
                alert('Error submitting form. Please try again.');
            }
        });
    });

    // Auto-fill current date
    $('#date').val(new Date().toISOString().split('T')[0]);

    // Initialize calculations for existing rows
    $('#qualityTableBody tr').each(function() {
        calculateRowTotal(this);
    });

    // Auto-fill test data function
    $('#autoFillTest').click(function() {
        // Fill header information
        $('#plant').val('Plant A');
        $('#date').val(new Date().toISOString().split('T')[0]);
        $('#shift').val('Morning');
        $('#shiftHours').val('6:00 AM - 2:00 PM');

        // Fill product information
        $('#productName').val('Test Product XYZ');
        $('#color').val('Black');
        $('#partNo').val('TP-123-456');

        // Fill ID numbers
        $('#idNumber1').val('ID001');
        $('#idNumber2').val('ID002');
        $('#idNumber3').val('ID003');
        $('#ejo').val('EJO-2024-001');

        // Fill assembly line info
        $('#assemblyLine').val('Line-01');
        $('#manpower').val('5');
        $('#reg').val('8');
        $('#ot').val('2');

        // Clear existing quality rows except the first one
        $('#qualityTableBody tr:gt(0)').remove();

        // Fill first quality row
        const qualityRows = [
            { part: 'Part A', defect: 'Scratch', times: [2, 1, 3, 0, 1, 2, 0, 1] },
            { part: 'Part B', defect: 'Dent', times: [1, 2, 0, 1, 0, 1, 1, 0] },
            { part: 'Part C', defect: 'Color Mismatch', times: [0, 1, 1, 2, 0, 0, 1, 1] }
        ];

        // Fill first row
        fillQualityRow($('#qualityTableBody tr:first'), qualityRows[0]);

        // Add and fill additional rows
        for (let i = 1; i < qualityRows.length; i++) {
            $('#addRow').click();
            fillQualityRow($('#qualityTableBody tr:last'), qualityRows[i]);
        }

        // Clear existing downtime rows except the first one
        $('#downtimeTableBody tr:gt(0)').remove();

        // Fill downtime entries
        const downtimeEntries = [
            { desc: 'Machine Maintenance', minutes: 30 },
            { desc: 'Material Change', minutes: 15 },
            { desc: 'Technical Issue', minutes: 45 }
        ];

        // Fill first downtime row
        fillDowntimeRow($('#downtimeTableBody tr:first'), downtimeEntries[0]);

        // Add and fill additional downtime rows
        for (let i = 1; i < downtimeEntries.length; i++) {
            $('#addDowntimeRow').click();
            fillDowntimeRow($('#downtimeTableBody tr:last'), downtimeEntries[i]);
        }

        // Fill total good
        $('#totalGood').val('95').trigger('input');

        // Fill remarks
        $('#remarks').val('Test data for quality control system testing. Generated on ' + new Date().toLocaleString());

        // Helper function to fill quality row
        function fillQualityRow(row, data) {
            $(row).find('input[name="partName[]"]').val(data.part);
            $(row).find('input[name="defect[]"]').val(data.defect);
            
            data.times.forEach((value, index) => {
                $(row).find(`input[name="time${index + 1}[]"]`).val(value).trigger('input');
            });
        }

        // Helper function to fill downtime row
        function fillDowntimeRow(row, data) {
            $(row).find('input[name="downtimeDesc[]"]').val(data.desc);
            $(row).find('input[name="downtimeMinutes[]"]').val(data.minutes);
        }

        // Recalculate all totals
        $('#qualityTableBody tr').each(function() {
            calculateRowTotal(this);
        });
    });

    // Prevent form submission on enter key
    $(window).keydown(function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
}); 