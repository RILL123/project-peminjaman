<?php
session_start();
include '../../model/koneksi.php';


// Pagination & Search
$items_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$where_clause = "";
if ($search_term !== '') {
    $search_term_escaped = mysqli_real_escape_string($koneksi, $search_term);
    $where_clause = "WHERE (r.id_request LIKE '%$search_term_escaped%' 
        OR u.nama LIKE '%$search_term_escaped%'
        OR b.judul LIKE '%$search_term_escaped%'
        OR r.status LIKE '%$search_term_escaped%')";
}

// Total data
$count_query = "SELECT COUNT(*) AS total FROM request_peminjaman r
    JOIN users u ON r.id_user = u.id_user
    JOIN buku b ON r.id_buku = b.id_buku
    $where_clause";
$count_result = mysqli_query($koneksi, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'];
$total_pages = ceil($total_records / $items_per_page);

// Data request
$query = "SELECT r.id_request, r.id_user, u.nama, r.id_buku, b.judul, r.tanggal_request, r.status, r.alasan_penolakan FROM request_peminjaman r JOIN users u ON r.id_user = u.id_user JOIN buku b ON r.id_buku = b.id_buku $where_clause ORDER BY r.tanggal_request DESC LIMIT $items_per_page OFFSET $offset";
$pending = mysqli_query($koneksi, $query);
if (!$pending) {
    die("Query error: " . mysqli_error($koneksi));
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Peminjaman</title>
    <link href="../../src/output.css" rel="stylesheet">
    </script>
    <link rel="icon" type="image/png" href="../../public/image/perpusku.png">
</head>
<body class="bg-perpusku4 min-h-screen">
    <?php include '../partials/admin_sidebar.php'; ?>

    <div id="mainContent" class="flex-1 flex flex-col min-h-screen md:ml-64 transition-all duration-300">
        <main class="flex-1 p-4 md:p-8">
            <!-- Header -->
            <div class="bg-linear-to-r from-perpusku1 to-perpusku2 rounded-2xl shadow-lg p-6 md:p-8 mb-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold mb-2">Request Peminjaman Buku</h2>
                        <p class="text-perpusku4 text-lg">Pantau seluruh request peminjaman buku</p>
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
                        <a href="notifikasi.php" class="bg-perpusku2 text-white px-6 py-3 rounded-lg hover:bg-perpusku1 transition font-semibold">
                            Reset
                        </a>
                    <?php endif; ?>
                </form>
                <div class="mt-2 text-sm text-gray-600">
                    Total data: <span><?= $total_records ?></span>
                </div>
            </div>

            <!-- Notifikasi Tabel -->
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
                        <?php if ($total_records > 0): 
                            $no = $offset + 1;
                            while ($row = mysqli_fetch_assoc($pending)):
                                $status_color = $row['status'] == 'pending' ? 'yellow' : ($row['status'] == 'approved' ? 'green' : 'red');
                        ?>
                        <tr class="border-b border-gray-200 hover:bg-perpusku4 transition">
                            <td class="px-6 py-4 text-sm font-semibold text-perpusku1"><?= $no++ ?></td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-bold text-perpusku1"><?= htmlspecialchars($row['nama']) ?></div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-bold text-perpusku1"><?= htmlspecialchars($row['judul']) ?></div>
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
                                    <form method="POST" action="../../controller/aksi_peminjaman.php" style="display:inline;">
                                        <input type="hidden" name="aksi" value="approve">
                                        <input type="hidden" name="id_request" value="<?= $row['id_request'] ?>">
                                        <button type="submit" class="bg-perpusku1 hover:bg-perpusku2 text-white px-3 py-2 rounded font-bold transition text-xs mr-1">Terima</button>
                                    </form>
                                    <form method="POST" action="../../controller/aksi_peminjaman.php" style="display:inline;">
                                        <input type="hidden" name="aksi" value="reject">
                                        <input type="hidden" name="id_request" value="<?= $row['id_request'] ?>">
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
                                <?= $search_term !== '' ? 'Tidak ada data yang cocok dengan pencarian.' : 'Tidak ada request peminjaman.' ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Halaman <span class="font-semibold"><?= $current_page ?></span> dari <span class="font-semibold"><?= $total_pages ?></span> | Total: <span class="font-semibold"><?= $total_records ?></span> data
                </div>
                <div class="flex gap-2">
                    <?php $search_param = $search_term !== '' ? '&search=' . urlencode($search_term) : ''; ?>
                    <?php if ($current_page > 1): ?>
                        <a href="?page=1<?= $search_param ?>" class="bg-perpusku1 text-white px-4 py-2 rounded-lg hover:bg-perpusku2 transition">First</a>
                        <a href="?page=<?= $current_page - 1 ?><?= $search_param ?>" class="bg-perpusku1 text-white px-4 py-2 rounded-lg hover:bg-perpusku2 transition">← Previous</a>
                    <?php endif; ?>
                    <?php if ($current_page < $total_pages): ?>
                        <a href="?page=<?= $current_page + 1 ?><?= $search_param ?>" class="bg-perpusku3 text-perpusku1 px-4 py-2 rounded-lg hover:opacity-90 transition font-semibold">Next →</a>
                        <a href="?page=<?= $total_pages ?><?= $search_param ?>" class="bg-perpusku3 text-perpusku1 px-4 py-2 rounded-lg hover:opacity-90 transition font-semibold">Last</a>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Tolak Request -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full">
            <h3 class="text-lg font-bold text-perpusku1 mb-4">Tolak Request Peminjaman</h3>
            <form method="POST" action="../../controller/aksi_peminjaman.php">
                <input type="hidden" name="id_request" id="reject_id_request">
                <input type="hidden" name="aksi" value="reject">
                
                <div class="mb-4">
                    <label for="alasan_penolakan" class="block font-semibold text-perpusku1 mb-2">Alasan Penolakan</label>
                    <textarea name="alasan_penolakan" id="alasan_penolakan" class="w-full border-2 border-perpusku2 rounded-lg p-3 focus:outline-none focus:border-perpusku1" rows="4" placeholder="Masukkan alasan penolakan..."></textarea>
                </div>
                
                <div class="flex gap-2">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 rounded-lg">Batal</button>
                    <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg">Tolak</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showRejectModal(id_request) {
            document.getElementById('reject_id_request').value = id_request;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('alasan_penolakan').value = '';
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    </script>
</body>
</html>
