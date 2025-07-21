<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Koneksi database - WAJIB DULUAN
$koneksi = new mysqli("localhost", "root", "", "geceman");

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Query chart status
$status_labels = [];
$status_counts = [];

$q_chart = $koneksi->query("SELECT status, COUNT(*) as jumlah FROM pengiriman GROUP BY status");
while ($row = $q_chart->fetch_assoc()) {
    $status_labels[] = $row['status'];
    $status_counts[] = $row['jumlah'];
}
// Total traffic
$q_total = $koneksi->query("SELECT COUNT(*) AS total FROM pengiriman");
$total = (int) $q_total->fetch_assoc()['total'];

// Barang masuk
$q_masuk = $koneksi->query("SELECT COUNT(*) AS masuk FROM pengiriman WHERE status = 'Menunggu'");
$masuk = (int) $q_masuk->fetch_assoc()['masuk'];

// Barang keluar
$q_keluar = $koneksi->query("SELECT COUNT(*) AS keluar FROM pengiriman WHERE status = 'Selesai'");
$keluar = (int) $q_keluar->fetch_assoc()['keluar'];

// Rata-rata harian 7 hari terakhir
$q_avg = $koneksi->query("SELECT COUNT(*) / 7 AS rata2 FROM pengiriman WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$rata2 = round((float) $q_avg->fetch_assoc()['rata2'], 2);

// Total minggu lalu
$q_lastweek = $koneksi->query("SELECT COUNT(*) AS jumlah FROM pengiriman WHERE tanggal BETWEEN DATE_SUB(CURDATE(), INTERVAL 14 DAY) AND DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$lastweek = (int) $q_lastweek->fetch_assoc()['jumlah'];

// Pertumbuhan total
$growth_masuk = $lastweek > 0 ? round((($total - $lastweek) / $lastweek) * 100, 1) : 0;

// Barang keluar minggu lalu
$q_keluar_lastweek = $koneksi->query("SELECT COUNT(*) AS keluar FROM pengiriman WHERE status = 'Selesai' AND tanggal BETWEEN DATE_SUB(CURDATE(), INTERVAL 14 DAY) AND DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$keluar_lastweek = (int) $q_keluar_lastweek->fetch_assoc()['keluar'];

// Pertumbuhan barang keluar
$growth_keluar = $keluar_lastweek > 0 ? round((($keluar - $keluar_lastweek) / $keluar_lastweek) * 100, 1) : 0;

// PAGINATION
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$result_total = $koneksi->query("SELECT COUNT(*) AS total FROM pengiriman");
$total_rows = $result_total->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

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
      background-color: #f5f5f5;
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
    <li><a href="dashboard.php"><i class="bi bi-house-door me-2"></i> Dashboard</a></li>
    <li><a href="resi.php"><i class="bi bi-receipt me-2"></i> Resi</a></li>
    <li class="active"><a href="statistik.php"><i class="bi bi-bar-chart me-2"></i> Statistik</a></li>
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

<div class="container my-4">
  <h4 class="text-center mb-4">Statistik Pengiriman per Status</h4>
  <canvas id="statusLineChart" width="400" height="200"></canvas>
</div>



    
   
<!-- Load Chart.js dari CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Scripts -->
<script>
  const openBtn = document.querySelector('.open-btn');
  const closeBtn = document.querySelector('.close-btn');
  const sidebar = document.getElementById('side_nav');
  const statusLabels = <?php echo json_encode($status_labels); ?>;
    const statusCounts = <?php echo json_encode($status_counts); ?>;

    const ctx = document.getElementById('statusLineChart').getContext('2d');
    const statusLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: statusLabels,
            datasets: [{
                label: 'Status Pengiriman',
                data: statusCounts,
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.4,
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true
                },
                tooltip: {
                    enabled: true
                }
            }
        }
    });

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
