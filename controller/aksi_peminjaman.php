<?php
session_start();

// Pastikan user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ../../public/login.php');
    exit;
}

include '../model/koneksi.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? null;
    if ($aksi === 'tambah') {
        $id_user = mysqli_real_escape_string($koneksi, $_POST['id_user']);
        $id_buku = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
        $tanggal_pinjam = mysqli_real_escape_string($koneksi, $_POST['tanggal_pinjam']);
        $tanggal_kembali = mysqli_real_escape_string($koneksi, $_POST['tanggal_kembali']);
        // Insert ke tabel peminjaman
        $query1 = "INSERT INTO peminjaman (id_user, tanggal_pinjam, tanggal_kembali, status) VALUES ('$id_user', '$tanggal_pinjam', '$tanggal_kembali', 'pending')";
        if (mysqli_query($koneksi, $query1)) {
            $id_peminjaman = mysqli_insert_id($koneksi);
            // Insert ke detail_peminjaman
            $query2 = "INSERT INTO detail_peminjaman (id_peminjaman, id_buku) VALUES ('$id_peminjaman', '$id_buku')";
            if (mysqli_query($koneksi, $query2)) {
                    // Kurangi stok buku
                    $update_stok = "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$id_buku' AND stok > 0";
                    mysqli_query($koneksi, $update_stok);
                $_SESSION['message'] = 'Data peminjaman berhasil ditambahkan';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Gagal menambah detail peminjaman: ' . mysqli_error($koneksi);
                $_SESSION['message_type'] = 'error';
            }
        } else {
            $_SESSION['message'] = 'Gagal menambah peminjaman: ' . mysqli_error($koneksi);
            $_SESSION['message_type'] = 'error';
        }
    } else {
        $id_peminjaman = $_POST['id_peminjaman'] ?? null;
        $alasan = $_POST['alasan'] ?? '';
        if ($id_peminjaman && $aksi) {
            if ($aksi === 'approve') {
                $update_query = "UPDATE peminjaman SET status = 'approved' WHERE id_peminjaman = $id_peminjaman";
                $pesan = 'Peminjaman berhasil diterima';
                if (mysqli_query($koneksi, $update_query)) {
                    $_SESSION['message'] = $pesan;
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = 'Gagal memperbarui peminjaman: ' . mysqli_error($koneksi);
                    $_SESSION['message_type'] = 'error';
                }
            } else if ($aksi === 'reject') {
                // Hapus detail_peminjaman dulu
                $del_detail = mysqli_query($koneksi, "DELETE FROM detail_peminjaman WHERE id_peminjaman = $id_peminjaman");
                $del_peminjaman = mysqli_query($koneksi, "DELETE FROM peminjaman WHERE id_peminjaman = $id_peminjaman");
                if ($del_detail && $del_peminjaman) {
                    $_SESSION['message'] = 'Peminjaman berhasil ditolak & dihapus';
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = 'Gagal menghapus peminjaman: ' . mysqli_error($koneksi);
                    $_SESSION['message_type'] = 'error';
                }
            }
        } else {
            $_SESSION['message'] = 'Data tidak lengkap';
            $_SESSION['message_type'] = 'error';
        }
    }
}

header('Location: ../view/admin/transaksi.php');
mysqli_close($koneksi);
exit;
?>
