<?php
require_once 'session_config.php';

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: ../login.html");
    exit();
}

// Load centralized database configuration
require_once __DIR__ . '/../includes/database.php';

// Get database connection
try {
    $conn = DatabaseManager::getConnection('sentinel_main');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get the export format
$format = isset($_GET['format']) ? $_GET['format'] : 'pdf';

// Fetch all records with their details
$sql = "SELECT * FROM parameter_records ORDER BY submission_date DESC";
$records = $conn->query($sql);

function getRecordDetails($conn, $recordId) {
    $tables = [
        'productmachineinfo' => 'Product Machine Info',
        'productdetails' => 'Product Details',
        'materialcomposition' => 'Material Composition',
        'colorantdetails' => 'Colorant Details',
        'moldoperationspecs' => 'Mold Operation Specs',
        'timerparameters' => 'Timer Parameters',
        'barrelheatertemperatures' => 'Barrel Heater Temperatures',
        'moldheatertemperatures' => 'Mold Heater Temperatures',
        'plasticizingparameters' => 'Plasticizing Parameters',
        'injectionparameters' => 'Injection Parameters',
        'ejectionparameters' => 'Ejection Parameters',
        'corepullsettings' => 'Core Pull Settings',
        'additionalinformation' => 'Additional Information',
        'personnel' => 'Personnel'
    ];

    $details = [];
    foreach ($tables as $table => $title) {
        $sql = "SELECT * FROM $table WHERE record_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $recordId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $details[$title] = $result->fetch_assoc();
        }
    }
    return $details;
}

switch ($format) {
    case 'pdf':
        require_once('tcpdf/tcpdf.php');
        
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Sentinel Digitization');
        $pdf->SetTitle('Parameters Export');
        
        // Set default header data
        $pdf->SetHeaderData('', 0, 'Parameters Export', 'Generated on ' . date('Y-m-d H:i:s'));
        
        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        
        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 15);
        
        // Add a page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 10);
        
        while ($record = $records->fetch_assoc()) {
            $details = getRecordDetails($conn, $record['record_id']);
            
            // Add record header
            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->Cell(0, 10, $record['title'], 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 5, 'Record ID: ' . $record['record_id'], 0, 1, 'L');
            $pdf->Cell(0, 5, 'Submitted by: ' . $record['submitted_by'], 0, 1, 'L');
            $pdf->Cell(0, 5, 'Date: ' . $record['submission_date'], 0, 1, 'L');
            $pdf->Ln(5);
            
            // Add details for each section
            foreach ($details as $title => $data) {
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(0, 10, $title, 0, 1, 'L');
                $pdf->SetFont('helvetica', '', 10);
                
                if (is_array($data)) {
                    foreach ($data as $key => $value) {
                        if ($key != 'record_id' && $key != 'id') {
                            $pdf->Cell(60, 5, ucwords(str_replace('_', ' ', $key)) . ':', 0, 0);
                            $pdf->Cell(0, 5, $value, 0, 1);
                        }
                    }
                }
                $pdf->Ln(5);
            }
            
            $pdf->Ln(10);
            $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
            $pdf->Ln(10);
        }
        
        // Output PDF
        $pdf->Output('parameters_export.pdf', 'D');
        break;
        
    case 'excel':
        require 'vendor/autoload.php';
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $row = 1;
        while ($record = $records->fetch_assoc()) {
            $details = getRecordDetails($conn, $record['record_id']);
            
            // Add record header
            $sheet->setCellValue('A' . $row, $record['title']);
            $sheet->mergeCells('A' . $row . ':D' . $row);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(14);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Record ID:');
            $sheet->setCellValue('B' . $row, $record['record_id']);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Submitted by:');
            $sheet->setCellValue('B' . $row, $record['submitted_by']);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Date:');
            $sheet->setCellValue('B' . $row, $record['submission_date']);
            $row += 2;
            
            // Add details for each section
            foreach ($details as $title => $data) {
                $sheet->setCellValue('A' . $row, $title);
                $sheet->mergeCells('A' . $row . ':D' . $row);
                $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                $row++;
                
                if (is_array($data)) {
                    foreach ($data as $key => $value) {
                        if ($key != 'record_id' && $key != 'id') {
                            $sheet->setCellValue('A' . $row, ucwords(str_replace('_', ' ', $key)));
                            $sheet->setCellValue('B' . $row, $value);
                            $row++;
                        }
                    }
                }
                $row++;
            }
            $row += 2;
        }
        
        // Auto-size columns
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Output Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="parameters_export.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        break;
        
    case 'sql':
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="parameters_export.sql"');
        
        echo "-- Parameters Export SQL\n";
        echo "-- Generated on " . date('Y-m-d H:i:s') . "\n\n";
        
        while ($record = $records->fetch_assoc()) {
            $details = getRecordDetails($conn, $record['record_id']);
            
            // Export main record
            echo "-- Record: " . $record['title'] . "\n";
            echo "INSERT INTO parameter_records (record_id, title, submitted_by, submission_date, status, description) VALUES (\n";
            echo "    '" . $record['record_id'] . "',\n";
            echo "    '" . addslashes($record['title']) . "',\n";
            echo "    '" . addslashes($record['submitted_by']) . "',\n";
            echo "    '" . $record['submission_date'] . "',\n";
            echo "    '" . $record['status'] . "',\n";
            echo "    '" . addslashes($record['description']) . "'\n";
            echo ");\n\n";
            
            // Export details for each table
            foreach ($details as $title => $data) {
                if (is_array($data)) {
                    $tableName = strtolower(str_replace(' ', '', $title));
                    $columns = array_keys($data);
                    $values = array_values($data);
                    
                    echo "INSERT INTO " . $tableName . " (" . implode(", ", $columns) . ") VALUES (\n";
                    echo "    '" . implode("',\n    '", array_map('addslashes', $values)) . "'\n";
                    echo ");\n\n";
                }
            }
            echo "\n";
        }
        break;
}

$conn->close();
?> 