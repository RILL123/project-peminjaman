<?php
include __DIR__ . '/../model/koneksi.php';
header('Content-Type: application/json');

$query = "SELECT id_log, id_user, aktivitas, keterangan, id_buku, tanggal FROM log_aktivitas ORDER BY tanggal DESC";
$result = mysqli_query($koneksi, $query);
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
echo json_encode($data);