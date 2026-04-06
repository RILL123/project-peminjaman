<?php

session_start();

// Cek apakah user udah login
if (!isset($_SESSION['id_user']) || !isset($_SESSION['role'])) {
    header('Location: ../../public/login.php');
    exit;
}

// Cek role admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../../public/login.php');
    exit;
}

include '../model/koneksi.php';
include '../model/log_helper.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $aksi = $_POST['aksi'] ?? null;

    // KEMBALIKAN
    if ($aksi === 'kembalikan') {
        $id_peminjaman = $_POST['id_peminjaman'] ?? null;
        $id_buku = $_POST['id_buku'] ?? null;

        if ($id_peminjaman && $id_buku) {
            // Ambil data untuk log
            $buku_data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT judul FROM buku WHERE id_buku = '$id_buku'"));
            
            mysqli_query($koneksi, "UPDATE buku SET stok = stok + 1 WHERE id_buku = '$id_buku'");
            mysqli_query($koneksi, "DELETE FROM detail_peminjaman WHERE id_peminjaman = '$id_peminjaman' AND id_buku = '$id_buku'");
            mysqli_query($koneksi, "DELETE FROM peminjaman WHERE id_peminjaman = '$id_peminjaman'");

            // Tambah log aktivitas
            tambah_log($koneksi, $_SESSION['id_user'], 'Pengembalian Buku', "Mengembalikan buku", $id_buku);

            $_SESSION['message'] = 'Buku berhasil dikembalikan.';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'ID peminjaman atau id_buku tidak ditemukan.';
            $_SESSION['message_type'] = 'error';
        }

        header('Location: ../view/admin/transaksi.php');
        exit;
    }

    // APPROVE ALL
    elseif ($aksi === 'approve_all') {
        $result = mysqli_query($koneksi, "SELECT r.*, u.nama, b.judul FROM request_peminjaman r JOIN users u ON r.id_user = u.id_user JOIN buku b ON r.id_buku = b.id_buku WHERE r.status = 'pending'");
        $berhasil = 0;
        $gagal = 0;

        while ($req = mysqli_fetch_assoc($result)) {
            $id_user = $req['id_user'];
            $id_buku = $req['id_buku'];
            $nama_peminjam = $req['nama'];
            $judul_buku = $req['judul'];
            $tanggal_pinjam = date('Y-m-d');
            $tanggal_kembali = date('Y-m-d', strtotime('+3 days'));

            $q1 = "INSERT INTO peminjaman (id_user, tanggal_pinjam, tanggal_kembali)
                   VALUES ('$id_user', '$tanggal_pinjam', '$tanggal_kembali')";

            if (mysqli_query($koneksi, $q1)) {
                $id_peminjaman = mysqli_insert_id($koneksi);

                $q2 = "INSERT INTO detail_peminjaman (id_peminjaman, id_buku)
                       VALUES ('$id_peminjaman', '$id_buku')";

                if (mysqli_query($koneksi, $q2)) {
                    mysqli_query($koneksi, "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$id_buku' AND stok > 0");
                    mysqli_query($koneksi, "DELETE FROM request_peminjaman WHERE id_request = '{$req['id_request']}'");
                    
                    // Tambah log aktivitas
                    tambah_log($koneksi, $_SESSION['id_user'], 'Terima Request Peminjaman', "Dari $nama_peminjam untuk buku $judul_buku (Batch)", $id_buku);
                    
                    $berhasil++;
                } else {
                    $gagal++;
                }
            } else {
                $gagal++;
            }
        }

        $_SESSION['message'] = "$berhasil berhasil, $gagal gagal.";
        $_SESSION['message_type'] = 'success';

        header('Location: ../view/admin/notifikasi.php');
        exit;
    }

    // TAMBAH
    elseif ($aksi === 'tambah') {
        $id_user = $_POST['id_user'];
        $id_buku = $_POST['id_buku'];
        $tanggal_pinjam = $_POST['tanggal_pinjam'];
        $tanggal_kembali = $_POST['tanggal_kembali'];

        $q1 = "INSERT INTO peminjaman (id_user, tanggal_pinjam, tanggal_kembali)
               VALUES ('$id_user', '$tanggal_pinjam', '$tanggal_kembali')";

        if (mysqli_query($koneksi, $q1)) {
            $id_peminjaman = mysqli_insert_id($koneksi);

            $q2 = "INSERT INTO detail_peminjaman (id_peminjaman, id_buku)
                   VALUES ('$id_peminjaman', '$id_buku')";

            if (mysqli_query($koneksi, $q2)) {
                // Ambil data buku untuk log
                $buku_data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT judul FROM buku WHERE id_buku = '$id_buku'"));
                
                mysqli_query($koneksi, "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$id_buku' AND stok > 0");

                // Tambah log aktivitas
                tambah_log($koneksi, $_SESSION['id_user'], 'Tambah Peminjaman', "Membuat peminjaman baru", $id_buku);

                $_SESSION['message'] = 'Berhasil tambah peminjaman';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Gagal detail peminjaman';
                $_SESSION['message_type'] = 'error';
            }
        }

        header('Location: ../view/admin/transaksi.php');
        exit;
    }

    // APPROVE
    elseif ($aksi === 'approve') {
        $id_request = $_POST['id_request'];

        $req = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT r.*, u.nama, b.judul FROM request_peminjaman r JOIN users u ON r.id_user = u.id_user JOIN buku b ON r.id_buku = b.id_buku WHERE r.id_request = '$id_request'"));

        if ($req) {
            $id_user = $req['id_user'];
            $id_buku = $req['id_buku'];
            $nama_peminjam = $req['nama'];
            $judul_buku = $req['judul'];

            $tanggal_pinjam = date('Y-m-d');
            $tanggal_kembali = date('Y-m-d', strtotime('+3 days'));

            mysqli_query($koneksi, "INSERT INTO peminjaman (id_user, tanggal_pinjam, tanggal_kembali)
                                   VALUES ('$id_user','$tanggal_pinjam','$tanggal_kembali')");

            $id_peminjaman = mysqli_insert_id($koneksi);

            mysqli_query($koneksi, "INSERT INTO detail_peminjaman (id_peminjaman, id_buku)
                                   VALUES ('$id_peminjaman','$id_buku')");

            mysqli_query($koneksi, "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$id_buku' AND stok > 0");
            mysqli_query($koneksi, "DELETE FROM request_peminjaman WHERE id_request = '$id_request'");

            // Tambah log aktivitas
            tambah_log($koneksi, $_SESSION['id_user'], 'Terima Request Peminjaman', "Dari $nama_peminjam untuk buku $judul_buku", $id_buku);

            $_SESSION['message'] = 'Request diterima';
            $_SESSION['message_type'] = 'success';
        }

        header('Location: ../view/admin/notifikasi.php');
        exit;
    }

    // REJECT
    elseif ($aksi === 'reject') {
        $id_request = $_POST['id_request'];

        // Ambil data untuk log
        $req = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT r.*, u.nama, b.judul FROM request_peminjaman r JOIN users u ON r.id_user = u.id_user JOIN buku b ON r.id_buku = b.id_buku WHERE r.id_request = '$id_request'"));
        
        if ($req) {
            $nama_peminjam = $req['nama'];
            $judul_buku = $req['judul'];
            $id_buku = $req['id_buku'];
            
            // Ambil alasan penolakan jika ada di POST
            $alasan = isset($_POST['alasan_penolakan']) ? mysqli_real_escape_string($koneksi, $_POST['alasan_penolakan']) : 'Tidak ada alasan';
            
            // Update alasan penolakan
            mysqli_query($koneksi, "UPDATE request_peminjaman SET alasan_penolakan = '$alasan' WHERE id_request = '$id_request'");
            
            // Hapus request
            mysqli_query($koneksi, "DELETE FROM request_peminjaman WHERE id_request = '$id_request'");
            
            // Tambah log aktivitas
            tambah_log($koneksi, $_SESSION['id_user'], 'Tolak Request Peminjaman', "Dari $nama_peminjam untuk buku $judul_buku. Alasan: $alasan", $id_buku);
        }

        $_SESSION['message'] = 'Request ditolak';
        $_SESSION['message_type'] = 'success';

        header('Location: ../view/admin/notifikasi.php');
        exit;
    }

    // TERIMA USER
    elseif ($aksi === 'terima') {
        $id_user = $_POST['id_user'];
        $id_buku = $_POST['id_buku'];
        $tanggal_kembali = $_POST['tanggal_kembali'];
        $tanggal_pinjam = date('Y-m-d');

        mysqli_query($koneksi, "INSERT INTO peminjaman (id_user, tanggal_pinjam, tanggal_kembali)
                               VALUES ('$id_user','$tanggal_pinjam','$tanggal_kembali')");

        $id_peminjaman = mysqli_insert_id($koneksi);

        mysqli_query($koneksi, "INSERT INTO detail_peminjaman (id_peminjaman, id_buku)
                               VALUES ('$id_peminjaman','$id_buku')");

        // Ambil data buku untuk log
        $buku_data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT judul FROM buku WHERE id_buku = '$id_buku'"));

        mysqli_query($koneksi, "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$id_buku' AND stok > 0");

        // Tambah log aktivitas
        tambah_log($koneksi, $_SESSION['id_user'], 'Terima Peminjaman', "Menerima peminjaman buku", $id_buku);

        $_SESSION['message'] = 'Peminjaman diterima';
        $_SESSION['message_type'] = 'success';

        header('Location: ../view/users/landing.php');
        exit;
    }

    // DEFAULT
    else {
        $_SESSION['message'] = 'Aksi tidak valid';
        $_SESSION['message_type'] = 'error';

        header('Location: ../view/admin/transaksi.php');
        exit;
    }
}
header('Location: ../view/admin/transaksi.php');
mysqli_close($koneksi);
exit;
?>
