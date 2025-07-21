<?php
session_start();

// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "geceman");

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil ID dari parameter GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query hapus data
    $sql = "DELETE FROM pengiriman WHERE id = '$id'";

    if ($koneksi->query($sql) === TRUE) {
        $_SESSION['success'] = "Data berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus data: " . $koneksi->error;
    }
} else {
    $_SESSION['error'] = "ID tidak ditemukan.";
}

// Kembali ke dashboard
header("Location: dashboard.php");
exit();

$koneksi->close();
?>
