<?php
session_start();
include '../../model/koneksi.php';

// Ambil request peminjaman yang statusnya pending
$pending = mysqli_query($koneksi, "SELECT r.id_request, r.id_user, u.nama, r.id_buku, b.judul, r.tanggal_request, r.status, r.alasan_penolakan FROM request_peminjaman r JOIN users u ON r.id_user = u.id_user JOIN buku b ON r.id_buku = b.id_buku WHERE r.status = 'pending' ORDER BY r.tanggal_request ASC");

// Debug: tampilkan error jika query gagal
if (!$pending) {
    die("Query error: " . mysqli_error($koneksi));
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Peminjaman</title>
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
    <link rel="icon" type="image/png" href="../../public/image/perpusku.png">
</head>
<body class="bg-perpusku4 min-h-screen">
    <?php include '../partials/admin_sidebar.php'; ?>
    <div id="mainContent" class="flex-1 flex flex-col min-h-screen md:ml-64 transition-all duration-300 p-4 md:p-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Notifikasi Request Peminjaman Buku</h2>
                <form method="POST" action="../../controller/aksi_peminjaman.php">
                    <button type="submit" name="aksi" value="approve_all" class="bg-perpusku3 hover:bg-yellow-400 text-perpusku1 font-semibold px-4 py-2 rounded shadow">Terima Semua</button>
                </form>
            </div>
            <?php if (mysqli_num_rows($pending) === 0): ?>
                <div class="text-center text-perpusku2 font-semibold">Tidak ada request peminjaman yang menunggu konfirmasi.</div>
            <?php else: ?>
            <div class="bg-white rounded-xl shadow-xl p-6 md:p-8 mb-8">
                <div class="font-semibold text-lg mb-4 text-gray-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" /></svg>
                    Daftar Request Peminjaman
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-100 text-gray-700 uppercase">
                            <tr>
                                <th class="py-2 px-3">Nama</th>
                                <th class="py-2 px-3">Judul Buku</th>
                                <th class="py-2 px-3">Tanggal Request</th>
                                <th class="py-2 px-3">Status</th>
                                <th class="py-2 px-3">Tanggal Kembali</th>
                                <th class="py-2 px-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($pending)): ?>
                            <tr class="border-b hover:bg-blue-50">
                                <td class="py-2 px-3"><?= htmlspecialchars($row['nama']) ?></td>
                                <td class="py-2 px-3"><?= htmlspecialchars($row['judul']) ?></td>
                                <td class="py-2 px-3"><?= date('d M Y', strtotime($row['tanggal_request'])) ?></td>
                                <td class="py-2 px-3">
                                    <span class="inline-block px-2 py-1 rounded text-xs font-semibold <?= $row['status'] == 'pending' ? 'bg-yellow-100 text-yellow-700' : ($row['status'] == 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') ?>">
                                        <?= htmlspecialchars($row['status']) ?>
                                    </span>
                                </td>
                                <td class="py-2 px-3 font-semibold text-perpusku1"><?= date('d M Y', strtotime('+3 days')) ?></td>
                                <td class="py-2 px-3 flex gap-2">
                                    <form method="POST" action="../../controller/aksi_peminjaman.php" style="display:inline-block;">
                                        <input type="hidden" name="id_request" value="<?= $row['id_request'] ?>">
                                        <button type="submit" name="aksi" value="approve" class="bg-perpusku1 hover:bg-perpusku2 text-white px-3 py-1 rounded shadow text-xs">Terima</button>
                                    </form>
                                    <button type="button" onclick="showRejectModal(<?= $row['id_request'] ?>)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow text-xs">Tolak</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Tolak Request -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
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
