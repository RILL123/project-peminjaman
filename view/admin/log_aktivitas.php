<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../src/output.css" rel="stylesheet">
    <title>Log Aktivitas</title>
    <link rel="icon" type="image/png" href="../../public/image/perpusku.png">
    <script src="../../public/realtime.js"></script>
</head>
<body class="bg-perpusku4 min-h-screen">
        <?php include '../partials/admin_sidebar.php'; ?>
        <button id="showSidebarBtn" class="fixed top-4 left-4 z-40 bg-perpusku1 text-perpusku3 w-14 h-14 rounded-full flex items-center justify-center shadow-lg transition hover:bg-perpusku2" style="display:none" onclick="showSidebar()">
            <img src="../../public/image/menu.png" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16" />
            </svg>
        </button>
        <!-- konten utama -->
        <div id="mainContent" class="flex-1 flex flex-col min-h-screen md:ml-64 transition-all duration-300">
            
            <main class="flex-1 p-4 md:p-8">
                <!-- Header -->
                <div class="bg-gradient-to-r from-perpusku1 to-perpusku2 rounded-2xl shadow-lg p-6 md:p-8 mb-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-3xl md:text-4xl font-bold mb-2">Log Aktivitas</h2>
                            <p class="text-perpusku4 text-lg">Pantau seluruh aktivitas sistem</p>
                        </div>
                        <a href="dashboard.php" class="bg-perpusku3 text-perpusku1 px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition">
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
                            placeholder="Cari ID, aktivitas, keterangan, buku, atau user..." 
                            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-perpusku1 transition"
                        />
                        <button type="submit" class="bg-perpusku1 text-white px-6 py-3 rounded-lg hover:bg-perpusku2 transition font-semibold">
                            Cari
                        </button>
                        <?php if (isset($_GET['search']) && $_GET['search'] !== ''): ?>
                            <a href="log_aktivitas.php" class="bg-perpusku2 text-white px-6 py-3 rounded-lg hover:bg-perpusku1 transition font-semibold">
                                Reset
                            </a>
                        <?php endif; ?>
                    </form>
                    <div class="mt-2 text-sm text-gray-600">
                        Total data: <span id="totalData">0</span> | Ditampilkan: <span id="displayedData">0</span>
                    </div>
                </div>

                <!-- Log Aktivitas Tabel -->
                <div class="bg-white rounded-xl shadow-md overflow-x-auto" style="max-height: 600px; overflow-y: auto;">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-perpusku1 text-white">
                                <th class="px-6 py-4 text-left text-sm font-semibold">ID</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Aktivitas</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Keterangan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Buku</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal & Jam</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include '../../model/koneksi.php';

                            // pengaturan pagination
                            $items_per_page = 10;
                            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $offset = ($current_page - 1) * $items_per_page;
                            
                            // get pencarian dari query string
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

                            // jumlah total data untuk pagination
                            $count_query = "SELECT COUNT(*) AS total FROM log_aktivitas l
                                LEFT JOIN users u ON l.id_user = u.id_user
                                LEFT JOIN buku b ON l.id_buku = b.id_buku
                                $where_clause";
                            $count_result = mysqli_query($koneksi, $count_query);
                            $count_row = mysqli_fetch_assoc($count_result);
                            $total_records = $count_row['total'];
                            $total_pages = ceil($total_records / $items_per_page);

                            // get log data
                            $log_query = "SELECT l.*, 
                                u.nama AS nama_user,
                                b.judul AS judul_buku
                                FROM log_aktivitas l
                                LEFT JOIN users u ON l.id_user = u.id_user
                                LEFT JOIN buku b ON l.id_buku = b.id_buku
                                $where_clause
                                ORDER BY l.tanggal DESC
                                LIMIT $items_per_page OFFSET $offset";
                            $log_result = mysqli_query($koneksi, $log_query);
                            
                            if (mysqli_num_rows($log_result) > 0):
                                $no = $offset + 1;
                                while ($log = mysqli_fetch_assoc($log_result)):
                            ?>
                            <tr class="border-b border-gray-200 hover:bg-perpusku4 transition log-row" data-search="<?= strtolower(htmlspecialchars($log['id_log'] . ' ' . $log['aktivitas'] . ' ' . $log['keterangan'] . ' ' . ($log['judul_buku'] ?? '') . ' ' . ($log['nama_user'] ?? ''))) ?>">
                                <td class="px-6 py-4 text-sm text-gray-700 row-number" data-id="<?= htmlspecialchars($log['id_log']) ?>"><?= htmlspecialchars($log['id_log']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-700 font-semibold text-perpusku1">
                                    <?= htmlspecialchars($log['aktivitas']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= htmlspecialchars($log['keterangan']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php if ($log['judul_buku']): ?>
                                        <span class="bg-perpusku2 text-white px-3 py-1 rounded-full text-xs"><?= htmlspecialchars($log['judul_buku']) ?></span>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= date('d M Y H:i', strtotime($log['tanggal'])) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 flex gap-2">
                                    <button onclick="openDetailModal(<?= $log['id_log'] ?>, '<?= htmlspecialchars($log['aktivitas']) ?>', '<?= htmlspecialchars($log['keterangan']) ?>', '<?= htmlspecialchars($log['judul_buku'] ?? '') ?>', '<?= htmlspecialchars($log['nama_user'] ?? 'Admin') ?>', '<?= date('d M Y H:i', strtotime($log['tanggal'])) ?>')" class="bg-perpusku1 text-white px-3 py-1 rounded hover:bg-perpusku2 transition text-xs font-semibold">
                                        Detail
                                    </button>
                                    <button onclick="deleteLog(<?= $log['id_log'] ?>)" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition text-xs font-semibold">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    <?= $search_term !== '' ? 'Tidak ada data yang cocok dengan pencarian.' : 'Belum ada data aktivitas.' ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- kontrol halaman -->
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
    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-perpusku1">Detail Aktivitas</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 font-semibold">ID</p>
                    <p id="modalId" class="text-lg text-gray-800"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold">Aktivitas</p>
                    <p id="modalAktivitas" class="text-lg text-gray-800"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold">Keterangan</p>
                    <p id="modalKeterangan" class="text-lg text-gray-800"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold">Buku</p>
                    <p id="modalBuku" class="text-lg text-gray-800"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold">User</p>
                    <p id="modalUser" class="text-lg text-gray-800"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold">Tanggal & Jam</p>
                    <p id="modalTanggal" class="text-lg text-gray-800"></p>
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <button onclick="closeDetailModal()" class="flex-1 bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 transition font-semibold">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script src="../../public/sidebar.js"></script>
    <script>
        // update jumlah
        const logRows = document.querySelectorAll('.log-row');
        const totalDataSpan = document.getElementById('totalData');
        const displayedDataSpan = document.getElementById('displayedData');

        // inisialisasi jumlah data
        function initCounts() {
            displayedDataSpan.textContent = logRows.length;
            totalDataSpan.textContent = document.querySelector('.text-sm.text-gray-600 .font-semibold:nth-child(5)').textContent;
        }

        initCounts();

        // Modal Detail
        function openDetailModal(id, aktivitas, keterangan, buku, user, tanggal) {
            document.getElementById('modalId').textContent = id;
            document.getElementById('modalAktivitas').textContent = aktivitas;
            document.getElementById('modalKeterangan').textContent = keterangan;
            document.getElementById('modalBuku').textContent = buku || '-';
            document.getElementById('modalUser').textContent = user;
            document.getElementById('modalTanggal').textContent = tanggal;
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });

        // Delete Log
        function deleteLog(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                fetch('../../controller/aksi_hapus_log.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id_log=' + id
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Data berhasil dihapus!');
                        location.reload();
                    } else {
                        alert('Gagal menghapus data: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus data');
                });
            }
        }
    </script>
</body>
</html>