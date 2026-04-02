<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        perpusku1: '#1A3263',
                        perpusku2: '#547792',
                        perpusku3: '#FAB95B',
                        perpusku4: '#E8E2DB',
                    }
                }
            }
        }
    </script>
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
        <!-- Main content -->
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
                    <input 
                        type="text" 
                        id="searchInput" 
                        placeholder="Cari aktivitas, keterangan, buku, atau user..." 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-perpusku1 transition"
                    />
                    <div class="mt-2 text-sm text-gray-600">
                        Total data: <span id="totalData">0</span> | Ditampilkan: <span id="displayedData">0</span>
                    </div>
                </div>

                <!-- Log Aktivitas Table -->
                <div class="bg-white rounded-xl shadow-md overflow-x-auto" style="max-height: 600px; overflow-y: auto;">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-perpusku1 text-white">
                                <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Aktivitas</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Keterangan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Buku</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">User</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal & Jam</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include '../../model/koneksi.php';

                            // Get all logs without pagination
                            $log_query = "SELECT l.*, 
                                u.nama AS nama_user,
                                b.judul AS judul_buku
                                FROM log_aktivitas l
                                LEFT JOIN users u ON l.id_user = u.id_user
                                LEFT JOIN buku b ON l.id_buku = b.id_buku
                                ORDER BY l.tanggal DESC";
                            $log_result = mysqli_query($koneksi, $log_query);
                            
                            if (mysqli_num_rows($log_result) > 0):
                                $no = 1;
                                while ($log = mysqli_fetch_assoc($log_result)):
                            ?>
                            <tr class="border-b border-gray-200 hover:bg-perpusku4 transition log-row" data-search="<?= strtolower(htmlspecialchars($log['aktivitas'] . ' ' . $log['keterangan'] . ' ' . ($log['judul_buku'] ?? '') . ' ' . ($log['nama_user'] ?? ''))) ?>">
                                <td class="px-6 py-4 text-sm text-gray-700 row-number"><?= $no++ ?></td>
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
                                    <?= $log['nama_user'] ? htmlspecialchars($log['nama_user']) : 'Admin' ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= date('d M Y H:i', strtotime($log['tanggal'])) ?>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada data aktivitas.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="../../public/sidebar.js"></script>
    <script>
        // Real-time search functionality
        const searchInput = document.getElementById('searchInput');
        const logRows = document.querySelectorAll('.log-row');
        const totalDataSpan = document.getElementById('totalData');
        const displayedDataSpan = document.getElementById('displayedData');

        // Initialize counts on page load
        function initCounts() {
            totalDataSpan.textContent = logRows.length;
            updateDisplayCount();
        }

        initCounts();

        // Search event listener
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            let visibleCount = 0;

            logRows.forEach(row => {
                const searchData = row.getAttribute('data-search');
                
                if (searchTerm === '' || searchData.includes(searchTerm)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            displayedDataSpan.textContent = visibleCount;
        });

        function updateDisplayCount() {
            let visibleCount = 0;
            logRows.forEach(row => {
                if (row.style.display !== 'none') {
                    visibleCount++;
                }
            });
            displayedDataSpan.textContent = visibleCount;
        }

        // Clear search button (optional - you can add this to HTML)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchInput.value !== '') {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('keyup'));
            }
        });
    </script>
</body>
</html>
