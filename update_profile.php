<?php
session_start();
require 'koneksi.php'; // Hubungkan ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir dengan nama variabel dalam bahasa Indonesia
    $nama = $_POST['nama'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $umur = $_POST['umur'];
    $alamat = $_POST['alamat'];

    // Cek apakah id disimpan di session
    if (!isset($_SESSION['id'])) {
        die("ID pengguna tidak valid. Silakan login ulang.");
    }

    // Update data ke tabel profile di database
    $stmt = $conn->prepare("UPDATE profile SET nama = ?, tanggal_lahir = ?, umur = ?, alamat = ? WHERE id = ?");

    // Eksekusi query
    if ($stmt->execute([$nama, $tanggal_lahir, $umur, $alamat, $_SESSION['id']])) {
        // Ambil data terbaru untuk menyimpan ke dalam session
        $stmt = $conn->prepare("SELECT * FROM profile WHERE id = ?");
        $stmt->execute([$_SESSION['id']]);
        $_SESSION['user_data'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Redirect ke halaman yang diinginkan setelah update
        header("Location: index.php");
        exit();
    } else {
        echo "Gagal memperbarui data. Silakan coba lagi.";
    }
}
?>

