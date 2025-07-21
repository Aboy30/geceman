<?php
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

        .hero {
            background-color: #1a237e;
            color: white;
            padding: 60px 20px;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
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
         .navbar-brand {
            font-weight: bold;
            color: #1a237e !important;
        }
        .btn-masuk {
            background: linear-gradient(45deg, #0d47a1, #536dfe); /* biru gelap ke biru neon */
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-masuk:hover {
            background: linear-gradient(45deg, #536dfe, #0d47a1); /* balikkan arah gradien */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.25);
            color: #ffffff;
        }

    </style>
</head>
<body>
 <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand" href="index.php">GECEMAN</a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="tracking.php">Lacak Kiriman</a></li>
                    <li class="nav-item">
                        <a class="btn btn-masuk" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>JASA PENGIRIMAN <br> BARANG</h1>
        </div>
    </section>
   <!-- Main Content -->
<div class="container m-5 d-flex justify-content-center">
    <div class="w-100" style="max-width: 500px;">
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
            $tracking_array = array_map('trim', explode(',', $status_tracking));
            foreach ($status_list as $status) {
                echo '<div class="status-item">';
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
</div>


    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
