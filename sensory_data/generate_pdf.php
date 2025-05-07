<?php
require_once('tcpdf/tcpdf.php');

$conn = new mysqli("localhost", "root", "", "sensory_data");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$table = isset($_GET['table']) ? $_GET['table'] : 'production_cycle';
$sql = "SELECT * FROM $table ORDER BY timestamp DESC";
$result = $conn->query($sql);

$pdf = new TCPDF();
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage('L');
$pdf->SetFont('Helvetica', 'B', 16);
$pdf->Cell(0, 10, ucfirst(str_replace('_', ' ', $table)) . " Report", 0, 1, 'C');
$pdf->Ln(5);

// Table Headers
$pdf->SetFont('Helvetica', 'B', 10);
$pdf->SetFillColor(65, 118, 48);
$pdf->SetTextColor(255, 255, 255);

if ($table == "production_cycle") {
    $headers = ['ID', 'Cycle Time (s)', 'Recycle Time (s)', 'Pressure (g)', 'Temp_01 (°C)', 'Humidity (%)', 'Temp_02 (°C)', 'Timestamp'];
    $widths = [15, 30, 30, 30, 30, 30, 30, 45];
} else {
    $headers = ['ID', 'Temp_01 (°C)', 'Temp_01 (°F)', 'Humidity', 'Temp_02 (°C)', 'Temp_02 (°F)', 'Water Flow', 'Air Flow', 'Timestamp'];
    $widths = [10, 25, 25, 25, 25, 25, 25, 25, 40];
}

// Print headers
foreach ($headers as $i => $header) {
    $pdf->Cell($widths[$i], 10, $header, 1, 0, 'C', true);
}
$pdf->Ln();

// Reset text color
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Helvetica', '', 10);

// Table Data
while ($row = $result->fetch_assoc()) {
    $data = ($table == "production_cycle") ? 
        [$row['id'], $row['cycle_time'], $row['recycle_time'], $row['pressure'], $row['tempC_01'], $row['humidity'], $row['tempC_02'], $row['timestamp']] :
        [$row['id'], $row['tempC_01'], $row['tempF_01'], $row['humidity'], $row['tempC_02'], $row['tempF_02'], $row['water_flow'], $row['air_flow'], $row['timestamp']];
    foreach ($data as $i => $value) {
        $pdf->Cell($widths[$i], 8, $value, 1);
    }
    $pdf->Ln();
}

$conn->close();
$pdf->Output("{$table}.pdf", 'D');
?>
