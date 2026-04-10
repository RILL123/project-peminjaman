
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
    $where_clause = "WHERE (p.id_peminjaman LIKE '%$search_term_escaped%' 
        OR u.nama LIKE '%$search_term_escaped%'
        OR b.judul LIKE '%$search_term_escaped%'
        OR b.kategori LIKE '%$search_term_escaped%'
        OR b.penulis LIKE '%$search_term_escaped%')";
}

// Total data
$count_query = "SELECT COUNT(*) AS total FROM peminjaman p
    JOIN users u ON p.id_user = u.id_user
    JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
    JOIN buku b ON dp.id_buku = b.id_buku
    $where_clause";
$count_result = mysqli_query($koneksi, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'];
$total_pages = ceil($total_records / $items_per_page);

// Data peminjaman
$query = "SELECT 
    p.id_peminjaman,
    u.id_user,
    u.nama as nama_user,
    b.id_buku,
    b.judul,
    b.kategori,
    b.penulis,
    b.cover,
    p.tanggal_pinjam,
    p.tanggal_kembali,
    dp.jumlah
FROM peminjaman p
JOIN users u ON p.id_user = u.id_user
JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
JOIN buku b ON dp.id_buku = b.id_buku
$where_clause
GROUP BY p.id_peminjaman, dp.id_buku
ORDER BY p.tanggal_pinjam DESC
LIMIT $items_per_page OFFSET $offset";
$result = mysqli_query($koneksi, $query);
if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Peminjaman</title>
<link href="../../src/output.css" rel="stylesheet">
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
                        <h2 class="text-3xl md:text-4xl font-bold mb-2">Data Peminjaman</h2>
                        <p class="text-perpusku4 text-lg">Pantau seluruh transaksi peminjaman buku</p>
                    </div>
                    <a href="crud_transaksi.php" class="bg-perpusku3 text-perpusku1 px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition">
                        + Tambah Peminjaman
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
                        placeholder="Cari user, buku, kategori, penulis..." 
                        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                        class="flex-1 px-4 py-3 border border-perpusku2 rounded-xl p-4 focus:border-perpusku1 transition"
                    />
                    <button type="submit" class="bg-perpusku1 text-white px-6 py-3 rounded-lg hover:bg-perpusku2 transition font-semibold">
                        Cari
                    </button>
                    <?php if (isset($_GET['search']) && $_GET['search'] !== ''): ?>
                        <a href="transaksi.php" class="bg-perpusku2 text-white px-6 py-3 rounded-lg hover:bg-perpusku1 transition font-semibold">
                            Reset
                        </a>
                    <?php endif; ?>
                </form>
                <div class="mt-2 text-sm text-gray-600">
                    Total data: <span><?= $total_records ?></span>
                </div>
            </div>

            <!-- Tabel Transaksi -->
            <div class="bg-white rounded-xl shadow-md overflow-x-auto" style="max-height: 600px; overflow-y: auto;">
                <table class="w-full">
                    <thead>
                        <tr class="bg-perpusku1 text-white">
                            <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nama Peminjam</th>
                            <!-- Kolom judul, kategori, penulis dihapus, semua info di modal detail -->
                            <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal Pinjam</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal Kembali</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Jumlah</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = $offset + 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr class="border-b border-gray-200 hover:bg-perpusku4 transition">
                            <td class="px-6 py-4 text-sm font-semibold text-perpusku1"><?= $no++ ?></td>
                            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($row['nama_user']) ?></td>
                            <!-- Data judul, kategori, penulis dihapus dari tabel utama -->
                            <td class="px-6 py-4 text-sm"><?= date('d-m-Y', strtotime($row['tanggal_pinjam'])) ?></td>
                            <td class="px-6 py-4 text-sm"><?= date('d-m-Y', strtotime($row['tanggal_kembali'])) ?></td>
                            <td class="px-6 py-4 text-center text-sm font-bold text-perpusku1"><?= (int)$row['jumlah'] ?></td>
                            <td class="px-6 py-4 text-center text-sm">
                                <form id="form-kembalikan-<?= $row['id_peminjaman'] ?>" action="../../controller/aksi_peminjaman.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="aksi" value="kembalikan">
                                    <input type="hidden" name="id_peminjaman" value="<?= $row['id_peminjaman'] ?>">
                                    <input type="hidden" name="id_buku" value="<?= $row['id_buku'] ?>">
                                    <button type="button" onclick="showConfirmModal(<?= $row['id_peminjaman'] ?>, '<?= htmlspecialchars($row['judul']) ?>')" class="bg-perpusku1 text-white px-3 py-1 rounded shadow text-xs font-semibold transition hover:bg-perpusku2">Kembalikan Buku</button>
                                </form>
                                <button type="button" onclick="showDetailModal(
                                    '<?= htmlspecialchars($row['id_peminjaman']) ?>',
                                    '<?= htmlspecialchars($row['nama_user']) ?>',
                                    '<?= htmlspecialchars($row['id_user']) ?>',
                                    '<?= htmlspecialchars($row['judul']) ?>',
                                    '<?= htmlspecialchars($row['kategori']) ?>',
                                    '<?= htmlspecialchars($row['penulis']) ?>',
                                    '<?= htmlspecialchars($row['tanggal_pinjam']) ?>',
                                    '<?= htmlspecialchars($row['tanggal_kembali']) ?>',
                                    '<?= htmlspecialchars($row['jumlah']) ?>',
                                    '<?= htmlspecialchars($row['cover']) ?>'
                                )" class="bg-perpusku3 text-perpusku1 px-3 py-1 rounded shadow text-xs font-semibold transition hover:bg-yellow-400">Detail</button>
                                <button type="button" onclick="printStrukJS('<?= $row['id_peminjaman'] ?>', '<?= htmlspecialchars($row['nama_user']) ?>', '<?= htmlspecialchars($row['id_user']) ?>', '<?= htmlspecialchars($row['judul']) ?>', '<?= htmlspecialchars($row['kategori']) ?>', '<?= htmlspecialchars($row['penulis']) ?>', '<?= htmlspecialchars($row['tanggal_pinjam']) ?>', '<?= htmlspecialchars($row['tanggal_kembali']) ?>', '<?= htmlspecialchars($row['cover']) ?>')" class="bg-perpusku3 text-perpusku1 px-3 py-1 rounded shadow text-xs font-semibold transition hover:bg-yellow-400">Print</button>
                                    <!-- Modal Detail Transaksi -->
                                    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
                                        <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-md mx-4 modal-animate relative">
                                            <button onclick="closeDetailModal()" class="absolute top-2 right-2 text-gray-400 hover:text-perpusku1 text-2xl font-bold">&times;</button>
                                            <h3 class="text-xl font-bold text-perpusku1 mb-4">Detail Transaksi Peminjaman</h3>
                                            <div class="space-y-2 text-sm">
                                                <div><b>ID Peminjaman:</b> <span id="detail_id_peminjaman"></span></div>
                                                <div><b>Nama Peminjam:</b> <span id="detail_nama_user"></span> <span class="text-xs text-gray-500">(ID: <span id="detail_id_user"></span>)</span></div>
                                                <div><b>Judul Buku:</b> <span id="detail_judul"></span></div>
                                                <div><b>Kategori:</b> <span id="detail_kategori"></span></div>
                                                <div><b>Penulis:</b> <span id="detail_penulis"></span></div>
                                                <div><b>Tanggal Pinjam:</b> <span id="detail_tanggal_pinjam"></span></div>
                                                <div><b>Tanggal Kembali:</b> <span id="detail_tanggal_kembali"></span></div>
                                                <div><b>Jumlah:</b> <span id="detail_jumlah"></span></div>
                                                <div><b>Cover:</b> <span id="detail_cover"></span></div>
                                            </div>
                                        </div>
                                    </div>
                            <script>
                            function showDetailModal(id_peminjaman, nama_user, id_user, judul, kategori, penulis, tanggal_pinjam, tanggal_kembali, jumlah, cover) {
                                document.getElementById('detail_id_peminjaman').textContent = id_peminjaman;
                                document.getElementById('detail_nama_user').textContent = nama_user;
                                document.getElementById('detail_id_user').textContent = id_user;
                                document.getElementById('detail_judul').textContent = judul;
                                document.getElementById('detail_kategori').textContent = kategori;
                                document.getElementById('detail_penulis').textContent = penulis;
                                document.getElementById('detail_tanggal_pinjam').textContent = new Date(tanggal_pinjam).toLocaleDateString('id-ID');
                                document.getElementById('detail_tanggal_kembali').textContent = new Date(tanggal_kembali).toLocaleDateString('id-ID');
                                document.getElementById('detail_jumlah').textContent = jumlah;
                                if (cover && cover !== 'null' && cover !== '') {
                                    document.getElementById('detail_cover').innerHTML = `<img src='../../public/cover/${cover}' style='height:60px;margin-top:8px;border-radius:8px;border:1px solid #FAB95B;'>`;
                                } else {
                                    document.getElementById('detail_cover').innerHTML = '<span class="text-gray-400">(tidak ada cover)</span>';
                                }
                                document.getElementById('detailModal').classList.remove('hidden');
                            }

                            function closeDetailModal() {
                                document.getElementById('detailModal').classList.add('hidden');
                            }
                            </script>
                            </td>
                        </tr>
                        <?php }
                        if ($total_records == 0): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-600">
                                Tidak ada data peminjaman
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
                    <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 3v4M8 3v4m-5 4h18"/></svg>
                    <span class="text-lg">Belum ada data peminjaman</span>
                </div>
            </div>

        <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.5s cubic-bezier(.4,0,.2,1);
        }
        @keyframes modalSlideIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .modal-animate {
            animation: modalSlideIn 0.3s ease-out;
        }
        </style>

        <!-- Confirmation Modal -->
        <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
            <div class="bg-white rounded-lg shadow-2xl p-6 max-w-sm mx-4 modal-animate">
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 rounded-full bg-yellow-100">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-6a4 4 0 110 8 4 4 0 010-8z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-center text-perpusku1 mb-2">Kembalikan Buku?</h3>
                <p class="text-sm text-gray-600 text-center mb-1">Buku:</p>
                <p class="text-sm font-semibold text-center text-perpusku1 mb-6" id="modalBookTitle"></p>
                <div class="flex gap-3">
                    <button type="button" onclick="closeConfirmModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="button" onclick="submitReturnBook()" class="flex-1 px-4 py-2 bg-perpusku1 text-white rounded-lg font-semibold hover:bg-perpusku2 transition">
                        Ya, Kembalikan
                    </button>
                </div>
            </div>
        </div>
        </div>
<script>
let currentPeminjamanId = null;

function showConfirmModal(peminjamanId, bookTitle) {
    currentPeminjamanId = peminjamanId;
    document.getElementById('modalBookTitle').textContent = bookTitle;
    document.getElementById('confirmModal').classList.remove('hidden');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    currentPeminjamanId = null;
}

function submitReturnBook() {
    if (currentPeminjamanId) {
        const form = document.getElementById('form-kembalikan-' + currentPeminjamanId);
        if (form) {
            form.submit();
        }
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('confirmModal');
    if (event.target === modal) {
        closeConfirmModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeConfirmModal();
    }
});

function printStrukJS(id, nama_user, id_user, judul, kategori, penulis, tanggal_pinjam, tanggal_kembali, cover) {
    let strukHTML = `
        <div style='font-family:sans-serif;padding:30px;max-width:350px;'>
            <h2 style='color:#1A3263;margin-bottom:16px;'>Struk Transaksi Peminjaman Buku</h2>
            <div style='margin-bottom:8px;'><b>ID Peminjaman:</b> ${id}</div>
            <div style='margin-bottom:8px;'><b>Nama Peminjam:</b> ${nama_user} <span style='font-size:11px;color:#547792;'>(ID: ${id_user})</span></div>
            <div style='margin-bottom:8px;'><b>Judul Buku:</b> ${judul}</div>
            <div style='margin-bottom:8px;'><b>Kategori:</b> ${kategori}</div>
            <div style='margin-bottom:8px;'><b>Penulis:</b> ${penulis}</div>
            <div style='margin-bottom:8px;'><b>Tanggal Pinjam:</b> ${new Date(tanggal_pinjam).toLocaleDateString('id-ID')}</div>
            <div style='margin-bottom:8px;'><b>Tanggal Kembali:</b> ${new Date(tanggal_kembali).toLocaleDateString('id-ID')}</div>
            <div style='margin-bottom:8px;'><b>Dicetak pada:</b> ${new Date().toLocaleString('id-ID')}</div>
            <div style='margin-bottom:8px;'><b>Cover:</b> ` + (cover && cover !== 'null' && cover !== '' ? `<img src='../../public/cover/${cover}' style='height:60px;margin-top:8px;border-radius:8px;border:1px solid #FAB95B;'>` : '<span style="color:#547792;">(tidak ada cover)</span>') + `</div>
        </div>
    `;
    let win = window.open('', 'PrintWindow', 'width=400,height=600');
    win.document.write(`
        <html><head><title>Struk Transaksi Peminjaman Buku</title></head><body>${strukHTML}</body></html>
    `);
    win.document.close();
    win.focus();
    win.print();
}
</script>

    <script>
    </script>
</body>
</html>

<?php mysqli_close($koneksi); ?>
  