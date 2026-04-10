<?php
session_start();
include '../../model/koneksi.php';

$id_user = $_SESSION['id_user'] ?? null;
if (!$id_user) {
    echo '<div class="text-red-500 text-center py-4">Anda belum login.</div>';
    exit;
}

// Pagination
$items_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Search
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Query untuk buku yang sudah dikembalikan (riwayat)
$where_riwayat = "rp.id_user = '$id_user' AND rp.status = 'approved'";
if ($search_term !== '') {
    $search_term_escaped = mysqli_real_escape_string($koneksi, $search_term);
    $where_riwayat .= " AND (b.judul LIKE '%$search_term_escaped%' OR b.penulis LIKE '%$search_term_escaped%')";
}
$query_riwayat = "SELECT b.judul, b.penulis, p.tanggal_pinjam, p.tanggal_kembali, rp.tanggal_approved AS tanggal_pengembalian
    FROM request_pengembalian rp
    JOIN buku b ON rp.id_buku = b.id_buku
    JOIN peminjaman p ON rp.id_peminjaman = p.id_peminjaman
    WHERE $where_riwayat";

// Query untuk buku yang masih dipinjam (belum dikembalikan)
$where_dipinjam = "p.id_user = '$id_user'";
if ($search_term !== '') {
    $where_dipinjam .= " AND (b.judul LIKE '%$search_term_escaped%' OR b.penulis LIKE '%$search_term_escaped%')";
}
$query_dipinjam = "SELECT b.judul, b.penulis, p.tanggal_pinjam, p.tanggal_kembali, NULL AS tanggal_pengembalian
    FROM peminjaman p
    JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
    JOIN buku b ON dp.id_buku = b.id_buku
    WHERE $where_dipinjam
    AND NOT EXISTS (
        SELECT 1 FROM request_pengembalian rp2 WHERE rp2.id_peminjaman = p.id_peminjaman AND rp2.id_buku = dp.id_buku AND rp2.status = 'approved'
    )";

// Gabungkan hasil
$query_union =
    "($query_riwayat)
    UNION ALL
    ($query_dipinjam)
    ORDER BY tanggal_pinjam DESC
    LIMIT $items_per_page OFFSET $offset";
$result = mysqli_query($koneksi, $query_union);

// Hitung total data untuk pagination
$count_union =
    "SELECT COUNT(*) AS total FROM (
        ($query_riwayat)
        UNION ALL
        ($query_dipinjam)
    ) AS all_data";
$count_result = mysqli_query($koneksi, $count_union);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'] ?? 0;
$total_pages = max(1, ceil($total_records / $items_per_page));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../src/output.css" rel="stylesheet">
    <title>Riwayat Pengembalian Buku</title>
    <link rel="icon" type="image/png" href="../../public/image/perpusku.png">
</head>
<body class="bg-perpusku4 min-h-screen">
<?php include '../partials/user_sidebar.php'; ?>
<main id="mainContent" class="flex-1 flex flex-col min-h-screen md:ml-64 transition-all duration-300 p-4 md:p-8">
    <!-- Header -->
    <div class="bg-linear-to-r from-perpusku1 to-perpusku2 rounded-2xl shadow-lg p-6 md:p-8 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold mb-2">Riwayat Pengembalian Buku</h2>
                <p class="text-perpusku4 text-lg">Daftar buku yang sudah Anda kembalikan</p>
            </div>
            <a href="buku_dipinjam.php" class="bg-perpusku3 text-perpusku1 px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <form method="GET" class="flex gap-2">
            <input 
                type="text" 
                name="search" 
                id="searchInput" 
                placeholder="Cari judul atau penulis..." 
                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                class="flex-1 px-4 py-3 border border-perpusku2 rounded-xl p-4 focus:border-perpusku1 transition"
            />
            <button type="submit" class="bg-perpusku1 text-white px-6 py-3 rounded-lg hover:bg-perpusku2 transition font-semibold">
                Cari
            </button>
            <?php if (isset($_GET['search']) && $_GET['search'] !== ''): ?>
                <a href="riwayat_peminjaman.php" class="bg-perpusku2 text-white px-6 py-3 rounded-lg hover:bg-perpusku1 transition font-semibold">
                    Reset
                </a>
            <?php endif; ?>
        </form>
        <div class="mt-2 text-sm text-gray-600">
            Total data: <span><?= $total_records ?></span> | Ditampilkan: <span><?= mysqli_num_rows($result) ?></span>
        </div>
    </div>

    <!-- Tabel Riwayat Pengembalian -->
    <div class="bg-white rounded-xl shadow-md overflow-x-auto" style="max-height: 600px; overflow-y: auto;">
        <table class="w-full">
            <thead>
                <tr class="bg-perpusku1 text-white">
                    <th class="px-6 py-4 text-left text-sm font-semibold">Judul Buku</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Penulis</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal Pinjam</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal Kembali</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="border-b border-gray-200 hover:bg-perpusku4 transition">
                        <td class="px-6 py-4 text-sm font-semibold text-perpusku1"><?= htmlspecialchars($row['judul']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($row['penulis']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?= date('d M Y', strtotime($row['tanggal_pinjam'])) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?= date('d M Y', strtotime($row['tanggal_kembali'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data pengembalian buku.
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
</body>
</html>
