<?php
session_start();
include 'koneksi.php';

// Cek apakah admin sudah log

// Proses balasan admin
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating_id = $_POST['rating_id'];
    $balasan_admin = $_POST['balasan_admin'];

    // Update balasan di database
    $sql = "UPDATE rating SET balasan_admin = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $balasan_admin, $rating_id);

    if ($stmt->execute()) {
        header("Location: ulasan_user.php?success=1");
    } else {
        echo "Gagal menyimpan balasan.";
    }
}
?>
