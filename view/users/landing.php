<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../src/output.css" rel="stylesheet">
    <title>Home</title>
    <link rel="icon" type="image/png" href="../../public/image/perpusku.png"> 
</head>
<body class="bg-perpusku4 min-h-screen">
<?php 
include '../partials/user_sidebar.php'; 
include '../../model/koneksi.php';
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$kategori = isset($_GET['kategori']) ? mysqli_real_escape_string($koneksi, $_GET['kategori']) : '';
$where = [];
if ($search) {
    $where[] = "(judul LIKE '%$search%' OR penulis LIKE '%$search%')";
}
if ($kategori) {
    $where[] = "kategori = '$kategori'";
}
$where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
$buku = mysqli_query($koneksi, "SELECT * FROM buku $where_sql ORDER BY created_at DESC");
?>
<main id="mainContent" class="flex-1 flex flex-col min-h-screen md:ml-64 transition-all duration-300 p-4 md:p-8">

    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-perpusku1 to-perpusku2 rounded-2xl shadow-lg p-6 md:p-8 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold mb-2">Selamat Datang <?php echo $_SESSION['username']; ?>!!</h2>
                <p class="text-perpusku4 text-lg">Perhatian!! Buku hanya boleh dipinjam maximal 3 hari</p>
            </div>
            <img src="../../public/image/perpusku.png" alt="Logo" class="hidden md:block w-24 h-24 rounded-full bg-white p-1" />
        </div>
    </div>
            <!-- Search Engine -->
        <form method="get" class="mb-8">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" name="search" class="w-full border-2 border-perpusku2 rounded-xl p-4 focus:border-perpusku1 focus:outline-none focus:ring-2 focus:ring-perpusku3 focus:ring-opacity-50 transition duration-300 shadow-sm" placeholder="Cari judul atau penulis..." value="<?= htmlspecialchars($search ?? '') ?>">
                </div>
                <div class="w-56">
                    <select name="kategori" class="w-full border-2 border-perpusku2 rounded-xl p-4 focus:border-perpusku1 focus:outline-none focus:ring-2 focus:ring-perpusku3 focus:ring-opacity-50 transition duration-300 shadow-sm">
                        <option value="">Semua Kategori</option>
                        <option value="Fiksi" <?= $kategori == 'Fiksi' ? 'selected' : '' ?>>Fiksi</option>
                        <option value="Non-Fiksi" <?= $kategori == 'Non-Fiksi' ? 'selected' : '' ?>>Non-Fiksi</option>
                        <option value="Komik" <?= $kategori == 'Komik' ? 'selected' : '' ?>>Komik</option>
                        <option value="Ensiklopedia" <?= $kategori == 'Ensiklopedia' ? 'selected' : '' ?>>Ensiklopedia</option>
                        <option value="Novel" <?= $kategori == 'Novel' ? 'selected' : '' ?>>Novel</option>
                    </select>
                </div>
                <button type="submit" class="bg-gradient-to-r from-perpusku2 to-perpusku1 hover:from-perpusku1 hover:to-perpusku2 text-white px-8 py-4 rounded-xl font-bold transition duration-300 shadow-lg hover:shadow-xl">Cari</button>
            </div>
        </form>
    <!-- Buku Card Section -->
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-perpusku1 mb-4">Daftar Buku</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php while ($row = mysqli_fetch_assoc($buku)): ?>
            <div class="bg-white rounded-xl shadow-lg flex flex-col overflow-hidden group">
                <div class="relative h-48 flex items-center justify-center bg-gray-100">
                    <?php if (!empty($row['cover'])): ?>
                        <img src="../../public/cover/<?= htmlspecialchars($row['cover']) ?>" alt="Cover" class="h-full w-auto object-contain group-hover:scale-105 transition duration-300">
                    <?php else: ?>
                        <span class="text-perpusku2 text-xs">Tidak ada cover</span>
                    <?php endif; ?>
                </div>
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-perpusku1 text-lg mb-1 line-clamp-2"><?= htmlspecialchars($row['judul']) ?></h4>
                        <p class="text-sm text-gray-600 mb-1">Penulis: <?= htmlspecialchars($row['penulis']) ?></p>
                        <p class="text-xs text-perpusku2 mb-1">Kategori: <span class="font-semibold"><?= htmlspecialchars($row['kategori']) ?></span></p>
                        <p class="text-xs text-gray-500">Tahun: <?= $row['tahun'] ?></p>
                        <p class="text-xs text-perpusku3">Stok: <?= $row['stok'] ?></p>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <button onclick="showDetail(<?= $row['id_buku'] ?>)" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg font-semibold shadow transition">Detail</button>
                        <button onclick="showPinjam(<?= $row['id_buku'] ?>)" class="flex-1 bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg font-semibold shadow transition" <?= ($row['stok'] <= 0 ? 'disabled style=\'background:#ccc;cursor:not-allowed\'' : '') ?>>Pinjam</button>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <!-- Modal Detail Buku -->
    <div id="modalDetail" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl p-8 max-w-md w-full relative">
            <button onclick="closeModal('modalDetail')" class="absolute top-3 right-3 text-perpusku1 text-xl font-bold">&times;</button>
            <div id="detailContent"></div>
        </div>
    </div>
    <!-- Modal Pinjam Buku -->
    <div id="modalPinjam" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl p-8 max-w-md w-full relative max-h-96 overflow-y-auto">
            <button onclick="closeModal('modalPinjam')" class="absolute top-3 right-3 text-perpusku1 text-xl font-bold">&times;</button>
            <h3 class="text-lg font-bold text-perpusku1 mb-4">Peminjaman Buku</h3>
            <form id="formPinjam" method="POST" action="../../controller/aksi_peminjaman_user.php">
                <input type="hidden" name="id_buku" id="pinjam_id_buku">
                <input type="hidden" name="aksi" value="pinjam">
                
                <div class="mb-2 p-3 bg-yellow-100 border-l-4 border-yellow-500 rounded text-sm text-yellow-800">
                    <strong>Perhatian!</strong> Durasi pinjam maksimal adalah <strong>3 hari</strong>
                </div>
                
                <div class="mb-4">
                    <label for="pinjam_tgl_kembali" class="block font-semibold text-perpusku1 mb-2">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali" id="pinjam_tgl_kembali" class="w-full border border-perpusku2 rounded-lg p-2" onchange="hitungDurasi()">
                    <small id="durasi_info" class="text-gray-600 mt-1 block">Pilih tanggal untuk melihat durasi pinjam</small>
                </div>
                
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg py-2 shadow transition">Konfirmasi Pinjam</button>
            </form>
            <?php if (isset($_SESSION['message'])): ?>
                <div class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-perpusku1 text-white px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 animate-fade-in">
                    <span><?= $_SESSION['message'] ?></span>
                    <button onclick="this.parentElement.remove()" class="ml-4 text-perpusku3 hover:text-white font-bold">&times;</button>
                </div>
                <style>
                @keyframes fade-in {
                    from { opacity: 0; transform: translateY(-20px) scale(0.95); }
                    to { opacity: 1; transform: translateY(0) scale(1); }
                }
                .animate-fade-in { animation: fade-in 0.4s; }
                </style>
                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
            <?php endif; ?>
        </div>
    </div>
</main>
<script>
function showDetail(id) {
    fetch('detail_buku.php?id=' + id)
        .then(res => res.text())
        .then(html => {
            document.getElementById('detailContent').innerHTML = html;
            document.getElementById('modalDetail').classList.remove('hidden');
        });
}
function showPinjam(id) {
    document.getElementById('pinjam_id_buku').value = id;
    document.getElementById('modalPinjam').classList.remove('hidden');
}
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
function hitungDurasi() {
    const tanggalKembali = document.getElementById('pinjam_tgl_kembali').value;
    const durasiInfo = document.getElementById('durasi_info');
    
    if (!tanggalKembali) {
        durasiInfo.textContent = 'Pilih tanggal untuk melihat durasi pinjam';
        durasiInfo.className = 'text-gray-600 mt-1 block';
        return;
    }
    
    const hari_ini = new Date();
    hari_ini.setHours(0, 0, 0, 0);
    
    const tanggal_kembali = new Date(tanggalKembali);
    const selisih_waktu = tanggal_kembali - hari_ini;
    const selisih_hari = Math.ceil(selisih_waktu / (1000 * 60 * 60 * 24));
    
    if (selisih_hari < 1) {
        durasiInfo.textContent = 'X Tanggal kembali harus lebih dari hari ini';
        durasiInfo.className = 'text-red-600 mt-1 block font-semibold';
    } else if (selisih_hari > 3) {
        durasiInfo.textContent = 'Durasi ' + selisih_hari + ' hari melebihi batas maksimal 3 hari. Request akan ditolak!';
        durasiInfo.className = 'X text-red-600 mt-1 block font-semibold';
    } else {
        durasiInfo.textContent = '✓ Durasi pinjam: ' + selisih_hari + ' hari (Terdaftar)';
        durasiInfo.className = 'text-green-600 mt-1 block font-semibold';
    }
}
function redirectNotifikasi() {
    var id_buku = document.getElementById('pinjam_id_buku').value;
    var tanggal_kembali = document.getElementById('pinjam_tgl_kembali').value;
    if (!tanggal_kembali) {
        alert('Tanggal kembali harus diisi!');
        return;
    }
    window.location.href = '../../view/admin/notifikasi.php?id_buku=' + encodeURIComponent(id_buku) + '&tanggal_kembali=' + encodeURIComponent(tanggal_kembali);
}
</script>
</body>
</html>