<?php
include 'koneksi.php'; // Pastikan koneksi diambil dari file koneksi.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari formulir dan memfilter input
    $nama = trim($_POST['nama']);
    $rating = intval($_POST['rating']); // Mengubah rating menjadi integer
    $komentar = trim($_POST['komentar']);
    $balasan_admin = null; // Nilai default untuk balasan_admin

    // Memastikan semua field diisi
    if (!empty($nama) && !empty($rating) && !empty($komentar)) {
        // Siapkan statement untuk menghindari SQL injection
        $stmt = $conn->prepare("INSERT INTO rating (nama, rating, komentar, balasan_admin) VALUES (?, ?, ?, ?)");
        
        if ($stmt) {
            // Bind parameter
            $stmt->bind_param("siss", $nama, $rating, $komentar, $balasan_admin); // 's' untuk string, 'i' untuk integer

            // Eksekusi statement
            if ($stmt->execute()) {
                // Redirect ke about.php dengan parameter success
                header("Location: about.php?success=1");
                exit();
            } else {
                echo "Error: " . $stmt->error; // Debugging error
            }

            // Tutup statement
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error; // Jika ada kesalahan dalam persiapan statement
        }
    } else {
        echo "Harap isi semua field!"; // Tambahkan pesan jika field kosong
    }
}

$conn->close();
?>

