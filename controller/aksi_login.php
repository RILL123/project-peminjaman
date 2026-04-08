<?php
session_start();
include '../model/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = mysqli_real_escape_string($koneksi, $_POST['username']);
	$password = mysqli_real_escape_string($koneksi, $_POST['password']);

	// Jika bukan admin, cek ke tabel users (jika tabel users ada)
	$cek_tabel_users = mysqli_query($koneksi, "SHOW TABLES LIKE 'users'");
	if ($cek_tabel_users && mysqli_num_rows($cek_tabel_users) === 1) {
		$query_users = "SELECT * FROM users WHERE username='$username' AND password='$password'";
		$result_users = mysqli_query($koneksi, $query_users);
		if ($result_users && mysqli_num_rows($result_users) === 1) {
			$data = mysqli_fetch_assoc($result_users);
			$_SESSION['login'] = true;
			$_SESSION['username'] = $data['username'];
			$_SESSION['id_user'] = $data['id_user'];
			// Deteksi role dari kolom role jika ada, default ke 'user'
			$_SESSION['role'] = isset($data['role']) ? $data['role'] : 'user';
			include_once '../model/log_helper.php';
			
			// Catat log sesuai dengan role
			if ($_SESSION['role'] === 'admin') {
				tambah_log($koneksi, $data['id_user'], 'Login Admin', 'Admin login ke sistem');
				header('Location: ../view/admin/dashboard.php');
				exit;
			} else {
				tambah_log($koneksi, $data['id_user'], 'Login User', 'User login ke sistem');
				header('Location: ../view/users/landing.php');
				exit;
			}
		}
	} else {
		header('Location: ../public/login.php?error=Database error: tabel users tidak ditemukan!');
		exit;
	}

	header('Location: ../public/login.php?error=Username atau password salah!');
	exit;
} else {
	header('Location: ../public/login.php');
	exit;
}