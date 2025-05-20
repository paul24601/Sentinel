<?php
require_once 'session_config.php';

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: ../login.html");
    exit();
}

// Check if record_id and format are provided
if (!isset($_GET['record_id']) || empty($_GET['record_id']) || !isset($_GET['format'])) {
    die("Missing required parameters");
}

$record_id = $_GET['record_id'];
$format = $_GET['format'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";
$dbname = "injectionmoldingparameters";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the record details
$sql = "SELECT * FROM parameter_records WHERE record_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $record_id);
$stmt->execute();
$recordData = $stmt->get_result()->fetch_assoc();

if (!$recordData) {
    die("Record not found");
}

// Function to fetch all data for a record
function fetchRecordData($conn, $table, $record_id) {
    $sql = "SELECT * FROM $table WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $record_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

// Fetch all data
$tables = [
    'productmachineinfo' => 'Product and Machine Information',
    'productdetails' => 'Product Details',
    'materialcomposition' => 'Material Composition',
    'colorantdetails' => 'Colorant Details',
    'moldoperationspecs' => 'Mold Operation Specifications',
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

$data = [];
foreach ($tables as $table => $title) {
    $data[$title] = fetchRecordData($conn, $table, $record_id);
}

// Fetch attachments
$sql = "SELECT * FROM attachments WHERE record_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $record_id);
$stmt->execute();
$attachments = [];
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $attachments[] = $row;
}
$data['Attachments'] = $attachments;

// Handle different export formats
switch ($format) {
    case 'pdf':
        require_once 'vendor/autoload.php'; // You'll need to install TCPDF via Composer
        
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Injection Molding Parameters System');
        $pdf->SetTitle($recordData['title']);
        
        // Add a page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 10);
        
        // Add title
        $pdf->Cell(0, 10, 'Injection Molding Parameters: ' . $recordData['title'], 0, 1, 'C');
        $pdf->Ln(10);
        
        // Add each section
        foreach ($data as $title => $rows) {
            if (!empty($rows)) {
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(0, 10, $title, 0, 1, 'L');
                $pdf->SetFont('helvetica', '', 10);
                
                // Create table
                $header = array_keys($rows[0]);
                $pdf->Table($header, $rows);
                $pdf->Ln(10);
            }
        }
        
        // Output PDF
        $pdf->Output($recordData['title'] . '.pdf', 'D');
        break;
        
    case 'excel':
        require_once 'vendor/autoload.php'; // You'll need to install PhpSpreadsheet via Composer
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $row = 1;
        foreach ($data as $title => $rows) {
            if (!empty($rows)) {
                // Add section title
                $sheet->setCellValue('A' . $row, $title);
                $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                $row++;
                
                // Add headers
                $col = 1;
                foreach (array_keys($rows[0]) as $header) {
                    $sheet->setCellValueByColumnAndRow($col, $row, $header);
                    $col++;
                }
                $row++;
                
                // Add data
                foreach ($rows as $dataRow) {
                    $col = 1;
                    foreach ($dataRow as $value) {
                        $sheet->setCellValueByColumnAndRow($col, $row, $value);
                        $col++;
                    }
                    $row++;
                }
                $row += 2; // Add space between sections
            }
        }
        
        // Output Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $recordData['title'] . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        break;
        
    case 'sql':
        // Generate SQL insert statements
        $sql_output = "-- Record: " . $recordData['title'] . "\n";
        $sql_output .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n";
        
        foreach ($data as $table => $rows) {
            if (!empty($rows)) {
                $sql_output .= "-- " . $table . "\n";
                foreach ($rows as $row) {
                    $columns = implode("`, `", array_keys($row));
                    $values = implode("', '", array_map(function($value) use ($conn) {
                        return $conn->real_escape_string($value);
                    }, $row));
                    $sql_output .= "INSERT INTO `" . strtolower(str_replace(' ', '', $table)) . "` (`" . $columns . "`) VALUES ('" . $values . "');\n";
                }
                $sql_output .= "\n";
            }
        }
        
        // Output SQL
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="' . $recordData['title'] . '.sql"');
        echo $sql_output;
        break;
        
    default:
        die("Invalid export format");
} 