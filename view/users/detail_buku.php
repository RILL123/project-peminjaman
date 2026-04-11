<?php
session_start();
include '../../model/koneksi.php';

// pagination
$items_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// search
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$where_clause = '';

if ($search_term !== '') {
    $search_term_escaped = mysqli_real_escape_string($koneksi, $search_term);
    $where_clause = "WHERE (l.id_log LIKE '%$search_term_escaped%' 
        OR l.aktivitas LIKE '%$search_term_escaped%'
        OR l.keterangan LIKE '%$search_term_escaped%'
        OR b.judul LIKE '%$search_term_escaped%'
        OR u.nama LIKE '%$search_term_escaped%')";
}

// cek mode print
$isPrint = isset($_GET['print']) && $_GET['print'] == '1';

// query data
$log_query = "SELECT l.*, 
    u.nama AS nama_user,
    b.judul AS judul_buku
    FROM log_aktivitas l
    LEFT JOIN users u ON l.id_user = u.id_user
    LEFT JOIN buku b ON l.id_buku = b.id_buku
    $where_clause
    ORDER BY l.tanggal DESC";

if (!$isPrint) {
    $log_query .= " LIMIT $items_per_page OFFSET $offset";
}

$log_result = mysqli_query($koneksi, $log_query);

// total data
$count_query = "SELECT COUNT(*) AS total FROM log_aktivitas l
    LEFT JOIN users u ON l.id_user = u.id_user
    LEFT JOIN buku b ON l.id_buku = b.id_buku
    $where_clause";

$count_result = mysqli_query($koneksi, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'] ?? 0;

$total_pages = $isPrint ? 1 : ceil($total_records / $items_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../../src/output.css" rel="stylesheet">
<title>Log Aktivitas</title>
</head>

<body class="bg-perpusku4 min-h-screen">

<?php if (!$isPrint): ?>
<?php include '../partials/admin_sidebar.php'; ?>
<?php endif; ?>

<div class="flex-1 flex flex-col min-h-screen md:ml-64">

<main class="flex-1 p-4 md:p-8">

<?php if (!$isPrint): ?>
<!-- HEADER -->
<div class="bg-linear-to-r from-perpusku1 to-perpusku2 rounded-2xl p-6 mb-8 text-white">
    <h2 class="text-3xl font-bold">Log Aktivitas</h2>
</div>

<!-- SEARCH -->
<form method="GET" class="flex gap-2 mb-6">
    <input type="text" name="search" value="<?= htmlspecialchars($search_term) ?>"
        class="flex-1 px-4 py-2 border rounded-lg">
    <button class="bg-perpusku1 text-white px-4 py-2 rounded">Cari</button>
</form>

<!-- BUTTON PRINT -->
<div class="mb-4 flex justify-end">
    <button onclick="printLaporan()" class="bg-perpusku1 text-white px-6 py-2 rounded">
        Cetak Laporan
    </button>
</div>
<?php endif; ?>

<!-- PRINT AREA -->
<div id="printArea" class="bg-white p-4 rounded shadow">

<?php if ($isPrint): ?>
<h2 class="text-center text-2xl font-bold mb-4">LAPORAN LOG AKTIVITAS</h2>
<?php endif; ?>

<table class="w-full border">
<thead>
<tr class="bg-perpusku1 text-white">
    <th class="p-2">ID</th>
    <th class="p-2">Aktivitas</th>
    <th class="p-2">Keterangan</th>
    <th class="p-2">Buku</th>
    <th class="p-2">Tanggal</th>
    <th class="p-2 print-hidden">Aksi</th>
</tr>
</thead>

<tbody>
<?php if (mysqli_num_rows($log_result) > 0): ?>
<?php while ($log = mysqli_fetch_assoc($log_result)): ?>
<tr class="border">
    <td class="p-2"><?= $log['id_log'] ?></td>
    <td class="p-2"><?= $log['aktivitas'] ?></td>
    <td class="p-2"><?= $log['keterangan'] ?></td>
    <td class="p-2"><?= $log['judul_buku'] ?? '-' ?></td>
    <td class="p-2"><?= date('d M Y H:i', strtotime($log['tanggal'])) ?></td>
    
    <td class="p-2 print-hidden">
        <button class="bg-blue-500 text-white px-2 py-1 rounded text-xs">Detail</button>
        <button class="bg-red-500 text-white px-2 py-1 rounded text-xs">Hapus</button>
    </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="6" class="text-center p-4">Tidak ada data</td>
</tr>
<?php endif; ?>
</tbody>
</table>

</div>

</main>
</div>

<!-- PRINT STYLE FIX -->
<style>
@media print {
    body * {
        visibility: hidden;
    }

    #printArea, #printArea * {
        visibility: visible;
    }

    #printArea {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }

    .print-hidden {
        display: none !important;
    }
}
</style>

<!-- SCRIPT -->
<script>
function printLaporan() {
    const printContent = document.getElementById('printArea').innerHTML;

    const printWindow = window.open('', '', 'width=900,height=600');

    printWindow.document.write(`
        <html>
        <head>
            <title>Cetak Laporan</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 20px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #000;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background: #eee;
                }
            </style>
        </head>
        <body>
            <h2 style="text-align:center;">LAPORAN LOG AKTIVITAS</h2>
            ${printContent}
        </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.focus();

    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 500);
}
</script>

</body>
</html>