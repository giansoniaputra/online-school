<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FPDF;

class PDFController
{

    protected $pdf;


    public function __construct()
    {
        $this->pdf = new FPDF;
    }
    public function header()
    {
        //Header
        $this->pdf->SetFont('Arial', 'B', 18);
        $this->pdf->Cell(30);
        $this->pdf->Cell(140, 5, "SMP PERSIS GANDOK", 0, 1, 'C');

        $this->pdf->SetFont('Arial', 'B', 13);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(30);
        $this->pdf->Cell(140, 9, 'SEKOLAH MENENGAH PERTAMA', 0, 1, 'C');

        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(30);
        $this->pdf->Cell(140, 5, 'GANDOK', 0, 1, 'C');

        // Menambahkan garis header
        $this->pdf->SetLineWidth(1);
        $this->pdf->Line(10, 36, 200, 36);
        $this->pdf->SetLineWidth(0);
        $this->pdf->Line(10, 37, 200, 37);
        $this->pdf->Ln();
        //Header
    }

    public function generatePDF()
    {
        $this->pdf->AddPage();
        $this->pdf->SetFont('Arial', 'B', 16);
        $this->pdf->Cell(40, 10, 'Hello, World!');

        // Output to browser
        $pdfContent = $this->pdf->Output('', 'S'); // Get PDF content as string

        return response($pdfContent)->header('Content-Type', 'application/pdf');
    }

    public function getPDF()
    {
        $this->pdf = $this->generatePDF();
        return response($this->pdf)->header('Content-Type', 'application/pdf');
    }
}
