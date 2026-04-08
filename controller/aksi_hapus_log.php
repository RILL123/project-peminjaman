<?php
header('Content-Type: application/json');

include '../model/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_log'])) {
    $id_log = (int)$_POST['id_log'];
    
    // Delete log
    $delete_query = "DELETE FROM log_aktivitas WHERE id_log = $id_log";
    
    if (mysqli_query($koneksi, $delete_query)) {
        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus']);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($koneksi)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
