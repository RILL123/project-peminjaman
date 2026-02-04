
<?php
include_once '../../model/koneksi.php';

// Ambil data peminjaman yang belum diproses
$query = "SELECT p.id_peminjaman, p.id_user, u.nama, p.tanggal_pinjam, p.tanggal_kembali, p.status
          FROM peminjaman p
          JOIN users u ON p.id_user = u.id_user
          WHERE p.status = 'pending'
          ORDER BY p.tanggal_pinjam DESC";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}

$aksi = $_GET['aksi'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Peminjaman - Admin</title>
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

</head>
<body class="bg-perpusku4">
    <?php include '../partials/admin_sidebar.php'; ?>
    <div id="mainContent" class="md:ml-64 transition-all duration-300 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <img src="../../public/image/info.png" class="w-8 h-8" />
                    <h2 class="text-3xl font-extrabold text-perpusku1 tracking-tight">Data Peminjaman</h2>
                </div>
                <a href="transaksi.php?aksi=tambah" class="inline-flex items-center gap-2 px-5 py-2 bg-perpusku3 text-perpusku1 font-semibold rounded-lg shadow hover:bg-yellow-400 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah Peminjaman
                </a>
            </div>
            <div id="tableSection" class="overflow-x-auto rounded-2xl shadow-lg bg-white border border-gray-200">
                <table class="min-w-full">
                    <thead class="bg-gradient-to-r from-perpusku1 to-perpusku2 text-white">
                        <tr>
                            <th class="px-4 py-3 border-b text-left font-semibold">No</th>
                            <th class="px-4 py-3 border-b text-left font-semibold">Nama Peminjam</th>
                            <th class="px-4 py-3 border-b text-left font-semibold">Judul Buku</th>
                             <th class="px-4 py-3 border-b text-left font-semibold">Kategori</th>
                            <th class="px-4 py-3 border-b text-left font-semibold">Tanggal Pinjam</th>
                            <th class="px-4 py-3 border-b text-left font-semibold">Tanggal Kembali</th>
                            <th class="px-4 py-3 border-b text-left font-semibold">Status</th>
                            <th class="px-4 py-3 border-b text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php 
                        // ...existing code...
                        ?>
                    </tbody>
                </table>
                <div class="flex flex-col items-center py-8 text-gray-400" id="emptyState" style="display:none;">
                    <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 3v4M8 3v4m-5 4h18"/></svg>
                    <span class="text-lg">Belum ada data peminjaman</span>
                </div>
            </div>
            <!-- Halaman Detail -->
            <div id="detailSection" class="hidden bg-white rounded-2xl shadow-xl p-10 max-w-lg mx-auto mt-12 border border-perpusku2 animate-fadeIn">
                <div class="flex items-center gap-3 mb-6">
                    <svg class="w-8 h-8 text-perpusku3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <h3 class="text-2xl font-bold text-perpusku1">Detail Peminjaman</h3>
                </div>
                <div class="space-y-4 mb-8">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-perpusku2">ID Peminjaman:</span> <span id="detail_id" class="text-gray-700"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-perpusku2">Nama Peminjam:</span> <span id="detail_nama" class="text-gray-700"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-perpusku2">Tanggal Pinjam:</span> <span id="detail_tgl_pinjam" class="text-gray-700"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-perpusku2">Tanggal Kembali:</span> <span id="detail_tgl_kembali" class="text-gray-700"></span>
                    </div>
                </div>
                <button onclick="backToTable()" class="px-6 py-2 bg-perpusku3 text-perpusku1 font-semibold rounded-lg shadow hover:bg-yellow-400 transition">Kembali</button>
            </div>
            <!-- Halaman Penolakan -->
            <div id="rejectSection" class="hidden bg-white rounded-2xl shadow-xl p-10 max-w-lg mx-auto mt-12 border border-red-200 animate-fadeIn">
                <div class="flex items-center gap-3 mb-6">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-1.414 1.414M6.343 17.657l-1.414-1.414M5.636 5.636l1.414 1.414M17.657 17.657l1.414-1.414M12 8v4m0 4h.01"/></svg>
                    <h3 class="text-2xl font-bold text-red-500">Tolak Peminjaman</h3>
                </div>
                <form action="../../controller/aksi_peminjaman.php" method="POST">
                    <input type="hidden" name="id_peminjaman" id="reject_id">
                    <input type="hidden" name="aksi" value="reject">
                    <div class="mb-4 flex items-center gap-2">
                        <span class="font-semibold text-perpusku2">Peminjam:</span> <span id="reject_nama" class="text-gray-700"></span>
                    </div>
                    <div class="mb-6">
                        <label for="alasan" class="block mb-1 font-medium text-red-500">Alasan Penolakan:</label>
                        <textarea class="w-full border-2 border-red-200 rounded p-3 focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" id="alasan" name="alasan" rows="4" placeholder="Masukkan alasan penolakan..." required></textarea>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="backToTable()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-red-500 text-white font-semibold rounded-lg shadow hover:bg-red-600 transition">Tolak Peminjaman</button>
                    </div>
                </form>
            </div>
        <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.5s cubic-bezier(.4,0,.2,1);
        }
        </style>
        </div>

    <script>
        // Navigasi antar section
        function showSection(section) {
            document.getElementById('tableSection').classList.add('hidden');
            document.getElementById('detailSection').classList.add('hidden');
            document.getElementById('rejectSection').classList.add('hidden');
            document.getElementById(section).classList.remove('hidden');
        }
        function backToTable() {
            showSection('tableSection');
        }
        function setDetailData(id, nama, tglPinjam, tglKembali) {
            document.getElementById('detail_id').textContent = id;
            document.getElementById('detail_nama').textContent = nama;
            document.getElementById('detail_tgl_pinjam').textContent = new Date(tglPinjam).toLocaleDateString('id-ID');
            document.getElementById('detail_tgl_kembali').textContent = new Date(tglKembali).toLocaleDateString('id-ID');
            showSection('detailSection');
        }
        function setRejectData(id, nama) {
            document.getElementById('reject_id').value = id;
            document.getElementById('reject_nama').textContent = nama;
            document.getElementById('alasan').value = '';
            showSection('rejectSection');
        }
    </script>
</body>
</html>

<?php mysqli_close($koneksi); ?>
