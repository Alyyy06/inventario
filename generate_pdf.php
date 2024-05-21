<?php
require('fpdf.php'); // Asegúrate de tener el archivo fpdf.php en el mismo directorio

// Crear una instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Configurar la fuente y tamaño del texto
$pdf->SetFont('Arial', 'B', 16);

// Título del reporte
$pdf->Cell(0, 10, 'Reporte de Materiales', 0, 1, 'C');

// Añadir espacio en blanco
$pdf->Ln(10);

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Nombre', 1);
$pdf->Cell(40, 10, 'Cantidad Inicial', 1);
$pdf->Cell(40, 10, 'Unidad de Medida', 1);
$pdf->Cell(40, 10, 'Cantidad Disponible', 1);
$pdf->Ln();

// Contenido de la tabla (los datos de los materiales)
$pdf->SetFont('Arial', '', 12);
foreach ($data as $item) {
    $pdf->Cell(40, 10, $item['nombre'], 1);
    $pdf->Cell(40, 10, $item['cantidad_inicial'], 1);
    $pdf->Cell(40, 10, $item['unidad_medida'], 1);
    $pdf->Cell(40, 10, $item['cantidad_restante'], 1);
    $pdf->Ln();
}

// Salida del PDF
$pdf->Output();
?>
