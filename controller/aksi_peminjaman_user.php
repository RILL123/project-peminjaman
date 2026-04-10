<?php
session_start();
include '../model/koneksi.php';
include '../model/log_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_buku = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
    $tanggal_pinjam = mysqli_real_escape_string($koneksi, $_POST['tanggal_pinjam']);
    $tanggal_kembali = mysqli_real_escape_string($koneksi, $_POST['tanggal_kembali']);
    $jumlah = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : 1;
    $id_user = $_SESSION['id_user'] ?? null;

    if ($id_user && $id_buku && $tanggal_pinjam && $tanggal_kembali && $jumlah > 0) {
        // Validasi: maksimal 3 hari pinjam
        $tgl_pinjam_dt = new DateTime($tanggal_pinjam);
        $tgl_kembali_dt = new DateTime($tanggal_kembali);
        $selisih_hari = $tgl_kembali_dt->diff($tgl_pinjam_dt)->days;

        if ($selisih_hari > 3) {
            $_SESSION['message'] = 'Peminjaman ditolak! Durasi pinjam maksimal adalah 3 hari, Anda meminta ' . $selisih_hari . ' hari.';
            $_SESSION['message_type'] = 'error';
        } else if ($selisih_hari < 1) {
            $_SESSION['message'] = 'Tanggal kembali harus lebih dari tanggal hari ini.';
            $_SESSION['message_type'] = 'error';
        } else {
            // Cek stok buku
            $stok_row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT stok, judul FROM buku WHERE id_buku = '$id_buku'"));
            $stok = (int)($stok_row['stok'] ?? 0);
            $judul_buku = $stok_row['judul'] ?? '';
            if ($jumlah > $stok) {
                $_SESSION['message'] = 'Jumlah pinjam melebihi stok buku!';
                $_SESSION['message_type'] = 'error';
            } else {
                // Insert ke request_peminjaman (pending approval)
                $q1 = "INSERT INTO request_peminjaman (id_user, id_buku, tanggal_pinjam, tanggal_kembali, jumlah, status) VALUES ('$id_user', '$id_buku', '$tanggal_pinjam', '$tanggal_kembali', '$jumlah', 'pending')";
                if (mysqli_query($koneksi, $q1)) {
                    tambah_log($koneksi, $id_user, 'Request Peminjaman', "Request pinjam $jumlah buku: $judul_buku selama $selisih_hari hari", $id_buku);
                    $_SESSION['message'] = 'Request peminjaman berhasil diajukan sebanyak ' . $jumlah . ' selama ' . $selisih_hari . ' hari. Silakan tunggu konfirmasi admin.';
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = 'Gagal mengajukan request peminjaman.';
                    $_SESSION['message_type'] = 'error';
                }
            }
        }
    } else {
        $_SESSION['message'] = 'Data tidak lengkap.';
        $_SESSION['message_type'] = 'error';
    }
    header('Location: ../view/users/landing.php');
    exit;
}