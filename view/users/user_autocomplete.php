<?php
include '../../model/koneksi.php';
$q = isset($_GET['q']) ? mysqli_real_escape_string($koneksi, $_GET['q']) : '';
$data = [];
if ($q !== '') {
    $result = mysqli_query($koneksi, "SELECT id_user, nama FROM users WHERE nama LIKE '%$q%' ORDER BY nama ASC LIMIT 10");
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}
header('Content-Type: application/json');
echo json_encode($data);
