<?php
/**
 * DATATABLES IMPLEMENTATION AUDIT
 * This script identifies all tables and their current DataTables status
 */

$tables_found = [];
$files_to_check = [
    // Main files
    'index.php',
    'quality_control.php',
    'process_form.php',
    
    // DMS files
    'dms/index.php',
    'dms/submission.php',
    'dms/approval.php',
    'dms/analytics.php',
    'dms/declined_submissions.php',
    
    // Admin files
    'admin/users.php',
    'admin/product_parameters.php',
    'admin/password_reset_management.php',
    'admin/notifications.php',
    
    // Parameters files
    'parameters/index.php',
    'parameters/submission.php',
    'parameters/analytics.php',
    
    // Production report files
    'production_report/index.php',
    
    // Sensory data files
    'sensory_data/dashboard.php',
    'sensory_data/weights.php',
    'sensory_data/production_cycle.php',
    'sensory_data/motor_temperatures.php'
];

echo "<h1>DataTables Implementation Audit</h1>";
echo "<p>Scanning files for tables and their current DataTables status...</p>";

foreach ($files_to_check as $file) {
    $filepath = "c:\\xampp\\htdocs\\Sentinel\\$file";
    
    if (file_exists($filepath)) {
        $content = file_get_contents($filepath);
        
        // Find all tables with IDs
        preg_match_all('/<table[^>]*id=["\']([^"\']+)["\'][^>]*>/i', $content, $table_matches);
        
        // Find all tables without IDs
        preg_match_all('/<table(?![^>]*id=)[^>]*class=["\']([^"\']*table[^"\']*)["\'][^>]*>/i', $content, $no_id_matches);
        
        // Check for DataTables initialization
        $has_datatables = preg_match('/DataTable\s*\(/i', $content);
        $has_simple_datatables = preg_match('/simpleDatatables\.DataTable/i', $content);
        
        if (!empty($table_matches[1]) || !empty($no_id_matches[0])) {
            $tables_found[$file] = [
                'tables_with_id' => $table_matches[1] ?? [],
                'tables_without_id_count' => count($no_id_matches[0] ?? []),
                'has_datatables' => $has_datatables,
                'has_simple_datatables' => $has_simple_datatables,
                'datatables_status' => $has_datatables ? 'jQuery DataTables' : ($has_simple_datatables ? 'Simple DataTables' : 'None')
            ];
        }
    }
}

// Display results
echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
echo "<thead>";
echo "<tr style='background-color: #f0f0f0;'>";
echo "<th>File</th>";
echo "<th>Tables with ID</th>";
echo "<th>Tables without ID</th>";
echo "<th>DataTables Status</th>";
echo "<th>Action Needed</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

foreach ($tables_found as $file => $info) {
    echo "<tr>";
    echo "<td><strong>$file</strong></td>";
    echo "<td>";
    if (!empty($info['tables_with_id'])) {
        foreach ($info['tables_with_id'] as $table_id) {
            echo "• #$table_id<br>";
        }
    } else {
        echo "None";
    }
    echo "</td>";
    echo "<td>" . $info['tables_without_id_count'] . "</td>";
    echo "<td>" . $info['datatables_status'] . "</td>";
    echo "<td>";
    
    $actions = [];
    if ($info['tables_without_id_count'] > 0) {
        $actions[] = "Add IDs to tables";
    }
    if (!$info['has_datatables'] && !$info['has_simple_datatables']) {
        $actions[] = "Add DataTables";
    }
    if ($info['has_datatables'] || $info['has_simple_datatables']) {
        $actions[] = "Standardize configuration";
    }
    
    echo implode('<br>', $actions);
    echo "</td>";
    echo "</tr>";
}

echo "</tbody>";
echo "</table>";

echo "<h2>Summary</h2>";
echo "<p><strong>Total files with tables:</strong> " . count($tables_found) . "</p>";

$total_tables_with_id = 0;
$total_tables_without_id = 0;
$files_with_datatables = 0;

foreach ($tables_found as $info) {
    $total_tables_with_id += count($info['tables_with_id']);
    $total_tables_without_id += $info['tables_without_id_count'];
    if ($info['has_datatables'] || $info['has_simple_datatables']) {
        $files_with_datatables++;
    }
}

echo "<p><strong>Total tables with ID:</strong> $total_tables_with_id</p>";
echo "<p><strong>Total tables without ID:</strong> $total_tables_without_id</p>";
echo "<p><strong>Files with DataTables:</strong> $files_with_datatables</p>";

?>

<h2>Next Steps</h2>
<ol>
    <li><strong>Standardize all existing DataTables</strong> - Remove custom configurations and rely on universal system</li>
    <li><strong>Add IDs to tables without them</strong> - Required for DataTables initialization</li>
    <li><strong>Exclude form tables</strong> - Tables used for data input shouldn't have DataTables</li>
    <li><strong>Test all pages</strong> - Ensure DataTables work properly after standardization</li>
</ol>

<h2>Files that need immediate attention:</h2>
<ul>
    <?php
    foreach ($tables_found as $file => $info) {
        if ($info['tables_without_id_count'] > 0 || (!$info['has_datatables'] && !$info['has_simple_datatables'] && !empty($info['tables_with_id']))) {
            echo "<li><strong>$file</strong> - ";
            if ($info['tables_without_id_count'] > 0) {
                echo "Has " . $info['tables_without_id_count'] . " tables without IDs. ";
            }
            if (!$info['has_datatables'] && !$info['has_simple_datatables'] && !empty($info['tables_with_id'])) {
                echo "Has tables but no DataTables implementation.";
            }
            echo "</li>";
        }
    }
    ?>
</ul>
