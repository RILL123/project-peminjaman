<?php
session_start();
include '../model/koneksi.php';
include '../model/log_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'pinjam') {
    $id_buku = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
    $tanggal_kembali = mysqli_real_escape_string($koneksi, $_POST['tanggal_kembali']);
    $id_user = $_SESSION['id_user'] ?? null;
    $tanggal_request = date('Y-m-d');
    
    if ($id_user && $id_buku && $tanggal_kembali) {
        // Validasi: maksimal 3 hari pinjam
        $tanggal_pinjam = new DateTime($tanggal_request);
        $tanggal_kembali_dt = new DateTime($tanggal_kembali);
        $selisih_hari = $tanggal_kembali_dt->diff($tanggal_pinjam)->days;
        
        if ($selisih_hari > 3) {
            $_SESSION['message'] = 'Peminjaman ditolak! Durasi pinjam maksimal adalah 3 hari, Anda meminta ' . $selisih_hari . ' hari.';
            $_SESSION['message_type'] = 'error';
        } else if ($selisih_hari < 1) {
            $_SESSION['message'] = 'Tanggal kembali harus lebih dari tanggal hari ini.';
            $_SESSION['message_type'] = 'error';
        } else {
            $query = "INSERT INTO request_peminjaman (id_user, id_buku, tanggal_request, status) VALUES ('$id_user', '$id_buku', '$tanggal_request', 'pending')";
            if (mysqli_query($koneksi, $query)) {
                // Ambil data buku untuk log
                $buku_data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT judul FROM buku WHERE id_buku = '$id_buku'"));
                
                // Tambah log aktivitas
                tambah_log($koneksi, $id_user, 'Request Peminjaman', "Selama $selisih_hari hari", $id_buku);
                
                $_SESSION['message'] = 'Request peminjaman berhasil diajukan selama ' . $selisih_hari . ' hari. Silakan tunggu konfirmasi admin.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Gagal mengajukan peminjaman.';
                $_SESSION['message_type'] = 'error';
            }
        }
    } else {
        $_SESSION['message'] = 'Data tidak lengkap.';
        $_SESSION['message_type'] = 'error';
    }
    header('Location: ../../view/users/landing.php');
    exit;
}