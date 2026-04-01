<?php
// Fungsi untuk menambah log aktifitas
function tambah_log($koneksi, $id_user, $aktivitas, $keterangan = '', $id_buku = null) {
    $id_user = mysqli_real_escape_string($koneksi, $id_user);
    $aktivitas = mysqli_real_escape_string($koneksi, $aktivitas);
    $keterangan = mysqli_real_escape_string($koneksi, $keterangan);
    $id_buku_sql = $id_buku ? (int)$id_buku : 'NULL';
    $tanggal = date('Y-m-d H:i:s');
    $query = "INSERT INTO log_aktivitas (id_user, aktivitas, keterangan, id_buku, tanggal) VALUES ('$id_user', '$aktivitas', '$keterangan', $id_buku_sql, '$tanggal')";
    mysqli_query($koneksi, $query);
}
