<?php
include 'koneksi.php';

$resi = isset($_GET['resi']) ? $_GET['resi'] : '';
$data = null;

if (!empty($resi)) {
    $query = mysqli_query($koneksi, "SELECT * FROM pengiriman WHERE no_resi = '$resi' LIMIT 1");
    $data = mysqli_fetch_assoc($query);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Pelacakan - GECEMAN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0c1b55;
            font-family: Arial, sans-serif;
        }
        .tracking-result {
            max-width: 700px;
            margin: 60px auto;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 35px;
            backdrop-filter: blur(12px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
            color: #fff;
        }
        .tracking-result h3 {
            margin-bottom: 25px;
            color: #fff;
        }

        .table {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff;
        }

        .table th, .table td {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.2);
        }

        .table th {
            font-weight: bold;
        }

        .badge.bg-info {
            background-color: #00e5ff !important;
            color: #000;
        }

        .btn-primary {
            background-color: #1a237e;
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #1a237e, #3949ab);
        }

        .alert-warning {
            background-color: rgba(255, 193, 7, 0.2);
            color: #fff;
            border: 1px solid rgba(255, 193, 7, 0.4);
        }
    </style>
</head>
<body>

<div class="container tracking-result">
    <h3>Hasil Pelacakan</h3>
    <?php if ($data): ?>
        <table class="table table-bordered">
            <tr>
                <th>No. Resi</th>
                <td><?= $data['no_resi'] ?></td>
            </tr>
            <tr>
                <th>Nama Penerima</th>
                <td><?= $data['nama_penerima'] ?></td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td><?= $data['alamat'] ?></td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td><?= $data['tanggal'] ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><span class="badge bg-info text-dark"><?= $data['status'] ?></span></td>
            </tr>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">Nomor resi <strong><?= htmlspecialchars($resi) ?></strong> tidak ditemukan.</div>
    <?php endif; ?>

    <a href="index.php" class="btn btn-primary mt-3">Kembali ke Beranda</a>
</div>

</body>
</html>
