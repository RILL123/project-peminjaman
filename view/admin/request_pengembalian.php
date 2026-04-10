<?php
session_start();

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role'])) {
    header('Location: ../../public/login.php');
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    header('Location: ../../public/login.php');
    exit;
}

include '../../model/koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../src/output.css" rel="stylesheet">
    <title>Request Pengembalian Buku</title>
    <link rel="icon" type="image/png" href="../../public/image/perpusku.png">
</head>
<body class="bg-perpusku4 min-h-screen">
<?php 
include '../partials/admin_sidebar.php';

$filter_status = isset($_GET['status']) ? $_GET['status'] : 'pending';
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

// Build where clause
$where = [];
if ($filter_status) {
    $where[] = "rk.status = '" . mysqli_real_escape_string($koneksi, $filter_status) . "'";
}
if ($search) {
    $where[] = "(u.nama LIKE '%$search%' OR b.judul LIKE '%$search%' OR u.username LIKE '%$search%')";
}

$where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

// Get return requests

$query = "SELECT rk.id_request_kembali, rk.id_peminjaman, rk.id_user, rk.id_buku, 
          rk.tanggal_request, rk.status, 
          u.nama, u.username, b.judul, b.penulis, p.tanggal_pinjam, p.tanggal_kembali
          FROM request_pengembalian rk
          JOIN users u ON rk.id_user = u.id_user
          JOIN buku b ON rk.id_buku = b.id_buku
          JOIN peminjaman p ON rk.id_peminjaman = p.id_peminjaman
          $where_sql
          ORDER BY rk.tanggal_request DESC";

$result = mysqli_query($koneksi, $query);
$total = mysqli_num_rows($result);
?>


<div id="mainContent" class="flex-1 flex flex-col min-h-screen md:ml-64 transition-all duration-300">
    <main class="flex-1 p-4 md:p-8">
        <!-- Header -->
        <div class="bg-linear-to-r from-perpusku1 to-perpusku2 rounded-2xl shadow-lg p-6 md:p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold mb-2">Request Pengembalian Buku</h2>
                    <p class="text-perpusku4 text-lg">Pantau seluruh request pengembalian buku</p>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <form method="GET" class="flex gap-2">
                <input 
                    type="text" 
                    name="search" 
                    id="searchInput" 
                    placeholder="Cari user, buku, atau status..." 
                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                    class="flex-1 px-4 py-3 border border-perpusku2 rounded-xl p-4 focus:border-perpusku1 transition"
                />
                <button type="submit" class="bg-perpusku1 text-white px-6 py-3 rounded-lg hover:bg-perpusku2 transition font-semibold">
                    Cari
                </button>
                <?php if (isset($_GET['search']) && $_GET['search'] !== ''): ?>
                    <a href="request_pengembalian.php" class="bg-perpusku2 text-white px-6 py-3 rounded-lg hover:bg-perpusku1 transition font-semibold">
                        Reset
                    </a>
                <?php endif; ?>
            </form>
            <div class="mt-2 text-sm text-gray-600">
                Total data: <span><?= $total ?></span>
            </div>
        </div>

        <!-- Request Pengembalian Tabel -->
        <div class="bg-white rounded-xl shadow-md overflow-x-auto" style="max-height: 600px; overflow-y: auto;">
            <table class="w-full">
                <thead>
                    <tr class="bg-perpusku1 text-white">
                        <th class="px-6 py-4 text-left text-sm font-semibold">ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">User</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Buku</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal Request</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total > 0): 
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)): 
                            $status_color = $row['status'] == 'pending' ? 'yellow' : ($row['status'] == 'approved' ? 'green' : 'red');
                    ?>
                    <tr class="border-b border-gray-300 hover:bg-perpusku4 transition">
                        <td class="px-6 py-4 text-sm font-semibold text-perpusku1"><?= $no++ ?></td>
                        <td class="px-6 py-4 text-sm">
                            <div class="font-bold text-perpusku1"><?= htmlspecialchars($row['nama']) ?></div>
                            <div class="text-gray-600 text-xs"><?= htmlspecialchars($row['username']) ?></div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="font-bold text-perpusku1"><?= htmlspecialchars($row['judul']) ?></div>
                            <div class="text-gray-600 text-xs"><?= htmlspecialchars($row['penulis']) ?></div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <?= date('d M Y H:i', strtotime($row['tanggal_request'])) ?>
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <span class="inline-block px-3 py-1 rounded-full font-bold text-perpusku1 bg-<?= $status_color ?>-500">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <?php if ($row['status'] == 'pending'): ?>
                                <form method="POST" action="../../controller/aksi_request_pengembalian.php" style="display:inline;">
                                    <input type="hidden" name="aksi" value="approve">
                                    <input type="hidden" name="id_request_kembali" value="<?= $row['id_request_kembali'] ?>">
                                    <button type="submit" class="bg-perpusku1 hover:bg-perpusku2 text-white px-3 py-2 rounded font-bold transition text-xs mr-1">Terima</button>
                                </form>
                                <form method="POST" action="../../controller/aksi_request_pengembalian.php" style="display:inline;">
                                    <input type="hidden" name="aksi" value="reject">
                                    <input type="hidden" name="id_request_kembali" value="<?= $row['id_request_kembali'] ?>">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded font-bold transition text-xs">Tolak</button>
                                </form>
                            <?php else: ?>
                                <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-600">
                            Tidak ada request pengembalian
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>
