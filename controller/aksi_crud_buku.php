<?php
include '../model/koneksi.php';
include '../model/log_helper.php';
session_start();

// Fungsi untuk membuat nama file dari judul buku
function createFileName($judul, $ext) {
	// Hapus karakter spesial, ganti spasi dengan underscore
	$clean = preg_replace('/[^a-zA-Z0-9\s]/', '', $judul);
	$clean = trim(preg_replace('/\s+/', '_', $clean));
	// Batasi panjang nama file
	$clean = substr($clean, 0, 50);
	return $clean . '.' . $ext;
}

// Tambah buku
if (isset($_POST['tambah'])) {
	$judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
	$penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
	$kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
	$tahun = mysqli_real_escape_string($koneksi, $_POST['tahun']);
	$stok = mysqli_real_escape_string($koneksi, $_POST['stok']);
	$cover = '';
	if (isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
		$ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
		$cover = createFileName($judul, $ext);
		move_uploaded_file($_FILES['cover']['tmp_name'], '../public/cover/' . $cover);
	}
	$query = "INSERT INTO buku (judul, penulis, kategori, tahun, stok, cover) VALUES ('$judul', '$penulis', '$kategori', '$tahun', '$stok', '$cover')";
	if (mysqli_query($koneksi, $query)) {
		$id_buku_baru = mysqli_insert_id($koneksi);
		$id_user = $_SESSION['id_user'] ?? null;
		if ($id_user) {
			tambah_log($koneksi, $id_user, 'Tambah Buku', "$judul", $id_buku_baru);
		}
	}
	$judul_url = urlencode($_POST['judul']);
	header("Location: ../view/admin/crud_buku.php?notif=added&judul=$judul_url");
	exit();
}

// Edit buku

if (isset($_POST['edit'])) {
	$id = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
	$judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
	$penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
	$kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
	$tahun = mysqli_real_escape_string($koneksi, $_POST['tahun']);
	$stok = mysqli_real_escape_string($koneksi, $_POST['stok']);
	$cover_sql = '';

	// Ambil cover lama
	$result = mysqli_query($koneksi, "SELECT cover FROM buku WHERE id_buku='$id'");
	$row = mysqli_fetch_assoc($result);
	$old_cover = ($row && isset($row['cover'])) ? $row['cover'] : '';

	if (isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
		// Hapus cover lama jika ada
		if (!empty($old_cover) && file_exists('../public/cover/' . $old_cover)) {
			unlink('../public/cover/' . $old_cover);
		}

		$ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
		$cover = createFileName($judul, $ext);
		move_uploaded_file($_FILES['cover']['tmp_name'], '../public/cover/' . $cover);
		$cover_sql = ", cover='$cover'";
	}

	$query = "UPDATE buku SET judul='$judul', penulis='$penulis', kategori='$kategori', tahun='$tahun', stok='$stok' $cover_sql WHERE id_buku='$id'";
	if (mysqli_query($koneksi, $query)) {
		$id_user = $_SESSION['id_user'] ?? null;
		if ($id_user) {
			tambah_log($koneksi, $id_user, 'Edit Buku', "$judul", $id);
		}
	}
	$judul_url = urlencode($_POST['judul']);
	header("Location: ../view/admin/crud_buku.php?notif=edited&judul=$judul_url");
	exit();
}

// Hapus buku
if (isset($_GET['hapus'])) {
	$id = mysqli_real_escape_string($koneksi, $_GET['hapus']);

	// Ambil cover dan judul dari database
	$result = mysqli_query($koneksi, "SELECT cover, judul FROM buku WHERE id_buku='$id'");
	$row = mysqli_fetch_assoc($result);
	
	if (!$row) {
		header("Location: ../view/admin/crud_buku.php?notif=error");
		exit();
	}
	
	$cover = $row['cover'] ?? '';
	$judul = $row['judul'] ?? 'Unknown';

	// Log aktivitas sebelum menghapus
	$id_user = $_SESSION['id_user'] ?? null;
	if ($id_user) {
		tambah_log($koneksi, $id_user, 'Hapus Buku', "$judul", $id);
	}

	// Hapus file cover jika ada
	if (!empty($cover) && file_exists('../public/cover/' . $cover)) {
		unlink('../public/cover/' . $cover);
	}

	// Hapus data dari database
	if (mysqli_query($koneksi, "DELETE FROM buku WHERE id_buku='$id'")) {
		$judul_url = urlencode($judul);
		header("Location: ../view/admin/crud_buku.php?notif=deleted&judul=$judul_url");
		exit();
	}
}
