<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$status_tracking = '';  // Default kosong

if (isset($_GET['resi'])) {
    $resi = mysqli_real_escape_string($koneksi, $_GET['resi']);
    $query = mysqli_query($koneksi, "SELECT status_tracking FROM pengiriman WHERE no_resi = '$resi'");

    if ($data = mysqli_fetch_assoc($query)) {
        $status_tracking = $data['status_tracking'];
    } else {
        $status_tracking = 'Not Found'; // biar tidak error
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lacak Kiriman - Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 200px;
            height: 100vh;
            background-color: #1a237e;
            color: white;
            padding-top: 30px;
            position: fixed;
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

        .main-content {
            margin-left: 200px;
            padding: 30px;
        }

        .tracking-box {
            background-color: #e0e0e0;
            padding: 20px;
            border-radius: 8px;
        }

        .status-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .status-icon {
            width: 30px;
            height: 30px;
            background-color: white;
            color: black;
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            margin-right: 15px;
            font-weight: bold;
        }

        input.form-control {
            background-color: #ddd;
            border: none;
        }

        button.btn-primary {
            background-color: #3f51b5;
            border: none;
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
     .btn-logout {
      position: absolute;
      bottom: 20px;
      width: 90%;
      left: 5%;
    }
    </style>
</head>
<body>

    <!-- Sidebar -->
<div class="sidebar" id="side_nav">
  <h4 class="mb-4">GECEMAN</h4>
  <ul>
    <li><a href="dashboard.php"><i class="bi bi-house-door me-2"></i> Dashboard</a></li>
    <li class="active"><a href="resi.php"><i class="bi bi-receipt me-2"></i> Resi</a></li>
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


        <!-- Input Resi -->
        <form class="d-flex mb-4" method="GET" action="">
            <input type="text" name="resi" class="form-control me-2" placeholder="xxxxxxxxxxxxxx">
            <button class="btn btn-primary">Lacak</button>
        </form>

        <!-- Box Status Kiriman -->
       
        <div class="tracking-box">
    <h5 class="mb-3">Lacak Kiriman</h5>

    <?php
    $status_list = ['Barang Diterima', 'Sedang Dikirim', 'Tiba Digudang', 'Dalam Perjalanan'];

    // Ubah status_tracking menjadi array berdasarkan koma
    $tracking_array = array_map('trim', explode(',', $status_tracking));

    foreach ($status_list as $status) {
        echo '<div class="status-item">';
        
        // Jika status saat ini ada di array tracking, tampilkan icon
        if (in_array($status, $tracking_array)) {
            echo '<div class="status-icon"><i class="bi bi-arrow-up"></i></div>';
        } else {
            echo '<div class="status-icon"></div>';
        }

        echo "<div>$status</div>";
        echo '</div>';
    }

    if ($status_tracking === 'Not Found' && isset($_GET['resi'])) {
        echo "<div class='text-danger mt-2'>Nomor resi <strong>" . htmlspecialchars($_GET['resi']) . "</strong> tidak ditemukan.</div>";
    }
?>

</div>

    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
