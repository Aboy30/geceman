<?php
session_start();

if (!isset($_SESSION['user'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: login.php");
    exit;
}

// Koneksi database
$koneksi = new mysqli("localhost", "root", "", "geceman");

// Total traffic
$q_total = $koneksi->query("SELECT COUNT(*) AS total FROM pengiriman");
$total = $q_total->fetch_assoc()['total'];

// Barang masuk
$q_masuk = $koneksi->query("SELECT COUNT(*) AS masuk FROM pengiriman WHERE status = 'Menunggu'");
$masuk = $q_masuk->fetch_assoc()['masuk'];

// Barang dalam proses
$q_proses = $koneksi->query("SELECT COUNT(*) AS proses FROM pengiriman WHERE status = 'Proses'");
$proses = $q_proses->fetch_assoc()['proses'];

// Barang keluar (anggap status = 'Selesai' dianggap keluar)
$q_keluar = $koneksi->query("SELECT COUNT(*) AS keluar FROM pengiriman WHERE status = 'Selesai'");
$keluar = $q_keluar->fetch_assoc()['keluar'];

// Rata-rata harian 7 hari terakhir
$q_avg = $koneksi->query("SELECT COUNT(*) / 7 AS rata2 FROM pengiriman WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$rata2 = round($q_avg->fetch_assoc()['rata2']);

// Traffic minggu lalu
$q_lastweek = $koneksi->query("SELECT COUNT(*) AS jumlah FROM pengiriman WHERE tanggal BETWEEN DATE_SUB(CURDATE(), INTERVAL 14 DAY) AND DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$lastweek = $q_lastweek->fetch_assoc()['jumlah'];

// Persentase pertumbuhan total traffic
$growth_masuk = $lastweek > 0 ? round((($total - $lastweek) / $lastweek) * 100, 1) : 0;

// Persentase pertumbuhan barang keluar
$q_keluar_lastweek = $koneksi->query("SELECT COUNT(*) AS keluar FROM pengiriman WHERE status = 'Selesai' AND tanggal BETWEEN DATE_SUB(CURDATE(), INTERVAL 14 DAY) AND DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$keluar_lastweek = $q_keluar_lastweek->fetch_assoc()['keluar'];
$growth_keluar = $keluar_lastweek > 0 ? round((($keluar - $keluar_lastweek) / $keluar_lastweek) * 100, 1) : 0;

// Koneksi database
$koneksi = new mysqli("localhost", "root", "", "geceman");

// Jumlah data per halaman
$limit = 10;

// Halaman saat ini
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Ambil total data
$result_total = $koneksi->query("SELECT COUNT(*) AS total FROM pengiriman");
$total_rows = $result_total->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Ambil data sesuai halaman
$result = $koneksi->query("SELECT * FROM pengiriman ORDER BY id DESC LIMIT $start, $limit");

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$result = $koneksi->query("SELECT * FROM pengiriman ORDER BY id DESC LIMIT $start, $limit");


?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard GECEMAN</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #ffffffff;
    }

    .sidebar {
      background: #0c1b55;
      color: white;
      min-height: 100vh;
      padding: 1rem;
      transition: all 0.3s ease;
    }

    .sidebar h4 {
      font-weight: bold;
    }

    .sidebar ul {
      list-style: none;
      padding-left: 0;
    }

    .sidebar ul li {
      margin: 1rem 0;
    }

    .sidebar ul li a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 0.5rem 1rem;
      border-radius: 8px;
    }

    .sidebar ul li.active a,
    .sidebar ul li a:hover {
      background-color: #162663;
    }

    .content {
      padding: 1.5rem;
      transition: margin-left 0.3s ease;
    }

    .card-stat {
      background-color: #152065;
      color: white;
      text-align: center;
      padding: 1rem;
      border-radius: 10px;
    }

    .card-stat .text-success,
    .card-stat .text-danger,
    .card-stat .text-warning {
      font-size: 0.85rem;
    }

    .table-dark {
      background-color: #0c1b55;
    }

    .table-footer {
      background-color: #152065;
    }

    .btn-logout {
      position: absolute;
      bottom: 20px;
      width: 90%;
      left: 5%;
    }

    .open-btn {
      position: fixed;
      top: 10px;
      left: 10px;
      z-index: 1100;
    }

    /* Sidebar Responsive */
    @media (max-width: 767px) {
      .sidebar {
        position: fixed;
        left: -250px;
        top: 0;
        width: 250px;
        height: 100%;
        z-index: 1050;
      }

      .sidebar.active {
        left: 0;
      }

      .content {
        margin-left: 0 !important;
      }

      .close-btn {
        display: block;
        position: absolute;
        top: 10px;
        right: 10px;
        color: white;
        background: none;
        border: none;
      }
    }

    @media (min-width: 768px) {
      .sidebar {
        position: fixed;
        width: 250px;
        top: 0;
        left: 0;
      }

      .content {
        margin-left: 250px;
      }

      .close-btn {
        display: none;
      }

      .open-btn {
        display: none;
      }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="side_nav">
  <h4 class="mb-4">GECEMAN</h4>
  <ul>
    <li class="active"><a href="#"><i class="bi bi-house-door me-2"></i> Dashboard</a></li>
    <li><a href="resi.php"><i class="bi bi-receipt me-2"></i> Resi</a></li>
    <li><a href="statistik.php"><i class="bi bi-bar-chart me-2"></i> Statistik</a></li>
  </ul>
  <a href="logout.php" class="btn btn-danger btn-logout">
  <i class="bi bi-box-arrow-left"></i> Log Out
</a>

  <button class="close-btn d-md-none"><i class="bi bi-x-lg"></i></button>
</div>

<!-- Toggle Sidebar (Mobile) -->
<button class="btn btn-outline-primary open-btn d-md-none">
  <i class="bi bi-list"></i>
</button>

<!-- Main Content -->
<div class="content">
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4>Hello, Aboy</h4>
      <div class="text-end">
        <i class="bi bi-person-circle fs-3"></i>
        <strong>Aboy Pohan</strong><br>
        <small>aboy@gmail.com</small>
      </div>
    </div>

  <div class="row g-3">
  <div class="col-md-3">
    <div class="card-stat">
      <h5>Total Traffic</h5>
      <h2><?= number_format($total) ?></h2>
      <p class="<?= ($growth_masuk >= 0) ? 'text-success' : 'text-danger' ?>">
        <?= ($growth_masuk >= 0 ? '+' : '') . $growth_masuk ?>% Minggu Sebelumnya
      </p>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-stat border border-info border-2">
      <h5>Barang Masuk</h5>
      <h2><?= number_format($masuk) ?></h2>
      <p class="text-success"><?= $masuk ?> pengiriman aktif</p>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-stat">
      <h5>Barang Keluar</h5>
      <h2><?= number_format($keluar) ?></h2>
      <p class="<?= ($growth_keluar >= 0) ? 'text-success' : 'text-danger' ?>">
        <?= ($growth_keluar >= 0 ? '+' : '') . $growth_keluar ?>% Minggu Sebelumnya
      </p>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-stat">
      <h5>Rata-rata Harian</h5>
      <h2><?= number_format($rata2) ?></h2>
      <p class="text-warning">Target: 300/hari</p>
    </div>
  </div>
</div>



    <!-- Pengiriman Terbaru -->
    <div class="container mt-5">
  <h2>Data Pengiriman</h2>
  <?php

if (isset($_SESSION['success'])) {
  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'
      . $_SESSION['success'] .
      '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
  echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
      . $_SESSION['error'] .
      '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  unset($_SESSION['error']);
}
?>
  <button class="btn btn-success my-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
    <i class="bi bi-plus-circle"></i> Tambah Pengiriman
  </button>

  <table class="table table-bordered table-striped">
    <thead class="table-dark text-light fw-bold">
      <tr>
        <th>No</th>
        <th>No. Resi</th>
        <th>Nama Penerima</th>
        <th>Alamat</th>
        <th>Tanggal</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      include 'koneksi.php';
        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $start = ($page - 1) * $limit;
        $no = $start + 1; // Nomor urut mulai dari data ke-1 pada halaman ini

     $query = mysqli_query($koneksi, "SELECT * FROM pengiriman ORDER BY id DESC LIMIT $start, $limit");

      while($data = mysqli_fetch_array($query)){
      ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $data['no_resi'] ?></td>
        <td><?= $data['nama_penerima'] ?></td>
        <td><?= $data['alamat'] ?></td>
        <td><?= $data['tanggal'] ?></td>
        <td><?= $data['status'] ?></td>
        <td>
        <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['id'] ?>">
            <i class="bi bi-pencil-square"></i>
        </button>
         <!-- Modal Edit per baris -->
  <div class="modal fade" id="modalEdit<?= $data['id'] ?>" tabindex="-1" aria-labelledby="editLabel<?= $data['id'] ?>" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="edit.php" method="POST">
          <input type="hidden" name="id" value="<?= $data['id'] ?>">
          <div class="modal-header">
            <h5 class="modal-title" id="editLabel<?= $data['id'] ?>">Edit Pengiriman</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">No. Resi</label>
              <input type="text" name="no_resi" class="form-control" value="<?= $data['no_resi'] ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Nama Penerima</label>
              <input type="text" name="nama_penerima" class="form-control" value="<?= $data['nama_penerima'] ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Alamat</label>
              <input type="text" name="alamat" class="form-control" value="<?= $data['alamat'] ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Tanggal</label>
              <input type="date" name="tanggal" class="form-control" value="<?= $data['tanggal'] ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Status</label>
              <select name="status" class="form-control" required>
                <option <?= $data['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                <option <?= $data['status'] == 'Proses' ? 'selected' : '' ?>>Proses</option>
                <option <?= $data['status'] == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
          <a href="hapus.php?id=<?= $data['id'] ?>" onclick="return confirm('Hapus data ini?')" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
        </td>
      </tr>
      
      <?php } ?>
    </tbody>
  </table>
  <?php
// Hitung total data
$total_result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengiriman");
$total_row = mysqli_fetch_assoc($total_result);
$total_data = $total_row['total'];

// Hitung total halaman
$total_pages = ceil($total_data / $limit);
?>

<!-- Pagination -->
<nav>
  <ul class="pagination justify-content-center">
    <?php if ($page > 1): ?>
      <li class="page-item">
        <a class="page-link" href="?page=<?= $page - 1 ?>">Sebelumnya</a>
      </li>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
      <li class="page-item">
        <a class="page-link" href="?page=<?= $page + 1 ?>">Berikutnya</a>
      </li>
    <?php endif; ?>
  </ul>
</nav>


  
  
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="tambah.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Tambah Pengiriman</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">No. Resi</label>
            <input type="text" name="no_resi" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama Penerima</label>
            <input type="text" name="nama_penerima" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <input type="text" name="alamat" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>
              <option value="Selesai">Selesai</option>
              <option value="Proses">Proses</option>
              <option value="Menunggu">Menunggu</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

   

<!-- Scripts -->
<script>
  const openBtn = document.querySelector('.open-btn');
  const closeBtn = document.querySelector('.close-btn');
  const sidebar = document.getElementById('side_nav');

  openBtn?.addEventListener("click", () => {
    sidebar.classList.add("active");
  });

  closeBtn?.addEventListener("click", () => {
    sidebar.classList.remove("active");
  });

  // Active link
  document.querySelectorAll(".sidebar ul li").forEach(item => {
    item.addEventListener("click", function () {
      document.querySelector(".sidebar ul li.active")?.classList.remove("active");
      this.classList.add("active");
    });
  });
</script>

<!-- Bootstrap JS Bundle Wajib -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
