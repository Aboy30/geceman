<?php
session_start(); // WAJIB: ini harus paling atas, sebelum output apa pun

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Contoh autentikasi sederhana
    if ($username === 'admin' && $password === '1234') {
        // Set session setelah login berhasil
        $_SESSION['user'] = $username;

        // Redirect ke dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Username atau password salah.";
    }
}
?>
