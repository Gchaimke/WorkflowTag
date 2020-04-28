<?php
require(base_url('assets/fpdf/fpdf.php'));
$file = $_POST['pdf'];
if ($file) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(40, 10, 'Hello World!');
    $pdf->Output('F', $file);
    print $file;
}else{
    print "No file name.";
}
