<?php
require('../libs/fpdf/fpdf.php'); 
include '../config.php';

if (!isset($_SESSION['user_id'])) {
    die("Akses tidak dibenarkan.");
}

$studentID = $_SESSION['user_id'];
$courseID = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

// 1. Tarik data Pelajar dan Kursus
$sql = "SELECT u.username, c.title, e.time_complete 
        FROM enroll_course e 
        JOIN user1 u ON e.studentID = u.id 
        JOIN courses c ON e.courseID = c.id 
        WHERE e.studentID = ? AND e.courseID = ? AND e.status = 'complete'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $studentID, $courseID);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Sijil tidak ditemui atau kursus belum tamat.");
}

// 2. Proses Menjana PDF Sijil (Portrait)
$pdf = new FPDF('P', 'mm', 'A4'); // 'P' untuk Portrait
$pdf->AddPage();

// --- BORDER ---
$pdf->SetLineWidth(2);
$pdf->Rect(10, 10, 190, 277); // Border luar (saiz A4 Portrait)
$pdf->SetLineWidth(0.5);
$pdf->Rect(15, 15, 180, 267); // Border dalam

// --- LOGO DI ATAS ---
// Parameter: Image(file, x, y, width)
// Letakkan logo di tengah: (210mm lebar kertas - 50mm lebar logo) / 2 = 80mm
$pdf->Image('../img/uthmLogo.png', 80, 25, 50); 

$pdf->Ln(40); // Jarakkan ruang selepas logo

// --- KANDUNGAN TEKS ---
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'UNIVERSITI TUN HUSSEIN ONN MALAYSIA', 0, 1, 'C');

$pdf->Ln(10);
$pdf->SetFont('Times', 'I', 28);
$pdf->SetTextColor(139, 92, 246); // Warna Violet Premium
$pdf->Cell(0, 20, 'Certificate of Completion', 0, 1, 'C');

$pdf->Ln(15);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(0, 10, 'This is to certify that', 0, 1, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 22);
$pdf->Cell(0, 15, strtoupper($data['username']), 0, 1, 'C');

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(0, 10, 'has successfully completed the course', 0, 1, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 18);
$pdf->SetTextColor(139, 92, 246);
$pdf->MultiCell(0, 10, $data['title'], 0, 'C'); // Gunakan MultiCell jika tajuk panjang

$pdf->Ln(20);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Issued on: ' . date('d F Y', strtotime($data['time_complete'])), 0, 1, 'C');

// --- TANDA TANGAN ---
$pdf->Ln(30);
$pdf->SetFont('Courier', 'I', 12);
$pdf->Cell(0, 10, '__________________________', 0, 1, 'C');
$pdf->Cell(0, 10, 'Dr. Noryusliza Abdullah', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 7, 'LMS UTHM Coordinator', 0, 1, 'C');

$pdf->Output('I', 'Certificate_' . $data['username'] . '.pdf'); // 'I' untuk buka dalam pelayar
?>