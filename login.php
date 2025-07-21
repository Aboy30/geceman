<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - GECEMAN</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
        }
        .navbar-brand {
            font-weight: bold;
            color: #1a237e !important;
        }
        .login-section {
            background-color: #1a237e;
            color: white;
            padding: 60px 20px;
        }
        .login-box {
            background-color: transparent;
            border: none;
        }
        .form-control {
            background-color: #f8f9fa;
        }
        .btn-login {
            background-color: #3f51b5;
            color: white;
        }
        .footer {
            height: 80px;
            background-color: #ffffff;
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Lacak Kiriman</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Section -->
    <section class="login-section text-center">
        <div class="container">
            <h2 class="mb-4">LOGIN</h2>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <form method="POST" action="login_process.php" class="login-box">
                        <div class="mb-3 text-start">
                            <label for="username" class="form-label text-white">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-4 text-start">
                            <label for="password" class="form-label text-white">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-login">Masuk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <div class="footer"></div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
