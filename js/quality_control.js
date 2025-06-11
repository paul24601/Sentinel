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
                        alert('Form submitted successfully!');
                        location.reload();
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
}); 