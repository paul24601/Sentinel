// Function to add a new row to the table
function addRow() {
    const table = document.getElementById('dataTable');
    const newRow = table.insertRow(-1); // row insertion sa end

    // Create editable cells
    for (let i = 0; i < 11; i++) {
        const newCell = newRow.insertCell(i);

        if (i === 0) { // drop down function sa machine column
            const select = document.createElement('select');
            const options = ['Select Machine', 'Machine A', 'Machine B', 'Machine C', 'Machine D']; // edit, lagay machine names later
            options.forEach(optionText => {
                const option = document.createElement('option');
                option.value = optionText;
                option.textContent = optionText;
                select.appendChild(option);
            });
            newCell.appendChild(select);
        } else {
            // gawing editable yung cells
            newCell.contentEditable = "true";
        }
    }
}

// delete last row sa table
function deleteRow() {
    const table = document.getElementById('dataTable');
    const rowCount = table.rows.length;

    // Check row count
    if (rowCount > 3) {
        table.deleteRow(rowCount - 1); // Deletes the last row
    } else {
        alert("Cannot delete all rows. At least one data row must remain.");
    }
}

// initialize rows pag nag load page
window.onload = function() {
    for (let i = 0; i < 20; i++) { // Adjust kung ilang rows kailangan
        addRow();
    }
    document.addEventListener('DOMContentLoaded', function () {
        const dateInput = document.getElementById('dateInput');
        
        dateInput.addEventListener('input', function () {
            // Remove any non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
    
};
