<?php
session_start();
include '../model/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = mysqli_real_escape_string($koneksi, $_POST['username']);
	$password = mysqli_real_escape_string($koneksi, $_POST['password']);

	// Cek apakah tabel admin ada
	$cek_tabel_admin = mysqli_query($koneksi, "SHOW TABLES LIKE 'admin'");
	if ($cek_tabel_admin && mysqli_num_rows($cek_tabel_admin) === 1) {
		$query_admin = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
		$result_admin = mysqli_query($koneksi, $query_admin);
		if ($result_admin && mysqli_num_rows($result_admin) === 1) {
			$data = mysqli_fetch_assoc($result_admin);
			$_SESSION['login'] = true;
			$_SESSION['username'] = $data['username'];
			$_SESSION['id_user'] = $data['id_admin']; // Pastikan kolom id_admin ada di tabel admin
			$_SESSION['role'] = 'admin';
			header('Location: ../view/admin/dashboard.php');
			exit;
		}
	}


	// Jika bukan admin, cek ke tabel users (jika tabel users ada)
	$cek_tabel_users = mysqli_query($koneksi, "SHOW TABLES LIKE 'users'");
	if ($cek_tabel_users && mysqli_num_rows($cek_tabel_users) === 1) {
		$query_users = "SELECT * FROM users WHERE username='$username' AND password='$password'";
		$result_users = mysqli_query($koneksi, $query_users);
		if ($result_users && mysqli_num_rows($result_users) === 1) {
			$data = mysqli_fetch_assoc($result_users);
			$_SESSION['login'] = true;
			$_SESSION['username'] = $data['username'];
			$_SESSION['id_user'] = $data['id_user']; // Pastikan kolom id_user ada di tabel users
			// Deteksi role dari kolom role jika ada, default ke 'user'
			$_SESSION['role'] = isset($data['role']) ? $data['role'] : 'user';
			if ($_SESSION['role'] === 'admin') {
				header('Location: ../view/admin/dashboard.php');
				exit;
			} else {
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
