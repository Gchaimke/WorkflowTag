<?php
require(base_url('assets/fpdf/fpdf.php'));
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Hello World!');
$pdf->Output('I','flex.pdf');
?>