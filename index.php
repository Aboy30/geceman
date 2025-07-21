<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>GECEMAN - Jasa Pengiriman Barang</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icon -->
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

        .track-form {
            margin-top: 30px;
        }

        .track-input {
            max-width: 500px;
            margin: 0 auto;
        }

        .features {
            background-color: #f8f9fa;
            padding: 40px 0;
            text-align: center;
        }

        .feature-box {
            padding: 20px;
        }

        .feature-icon {
            font-size: 40px;
            margin-bottom: 15px;
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
            <a class="navbar-brand" href="#">GECEMAN</a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#">Beranda</a></li>
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

            <!-- Lacak Form -->
            <form class="track-form mt-4" method="GET" action="lacak.php">
                <div class="input-group track-input">
                    <input type="text" class="form-control" name="resi" placeholder="Masukkan nomor resi" required>
                    <button class="btn btn-primary" type="submit">Lacak</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Fitur Section -->
    <section class="features">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 feature-box border-end">
                    <div class="feature-icon"><i class="bi bi-lightning-fill"></i></div>
                    <h5>Cepat</h5>
                </div>
                <div class="col-md-4 feature-box border-end">
                    <div class="feature-icon"><i class="bi bi-percent"></i></div>
                    <h5>Terjangkau</h5>
                </div>
                <div class="col-md-4 feature-box">
                    <div class="feature-icon"><i class="bi bi-clock-history"></i></div>
                    <h5>Real Time</h5>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
