<?php
$koneksi = mysqli_connect("localhost", "root", "", "peminjaman_buku");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Auto-create request_pengembalian table if not exists
$sql_create_table = "CREATE TABLE IF NOT EXISTS request_pengembalian (
    id_request_kembali INT AUTO_INCREMENT PRIMARY KEY,
    id_peminjaman INT NOT NULL,
    id_user INT NOT NULL,
    id_buku INT NOT NULL,
    tanggal_request DATETIME DEFAULT CURRENT_TIMESTAMP,
    alasan_kembali TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    tanggal_approved DATETIME NULL,
    catatan_admin TEXT NULL,
    FOREIGN KEY (id_peminjaman) REFERENCES peminjaman(id_peminjaman) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE,
    INDEX idx_user (id_user),
    INDEX idx_status (status),
    INDEX idx_tanggal (tanggal_request)
)";

mysqli_query($koneksi, $sql_create_table);

?>