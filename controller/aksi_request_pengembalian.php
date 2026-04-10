<?php
session_start();
include '../model/koneksi.php';
include '../model/log_helper.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../public/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi'])) {
    $aksi = $_POST['aksi'];
    $id_request_kembali = mysqli_real_escape_string($koneksi, $_POST['id_request_kembali']);
    
    // Get request data
    $req_query = "SELECT rk.*, p.id_peminjaman, b.id_buku, b.judul, u.nama 
                  FROM request_pengembalian rk
                  JOIN peminjaman p ON rk.id_peminjaman = p.id_peminjaman
                  JOIN buku b ON rk.id_buku = b.id_buku
                  JOIN users u ON rk.id_user = u.id_user
                  WHERE rk.id_request_kembali = '$id_request_kembali'";
    
    $req = mysqli_fetch_assoc(mysqli_query($koneksi, $req_query));
    
    if (!$req) {
        $_SESSION['message'] = 'Request tidak ditemukan.';
        $_SESSION['message_type'] = 'error';
        header('Location: ../../view/admin/request_pengembalian.php');
        exit;
    }
    
    // Approve return request
    if ($aksi === 'approve') {
        $tanggal_approved = date('Y-m-d H:i:s');
        
        // Update request status to approved
        $update_query = "UPDATE request_pengembalian 
                        SET status = 'approved', tanggal_approved = '$tanggal_approved'
                        WHERE id_request_kembali = '$id_request_kembali'";
        
        if (mysqli_query($koneksi, $update_query)) {
            // Update book stock (add 1)
            mysqli_query($koneksi, "UPDATE buku SET stok = stok + 1 WHERE id_buku = '{$req['id_buku']}'");
            
            // Delete peminjaman and detail_peminjaman records
            mysqli_query($koneksi, "DELETE FROM detail_peminjaman WHERE id_peminjaman = '{$req['id_peminjaman']}'");
            mysqli_query($koneksi, "DELETE FROM peminjaman WHERE id_peminjaman = '{$req['id_peminjaman']}'");
            
            // Add log activity
            tambah_log($koneksi, $_SESSION['id_user'], 'Setujui Pengembalian', "Buku: {$req['judul']} dari {$req['nama']}", $req['id_buku']);
            
            $_SESSION['message'] = 'Pengembalian buku disetujui. Stok buku telah diperbarui.';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Gagal menyetujui pengembalian.';
            $_SESSION['message_type'] = 'error';
        }
    }
    
    // Reject return request
    elseif ($aksi === 'reject') {
        $update_query = "UPDATE request_pengembalian 
                        SET status = 'rejected', catatan_admin = NULL
                        WHERE id_request_kembali = '$id_request_kembali'";
        
        if (mysqli_query($koneksi, $update_query)) {
            // Add log activity
            tambah_log($koneksi, $_SESSION['id_user'], 'Tolak Pengembalian', "Buku: {$req['judul']} dari {$req['nama']}", $req['id_buku']);
            
            $_SESSION['message'] = 'Request pengembalian ditolak.';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Gagal menolak request pengembalian.';
            $_SESSION['message_type'] = 'error';
        }
    }
    
    header('Location: ../view/admin/request_pengembalian.php');
    exit;
}
?>
