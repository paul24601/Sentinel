/**
 * CENTRALIZED DATATABLES CONFIGURATION
 * Universal DataTables setup for all Sentinel tables
 * Maintains consistent styling, functionality, and performance
 */

// Default DataTables configuration
const DATATABLES_DEFAULT_CONFIG = {
    "pageLength": 10,
    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
    "responsive": true,
    "searching": true,
    "ordering": true,
    "paging": true,
    "info": true,
    "autoWidth": false,
    "stateSave": false,
    "language": {
        "search": "Search:",
        "lengthMenu": "Show _MENU_ entries",
        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
        "infoEmpty": "Showing 0 to 0 of 0 entries",
        "infoFiltered": "(filtered from _MAX_ total entries)",
        "paginate": {
            "first": "First",
            "last": "Last",
            "next": "Next",
            "previous": "Previous"
        },
        "emptyTable": "No data available in table",
        "zeroRecords": "No matching records found"
    },
    "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
           "<'row'<'col-sm-12'tr>>" +
           "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    "columnDefs": [
        {
            "targets": '_all',
            "className": 'text-center'
        }
    ]
};

// Enhanced configuration for admin tables
const DATATABLES_ADMIN_CONFIG = {
    ...DATATABLES_DEFAULT_CONFIG,
    "pageLength": 25,
    "order": [[0, 'desc']], // Default sort by first column descending
    "columnDefs": [
        {
            "targets": [0], // ID columns
            "width": "10%"
        },
        {
            "targets": '_all',
            "className": 'text-center'
        }
    ]
};

// Configuration for data tables with timestamps
const DATATABLES_TIMESTAMP_CONFIG = {
    ...DATATABLES_DEFAULT_CONFIG,
    "order": [[0, 'desc']], // Sort by timestamp/ID descending (newest first)
    "columnDefs": [
        {
            "targets": [0], // ID/timestamp columns
            "width": "15%"
        },
        {
            "targets": '_all',
            "className": 'text-center'
        }
    ]
};

// Configuration for analytics/reporting tables
const DATATABLES_ANALYTICS_CONFIG = {
    ...DATATABLES_DEFAULT_CONFIG,
    "pageLength": 50,
    "scrollX": true,
    "columnDefs": [
        {
            "targets": '_all',
            "className": 'text-center'
        }
    ],
    "buttons": [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
           "<'row'<'col-sm-12 col-md-6'B>>" +
           "<'row'<'col-sm-12'tr>>" +
           "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
};

/**
 * Initialize DataTables on all tables
 */
function initializeDataTables() {
    // Check if jQuery and DataTables are loaded
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. DataTables requires jQuery.');
        return;
    }
    
    if (typeof $.fn.DataTable === 'undefined') {
        console.error('DataTables is not loaded.');
        return;
    }

    // Auto-detect and initialize tables
    const tableSelectors = [
        // Admin tables
        '#usersTable',
        '#parametersTable',
        '#passwordResetTable',
        
        // DMS tables
        '#submissionTable',
        '#dataTable',
        '#pendingTable',
        '#otherTable',
        '#cycleTimeVarianceTable',
        '#remarksTable',
        
        // Production tables
        '#qualityTable',
        '#downtimeTable',
        
        // Dashboard tables
        '#datatablesSimple',
        
        // Test tables
        '#testTable',
        
        // Sensory data tables (special handling)
        '#sensorTable'
    ];

    // Initialize each table with appropriate configuration
    tableSelectors.forEach(selector => {
        const $table = $(selector);
        if ($table.length > 0 && !$.fn.DataTable.isDataTable(selector)) {
            let config = DATATABLES_DEFAULT_CONFIG;
            
            // Determine appropriate configuration based on table type
            if (selector.includes('users') || selector.includes('admin')) {
                config = DATATABLES_ADMIN_CONFIG;
            } else if (selector.includes('analytics') || selector.includes('variance') || selector.includes('remarks')) {
                config = DATATABLES_ANALYTICS_CONFIG;
            } else if (selector.includes('submission') || selector.includes('data')) {
                config = DATATABLES_TIMESTAMP_CONFIG;
            }
            
            try {
                $table.DataTable(config);
                console.log(`DataTable initialized for ${selector}`);
            } catch (error) {
                console.error(`Failed to initialize DataTable for ${selector}:`, error);
            }
        }
    });

    // Initialize any remaining tables with class 'table' that don't have DataTables
    $('table.table').each(function() {
        const $this = $(this);
        if (!$.fn.DataTable.isDataTable(this) && $this.find('thead').length > 0 && $this.find('tbody tr').length > 0) {
            try {
                $this.DataTable(DATATABLES_DEFAULT_CONFIG);
                console.log(`DataTable auto-initialized for table:`, this);
            } catch (error) {
                console.warn(`Could not auto-initialize DataTable:`, error);
            }
        }
    });

    // Apply Bootstrap styling to DataTables elements
    setTimeout(() => {
        $('.dataTables_filter input').addClass('form-control form-control-sm').attr('placeholder', 'Search...');
        $('.dataTables_length select').addClass('form-select form-select-sm');
        $('.dataTables_wrapper .row').addClass('g-2');
        
        // Apply consistent styling to pagination
        $('.dataTables_paginate .paginate_button').addClass('page-link');
        $('.dataTables_paginate .paginate_button.current').addClass('active');
    }, 100);
}

/**
 * Custom function to add DataTable to any table
 */
function addDataTableToElement(selector, config = null) {
    const $table = $(selector);
    if ($table.length > 0 && !$.fn.DataTable.isDataTable(selector)) {
        const finalConfig = config || DATATABLES_DEFAULT_CONFIG;
        try {
            $table.DataTable(finalConfig);
            console.log(`Custom DataTable initialized for ${selector}`);
            return true;
        } catch (error) {
            console.error(`Failed to initialize custom DataTable for ${selector}:`, error);
            return false;
        }
    }
    return false;
}

/**
 * Initialize when DOM is ready
 */
$(document).ready(function() {
    // Small delay to ensure all content is loaded
    setTimeout(initializeDataTables, 250);
});

// For pages that load content dynamically
$(window).on('load', function() {
    setTimeout(initializeDataTables, 500);
});

// Export for manual initialization
window.SentinelDataTables = {
    init: initializeDataTables,
    addToElement: addDataTableToElement,
    configs: {
        default: DATATABLES_DEFAULT_CONFIG,
        admin: DATATABLES_ADMIN_CONFIG,
        timestamp: DATATABLES_TIMESTAMP_CONFIG,
        analytics: DATATABLES_ANALYTICS_CONFIG
    }
};
