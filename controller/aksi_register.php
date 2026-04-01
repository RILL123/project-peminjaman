<?php

include '../model/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input tidak boleh kosong
    if (empty($nama) || empty($username) || empty($password) || empty($confirm_password)) {
        header('Location: ../public/register.php?error=Semua field harus diisi');
        exit;
    }

    // Validasi panjang username
    if (strlen($username) < 3) {
        header('Location: ../public/register.php?error=Username minimal 3 karakter');
        exit;
    }

    // Validasi panjang password
    if (strlen($password) < 3) {
        header('Location: ../public/register.php?error=Password minimal 3 karakter');
        exit;
    }

    // Validasi password dan confirm password harus sama
    if ($password !== $confirm_password) {
        header('Location: ../public/register.php?error=Password dan konfirmasi password tidak sesuai');
        exit;
    }

    // Cek apakah username sudah ada
    $check_username = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check_username) > 0) {
        header('Location: ../public/register.php?error=Username sudah terdaftar');
        exit;
    }

    // Insert user baru dengan role otomatis 'user'
    $query = "INSERT INTO users (nama, username, password, role) 
              VALUES ('$nama', '$username', '$password', 'user')";
    
    if (mysqli_query($koneksi, $query)) {
        header('Location: ../public/register.php?success=Akun berhasil dibuat');
        exit;
    } else {
        header('Location: ../public/register.php?error=Gagal membuat akun: ' . mysqli_error($koneksi));
        exit;
    }
} else {
    header('Location: ../public/register.php');
    exit;
}

mysqli_close($koneksi);
?>
