<?php
session_start(); // Wajib ditambahkan

// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "geceman");

// Cek koneksi
if ($koneksi->connect_error) {
  die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil data dari form
$id = $_POST['id'];
$no_resi = $_POST['no_resi'];
$nama_penerima = $_POST['nama_penerima'];
$alamat = $_POST['alamat'];
$tanggal = $_POST['tanggal'];
$status = $_POST['status'];

// Update data ke database
$sql = "UPDATE pengiriman SET 
          no_resi='$no_resi',
          nama_penerima='$nama_penerima',
          alamat='$alamat',
          tanggal='$tanggal',
          status='$status'
        WHERE id='$id'";

if ($koneksi->query($sql) === TRUE) {
  // ✅ Simpan pesan sukses ke session
  $_SESSION['success'] = "Data pengiriman berhasil diperbarui.";
} else {
  // ✅ Simpan pesan error ke session
  $_SESSION['error'] = "Gagal memperbarui data: " . $koneksi->error;
}

// Redirect ke halaman utama
header("Location: dashboard.php");
exit();

$koneksi->close();
?>
