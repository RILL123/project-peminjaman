<?php
session_start();
include '../model/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'pinjam') {
    $id_buku = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
    $tanggal_kembali = mysqli_real_escape_string($koneksi, $_POST['tanggal_kembali']);
    $id_user = $_SESSION['id_user'] ?? null;
    $tanggal_pinjam = date('Y-m-d');
    if ($id_user && $id_buku && $tanggal_kembali) {
        $query1 = "INSERT INTO peminjaman (id_user, tanggal_pinjam, tanggal_kembali, status) VALUES ('$id_user', '$tanggal_pinjam', '$tanggal_kembali', 'pending')";
        if (mysqli_query($koneksi, $query1)) {
            $id_peminjaman = mysqli_insert_id($koneksi);
            $query2 = "INSERT INTO detail_peminjaman (id_peminjaman, id_buku) VALUES ('$id_peminjaman', '$id_buku')";
            if (mysqli_query($koneksi, $query2)) {
                $update_stok = "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$id_buku' AND stok > 0";
                mysqli_query($koneksi, $update_stok);
                $_SESSION['message'] = 'Peminjaman berhasil diajukan.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Gagal menambah detail peminjaman.';
                $_SESSION['message_type'] = 'error';
            }
        } else {
            $_SESSION['message'] = 'Gagal menambah peminjaman.';
            $_SESSION['message_type'] = 'error';
        }
    } else {
        $_SESSION['message'] = 'Data tidak lengkap.';
        $_SESSION['message_type'] = 'error';
    }
    header('Location: ../view/users/landing.php');
    exit;
}