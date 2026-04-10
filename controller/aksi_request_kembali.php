<?php
session_start();
include '../model/koneksi.php';
include '../model/log_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'request_kembali') {
    $id_peminjaman = mysqli_real_escape_string($koneksi, $_POST['id_peminjaman']);
    $alasan_kembali = isset($_POST['alasan_kembali']) ? mysqli_real_escape_string($koneksi, $_POST['alasan_kembali']) : '';
    $id_user = $_SESSION['id_user'] ?? null;
    $tanggal_request = date('Y-m-d H:i:s');
    
    if ($id_user && $id_peminjaman) {
        // Check if peminjaman exists and belongs to user
        $check_query = "SELECT p.id_peminjaman, p.id_user, b.judul, dp.id_buku 
                        FROM peminjaman p
                        JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
                        JOIN buku b ON dp.id_buku = b.id_buku
                        WHERE p.id_peminjaman = '$id_peminjaman' AND p.id_user = '$id_user'";
        
        $check = mysqli_query($koneksi, $check_query);
        
        if (mysqli_num_rows($check) > 0) {
            $peminjaman_data = mysqli_fetch_assoc($check);
            $id_buku = $peminjaman_data['id_buku'];
            $judul_buku = $peminjaman_data['judul'];
            
            // Check if request already pending
            $check_existing = mysqli_query($koneksi, "SELECT id_request_kembali FROM request_pengembalian 
                                                      WHERE id_peminjaman = '$id_peminjaman' AND status = 'pending'");
            
            if (mysqli_num_rows($check_existing) > 0) {
                $_SESSION['message'] = 'Anda sudah memiliki request pengembalian yang pending untuk buku ini.';
                $_SESSION['message_type'] = 'error';
            } else {
                // Insert request_return record
                $insert_query = "INSERT INTO request_pengembalian (id_peminjaman, id_user, id_buku, tanggal_request, status)
                                VALUES ('$id_peminjaman', '$id_user', '$id_buku', '$tanggal_request', 'pending')";
                
                if (mysqli_query($koneksi, $insert_query)) {
                    // Add log activity
                    tambah_log($koneksi, $id_user, 'Request Pengembalian', "Meminta pengembalian buku: $judul_buku", $id_buku);
                    
                    $_SESSION['message'] = 'Request pengembalian berhasil diajukan. Silakan tunggu konfirmasi admin.';
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = 'Gagal mengajukan request pengembalian: ' . mysqli_error($koneksi);
                    $_SESSION['message_type'] = 'error';
                }
            }
        } else {
            $_SESSION['message'] = 'Data peminjaman tidak ditemukan atau tidak sesuai.';
            $_SESSION['message_type'] = 'error';
        }
    } else {
        $_SESSION['message'] = 'Data tidak lengkap.';
        $_SESSION['message_type'] = 'error';
    }
    
    header('Location: ../view/users/buku_dipinjam.php');
    exit;
}
?>
