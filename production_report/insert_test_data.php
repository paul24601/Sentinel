<?php
// Insert test data for production reports
$host = 'localhost';
$user = 'root';
$pass = 'injectionadmin123';
$db = 'dailymonitoringsheet';

try {
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    
    echo "Connected to database: $db\n";
    
    // Test data array
    $testData = [
        [
            'report_type' => 'finishing',
            'report_date' => '2024-03-15',
            'shift' => '1st-8hr',
            'shift_hours' => '06:00 - 14:00 (8 hours)',
            'product_name' => 'ASSY FOG LAMP LH TUNDRA',
            'color' => 'Black',
            'part_no' => 'FL-001-LH',
            'id_number1' => '123456',
            'id_number2' => '789012',
            'id_number3' => '345678',
            'ejo_number' => 'EJO-2024-001',
            'assembly_line' => 'Table 1',
            'machine' => null,
            'robot_arm' => null,
            'manpower' => 8,
            'reg' => '8',
            'ot' => '0',
            'total_reject' => 15,
            'total_good' => 385,
            'finishing_process' => 'Assembly',
            'station_number' => 'ST-001',
            'work_order' => 'WO-2024-001',
            'finishing_tools' => 'Screwdriver, Wrench Set, Torque Gun',
            'quality_standard' => 'ISO 9001:2015',
            'remarks' => 'Standard assembly process completed successfully',
            'created_by' => 'TEST001'
        ],
        [
            'report_type' => 'injection',
            'report_date' => '2024-03-16',
            'shift' => '2nd-8hr',
            'shift_hours' => '14:00 - 22:00 (8 hours)',
            'product_name' => 'BUMPER COVER FRONT',
            'color' => 'White',
            'part_no' => 'BC-002-FR',
            'id_number1' => '234567',
            'id_number2' => '890123',
            'id_number3' => '456789',
            'ejo_number' => 'EJO-2024-002',
            'assembly_line' => null,
            'machine' => 'IM-001',
            'robot_arm' => 'Yes',
            'manpower' => null,
            'reg' => '8',
            'ot' => '0',
            'total_reject' => 8,
            'total_good' => 392,
            'injection_pressure' => 85.5,
            'molding_temp' => 220,
            'cycle_time' => 45.2,
            'cavity_count' => 4,
            'cooling_time' => 15.8,
            'holding_pressure' => 65.3,
            'material_type' => 'ABS',
            'shot_size' => 28.5,
            'remarks' => 'Injection molding process with robot arm assistance',
            'created_by' => 'TEST002'
        ],
        [
            'report_type' => 'finishing',
            'report_date' => '2024-03-17',
            'shift' => '3rd-8hr',
            'shift_hours' => '22:00 - 06:00 (8 hours)',
            'product_name' => 'SIDE MIRROR HOUSING',
            'color' => 'Silver',
            'part_no' => 'SM-003-RH',
            'id_number1' => '345678',
            'id_number2' => '901234',
            'id_number3' => '567890',
            'ejo_number' => 'EJO-2024-003',
            'assembly_line' => 'Table 3',
            'machine' => null,
            'robot_arm' => null,
            'manpower' => 6,
            'reg' => '6',
            'ot' => '2',
            'total_reject' => 22,
            'total_good' => 278,
            'finishing_process' => 'Painting',
            'station_number' => 'ST-003',
            'work_order' => 'WO-2024-003',
            'finishing_tools' => 'Spray Gun, Compressor, Paint Booth',
            'quality_standard' => 'Automotive Paint Standard',
            'remarks' => 'Night shift painting operation with overtime',
            'created_by' => 'TEST003'
        ],
        [
            'report_type' => 'injection',
            'report_date' => '2024-03-18',
            'shift' => '1st-12hr',
            'shift_hours' => '06:00 - 18:00 (12 hours)',
            'product_name' => 'DASHBOARD PANEL',
            'color' => 'Black',
            'part_no' => 'DP-004-CT',
            'id_number1' => '456789',
            'id_number2' => '012345',
            'id_number3' => '678901',
            'ejo_number' => 'EJO-2024-004',
            'assembly_line' => null,
            'machine' => 'IM-002',
            'robot_arm' => 'No',
            'manpower' => null,
            'reg' => '12',
            'ot' => '0',
            'total_reject' => 12,
            'total_good' => 588,
            'injection_pressure' => 92.3,
            'molding_temp' => 240,
            'cycle_time' => 52.1,
            'cavity_count' => 2,
            'cooling_time' => 18.5,
            'holding_pressure' => 70.8,
            'material_type' => 'PP',
            'shot_size' => 45.2,
            'remarks' => '12-hour shift for large dashboard components',
            'created_by' => 'TEST004'
        ],
        [
            'report_type' => 'finishing',
            'report_date' => '2024-03-19',
            'shift' => '2nd-12hr',
            'shift_hours' => '18:00 - 06:00 (12 hours)',
            'product_name' => 'TAIL LIGHT ASSEMBLY',
            'color' => 'Red',
            'part_no' => 'TL-005-RH',
            'id_number1' => '567890',
            'id_number2' => '123456',
            'id_number3' => '789012',
            'ejo_number' => 'EJO-2024-005',
            'assembly_line' => 'Table 5',
            'machine' => null,
            'robot_arm' => null,
            'manpower' => 10,
            'reg' => '8',
            'ot' => '4',
            'total_reject' => 18,
            'total_good' => 582,
            'finishing_process' => 'Quality Check',
            'station_number' => 'ST-005',
            'work_order' => 'WO-2024-005',
            'finishing_tools' => 'Testing Equipment, Multimeter, Calibration Tools',
            'quality_standard' => 'Automotive Lighting Standard',
            'remarks' => 'Night shift quality control with extended overtime',
            'created_by' => 'TEST005'
        ]
    ];
    
    // Prepare SQL statement
    $sql = "INSERT INTO production_reports (
        report_type, report_date, shift, shift_hours, product_name, color, part_no,
        id_number1, id_number2, id_number3, ejo_number, assembly_line, machine, robot_arm,
        manpower, reg, ot, total_reject, total_good, injection_pressure, molding_temp,
        cycle_time, cavity_count, cooling_time, holding_pressure, material_type, shot_size,
        finishing_process, station_number, work_order, finishing_tools, quality_standard,
        remarks, created_by, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $insertedCount = 0;
    foreach ($testData as $data) {
        $stmt->bind_param("ssssssssssssssssiiidddiiddssssssss",
            $data['report_type'], $data['report_date'], $data['shift'], $data['shift_hours'],
            $data['product_name'], $data['color'], $data['part_no'], $data['id_number1'],
            $data['id_number2'], $data['id_number3'], $data['ejo_number'], $data['assembly_line'],
            $data['machine'], $data['robot_arm'], $data['manpower'], $data['reg'], $data['ot'],
            $data['total_reject'], $data['total_good'], $data['injection_pressure'],
            $data['molding_temp'], $data['cycle_time'], $data['cavity_count'], $data['cooling_time'],
            $data['holding_pressure'], $data['material_type'], $data['shot_size'],
            $data['finishing_process'], $data['station_number'], $data['work_order'],
            $data['finishing_tools'], $data['quality_standard'], $data['remarks'], $data['created_by']
        );
        
        if ($stmt->execute()) {
            $insertedCount++;
            echo "Inserted test record $insertedCount: {$data['product_name']} ({$data['report_type']})\n";
        } else {
            echo "Error inserting record: " . $stmt->error . "\n";
        }
    }
    
    echo "\nSuccessfully inserted $insertedCount test records!\n";
    echo "You can now view the records at: http://localhost/Sentinel/production_report/records.php\n";
    
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
