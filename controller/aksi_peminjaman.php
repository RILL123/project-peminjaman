<?php
session_start();

// Pastikan user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ../../public/login.php');
    exit;
}

include '../../model/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_peminjaman = $_POST['id_peminjaman'] ?? null;
    $aksi = $_POST['aksi'] ?? null;
    $alasan = $_POST['alasan'] ?? '';

    if ($id_peminjaman && $aksi) {
        if ($aksi === 'approve') {
            $update_query = "UPDATE peminjaman SET status = 'approved' WHERE id_peminjaman = $id_peminjaman";
            $pesan = 'Peminjaman berhasil diterima';
        } else if ($aksi === 'reject') {
            // Escape input untuk keamanan
            $alasan = mysqli_real_escape_string($koneksi, $alasan);
            $update_query = "UPDATE peminjaman SET status = 'rejected', alasan_penolakan = '$alasan' WHERE id_peminjaman = $id_peminjaman";
            $pesan = 'Peminjaman berhasil ditolak';
        }

        if (isset($update_query) && mysqli_query($koneksi, $update_query)) {
            $_SESSION['message'] = $pesan;
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Gagal memperbarui peminjaman: ' . mysqli_error($koneksi);
            $_SESSION['message_type'] = 'error';
        }
    } else {
        $_SESSION['message'] = 'Data tidak lengkap';
        $_SESSION['message_type'] = 'error';
    }
}

header('Location: ../admin/peminjaman.php');
mysqli_close($koneksi);
exit;
?>
