<?php
require 'vendor/autoload.php'; // Autoload FPDF via Composer
include 'koneksi.php';

use Fpdf\Fpdf; // Use the FPDF namespace

// Dapatkan kode booking dari URL
$unique_code = isset($_GET['code']) ? $_GET['code'] : '';

// Ambil detail booking berdasarkan kode unik
$stmt = $conn->prepare("SELECT * FROM booking WHERE unique_code = ?");
$stmt->bind_param("s", $unique_code);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if ($booking) {
    // Informasi booking
    $username = $booking['username'];
    $room = $booking['room'];
    $check_in = $booking['check_in'];
    $check_out = $booking['check_out'];
    $fasilitas = $booking['fasilitas'];
    $total_price = $booking['total_price'];

    // Membuat PDF invoice
    $pdf = new Fpdf();
    $pdf->AddPage();

    // Logo Hotel
    $pdf->Image('uploads/logo.png', 10, 10, 30); // Sesuaikan path logo
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'ALEXANDER', 0, 1, 'C'); // Nama hotel
    $pdf->Ln(10);

    // Judul Invoice
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Invoice Booking', 0, 1, 'C');
    $pdf->Ln(10);

    // Detail booking
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, "Detail Booking", 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Kode Booking: $unique_code", 0, 1);
    $pdf->Cell(0, 10, "Nama Pengguna: $username", 0, 1);
    $pdf->Cell(0, 10, "Nomor Kamar: $room", 0, 1);
    $pdf->Cell(0, 10, "Tanggal Check-in: " . date('d-m-Y', strtotime($check_in)), 0, 1);
    $pdf->Cell(0, 10, "Tanggal Check-out: " . date('d-m-Y', strtotime($check_out)), 0, 1);
    $pdf->Cell(0, 10, "Fasilitas: $fasilitas", 0, 1);
    
    // Garis pembatas sebelum total harga
    $pdf->Ln(5);
    $pdf->Cell(0, 0, '', 'T'); // Garis horisontal
    $pdf->Ln(5);
    
    // Total Harga
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, "Total Harga: Rp. " . number_format($total_price, 0, ',', '.'), 0, 1);
    $pdf->Ln(10);

    // Catatan tambahan
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Ini untuk tanda bukti saat anda checkin', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Terima kasih telah melakukan booking :}', 0, 1, 'C');

    // Output file PDF (unduh file)
    $pdf->Output('D', 'Invoice_' . $unique_code . '.pdf');
    
    // Pesan alert di halaman web
    echo "<script>alert('Invoice Anda telah terunduh. Silakan periksa folder unduhan Anda.');</script>";
} else {
    echo "Booking tidak ditemukan.";
}

$stmt->close();
$conn->close();
?>
