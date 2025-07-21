<?php
session_start(); // Wajib ditambahkan

// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "geceman";

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$no_resi       = $_POST['no_resi'];
$nama_penerima = $_POST['nama_penerima'];
$alamat        = $_POST['alamat'];
$tanggal       = $_POST['tanggal'];
$status        = $_POST['status'];

// Insert data ke tabel pengiriman
$sql = "INSERT INTO pengiriman (no_resi, nama_penerima, alamat, tanggal, status)
        VALUES ('$no_resi', '$nama_penerima', '$alamat', '$tanggal', '$status')";

if ($conn->query($sql) === TRUE) {
    // ✅ Simpan pesan sukses ke session
    $_SESSION['success'] = "Data pengiriman berhasil ditambahkan.";
} else {
    // ✅ Simpan pesan error ke session
    $_SESSION['error'] = "Gagal menambahkan data: " . $conn->error;
}

// Redirect kembali ke dashboard
header("Location: dashboard.php");
exit();

$conn->close();
?>