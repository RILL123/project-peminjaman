<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../src/output.css" rel="stylesheet">
    <title>Log Aktivitas</title>
    <link rel="icon" type="image/png" href="../../public/image/perpusku.png">
    <script src="../../public/realtime.js"></script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        @keyframes slideInScale {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        .modal-backdrop-show {
            animation: fadeIn 0.3s ease-in-out forwards;
        }
        .modal-backdrop-hide {
            animation: fadeIn 0.2s ease-in-out reverse forwards;
        }
        .modal-content-show {
            animation: slideInScale 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        .modal-content-hide {
            animation: slideInScale 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) reverse forwards;
        }
    </style>
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
                <div class="bg-linear-to-r from-perpusku1 to-perpusku2 rounded-2xl shadow-lg p-6 md:p-8 mb-8 text-white">
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
                            class="flex-1 px-4 py-3 border border-perpusku2 rounded-xl p-4 focus:border-perpusku1 transition"
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
                <div class="mb-4 flex justify-end">
                    <button onclick="printLaporan()" class="bg-perpusku1 text-white px-6 py-2 rounded-lg font-semibold shadow hover:bg-perpusku2 transition">
                        Cetak Laporan
                    </button>
                </div>
                <div id="printArea" class="bg-white rounded-xl shadow-md overflow-x-auto" style="max-height: 600px; overflow-y: auto;">
                    <table class="w-full" id="logTable">
                        <thead>
                            <tr class="bg-perpusku1 text-white">
                                <th class="px-6 py-4 text-left text-sm font-semibold">ID</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Aktivitas</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Keterangan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal & Jam</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold print-user">Aksi</th>
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

                            // Query untuk print: ambil semua data jika sedang print
                            $isPrint = isset($_GET['print']) && $_GET['print'] == '1';
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
                            
                            // jumlah total data untuk pagination
                            $count_query = "SELECT COUNT(*) AS total FROM log_aktivitas l
                                LEFT JOIN users u ON l.id_user = u.id_user
                                LEFT JOIN buku b ON l.id_buku = b.id_buku
                                $where_clause";
                            $count_result = mysqli_query($koneksi, $count_query);
                            $count_row = mysqli_fetch_assoc($count_result);
                            $total_records = $count_row['total'] ?? 0;
                            if (!isset($isPrint)) $isPrint = false;
                            if ($isPrint) {
                                $total_pages = 1;
                                $current_page = 1;
                            } else {
                                $total_pages = ceil($total_records / $items_per_page);
                            }

                            if (mysqli_num_rows($log_result) > 0):
                                $no = $offset + 1;
                                while ($log = mysqli_fetch_assoc($log_result)):
                            ?>
                            <tr class="border-b border-gray-200 hover:bg-perpusku4 transition log-row" data-search="<?= strtolower(htmlspecialchars($log['id_log'] . ' ' . $log['aktivitas'] . ' ' . $log['keterangan'] . ' ' . ($log['judul_buku'] ?? '') . ' ' . ($log['nama_user'] ?? ''))) ?>">
                                <td class="px-6 py-4 text-sm text-gray-700 row-number" data-id="<?= htmlspecialchars($log['id_log']) ?>"><?= htmlspecialchars($log['id_log']) ?></td>
                                <td class="px-6 py-4 text-sm font-semibold text-perpusku1">
                                    <?= htmlspecialchars($log['aktivitas']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= htmlspecialchars($log['keterangan']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= date('d M Y H:i', strtotime($log['tanggal'])) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 flex gap-2 print-user">
                                    <span class="hidden print:inline"><?= htmlspecialchars($log['nama_user'] ?? 'Admin') ?></span>
                                    <span class="print:hidden">
                                        <button onclick="openDetailModal('<?= htmlspecialchars($log['id_log']) ?>','<?= htmlspecialchars(addslashes($log['aktivitas'])) ?>','<?= htmlspecialchars(addslashes($log['keterangan'])) ?>','<?= htmlspecialchars(addslashes($log['judul_buku'] ?? '-')) ?>','<?= htmlspecialchars(addslashes($log['nama_user'] ?? 'Admin')) ?>','<?= date('d M Y H:i', strtotime($log['tanggal'])) ?>')" class="bg-perpusku1 text-white px-3 py-1 rounded hover:bg-perpusku2 transition text-xs font-semibold mr-1">Detail</button>
                                        <button onclick="deleteLog(<?= $log['id_log'] ?>)" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition text-xs font-semibold">Hapus</button>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
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

<!-- Modal Detail Log -->
<div id="detailModal" class="hidden fixed inset-0 flex items-center justify-center z-50">
  <div id="modalContent" class="bg-white border-3 border-perpusku1 rounded-xl shadow-lg w-full max-w-md p-6 relative">
    <button onclick="closeDetailModal()" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 text-2xl">&times;</button>
    <h3 class="text-xl font-bold mb-4 text-perpusku1">Detail Log Aktivitas</h3>
    <div class="space-y-2">
      <div><span class="font-semibold">ID Log:</span> <span id="modalId"></span></div>
      <div><span class="font-semibold">Aktivitas:</span> <span id="modalAktivitas"></span></div>
      <div><span class="font-semibold">Keterangan:</span> <span id="modalKeterangan"></span></div>
      <div><span class="font-semibold">Buku:</span> <span id="modalBuku"></span></div>
      <div><span class="font-semibold">User:</span> <span id="modalUser"></span></div>
      <div><span class="font-semibold">Tanggal & Jam:</span> <span id="modalTanggal"></span></div>
    </div>
  </div>
</div>

<script>
function printLaporan() {
    fetch('../../controller/get_all_log.php')
        .then(res => {
            if (!res.ok) throw new Error('HTTP error');
            return res.json();
        })
        .then(data => {
            let tableRows = data.map(log => `
                <tr>
                    <td style="padding:8px;border:1px solid #ccc;">${log.id_log}</td>
                    <td style="padding:8px;border:1px solid #ccc;">${log.id_user}</td>
                    <td style="padding:8px;border:1px solid #ccc;">${log.aktivitas}</td>
                    <td style="padding:8px;border:1px solid #ccc;">${log.keterangan}</td>
                    <td style="padding:8px;border:1px solid #ccc;">${formatTanggal(log.tanggal)}</td>
                </tr>
            `).join('');
            let html = `
                <html>
                <head>
                    <title>Cetak Laporan Log Aktivitas</title>
                    <link href='../../src/output.css' rel='stylesheet'>
                    <style>
                        @media print {
                            body * { display: none !important; }
                            #printArea, #printArea * { display: revert !important; box-shadow: none !important; background: white !important; }
                            #printArea { position: static !important; margin: 0 !important; padding: 0 !important; }
                        }
                        table { border-collapse: collapse; width: 100%; }
                        th, td { border: 1px solid #ccc; padding: 8px; }
                        th { background: #1e293b; color: #fff; }
                    </style>
                </head>
                <body>
                    <div class='text-center mb-6'>
                        <h2 class='text-2xl font-bold text-perpusku1'>Laporan Log Aktivitas</h2>
                        <p class='text-perpusku2'>Dicetak pada: ${formatTanggalCetak(new Date())}</p>
                    </div>
                    <div id='printArea' class='bg-white rounded-xl shadow-md overflow-x-auto'>
                        <table class='w-full'>
                            <thead>
                                <tr>
                                    <th>ID Log</th>
                                    <th>ID User</th>
                                    <th>Aktivitas</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal & Jam</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${tableRows}
                            </tbody>
                        </table>
                    </div>
                    <script>
                    window.onload = function() {
                        setTimeout(function() {
                            window.print();
                            window.onafterprint = function() { window.close(); };
                        }, 300);
                    }
                    <\/script>
                </body>
                </html>
            `;
            let printWindow = window.open('', '_blank');
            if (printWindow) {
                printWindow.document.open();
                printWindow.document.write(html);
                printWindow.document.close();
            } else {
                alert('Popup diblokir! Izinkan popup untuk mencetak laporan.');
            }
        })
        .catch(() => alert('Gagal mengambil data log untuk cetak.'));
}
function formatTanggal(tgl) {
    const d = new Date(tgl);
    if (isNaN(d)) return '-';
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ' ' + d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
}
function formatTanggalCetak(d) {
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ' ' + d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
}
function openDetailModal(id, aktivitas, keterangan, buku, user, tanggal) {
    document.getElementById('modalId').textContent = id;
    document.getElementById('modalAktivitas').textContent = aktivitas;
    document.getElementById('modalKeterangan').textContent = keterangan;
    document.getElementById('modalBuku').textContent = buku || '-';
    document.getElementById('modalUser').textContent = user;
    document.getElementById('modalTanggal').textContent = tanggal;
    
    const modal = document.getElementById('detailModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    modal.classList.add('modal-backdrop-show');
    modalContent.classList.add('modal-content-show');
}
function closeDetailModal() {
    const modal = document.getElementById('detailModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('modal-backdrop-show');
    modal.classList.add('modal-backdrop-hide');
    modalContent.classList.remove('modal-content-show');
    modalContent.classList.add('modal-content-hide');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('modal-backdrop-hide');
        modalContent.classList.remove('modal-content-hide');
    }, 300);
}
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetailModal();
    }
});
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