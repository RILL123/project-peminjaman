<?php
include '../../model/koneksi.php';
$q = isset($_GET['q']) ? mysqli_real_escape_string($koneksi, $_GET['q']) : '';
$data = [];
if ($q !== '') {
    $result = mysqli_query($koneksi, "SELECT id_buku, judul, cover, kategori, penulis FROM buku WHERE judul LIKE '%$q%' ORDER BY judul ASC LIMIT 8");
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}
header('Content-Type: application/json');
echo json_encode($data);
