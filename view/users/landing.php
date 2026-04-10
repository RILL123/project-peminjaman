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
    <div class="bg-linear-to-r from-perpusku1 to-perpusku2 rounded-2xl shadow-lg p-6 md:p-8 mb-8 text-white">
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
                <button type="submit" class="bg-linear-to-r from-perpusku2 to-perpusku1 hover:from-perpusku1 hover:to-perpusku2 text-white px-8 py-4 rounded-xl font-bold transition duration-300 shadow-lg hover:shadow-2xl">Cari</button>
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
                        <button onclick="showPinjam(<?= $row['id_buku'] ?>, '<?= htmlspecialchars($row['judul']) ?>', <?= $row['stok'] ?>)" class="flex-1 bg-perpusku1 hover:bg-perpusku2 text-white px-3 py-2 rounded-lg font-semibold shadow transition" <?= $row['stok'] <= 0 ? 'disabled' : '' ?>>Pinjam</button>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <!-- Modal Detail Buku -->
    <div id="modalDetail" class="fixed inset-0 border hidden z-50 flex items-center justify-center">
        <div class="bg-white border border-perpusku1 rounded-xl shadow-xl p-8 max-w-md w-full relative">
            <button onclick="closeModal('modalDetail')" class="absolute top-3 right-3 text-perpusku1 text-xl font-bold">&times;</button>
            <div id="detailContent"></div>
        </div>
    </div>

    <!-- Modal Pinjam Buku -->
    <div id="modalPinjam" class="fixed inset-0 border hidden z-50 flex items-center justify-center">
        <div class="bg-white border border-perpusku1 rounded-xl shadow-xl p-8 max-w-md w-full relative">
            <button onclick="closeModal('modalPinjam')" class="absolute top-3 right-3 text-perpusku1 text-xl font-bold">&times;</button>
            <h3 class="text-lg font-bold text-perpusku1 mb-4">Form Peminjaman Buku</h3>
            <form id="formPinjam" method="POST" action="../../controller/aksi_peminjaman_user.php" onsubmit="return validatePinjam()">
                <input type="hidden" name="id_buku" id="pinjam_id_buku">
                <div class="mb-2">
                    <label class="block text-sm font-semibold mb-1">Judul Buku</label>
                    <input type="text" id="pinjam_judul" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-semibold mb-1">Nama Peminjam</label>
                    <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100" value="<?= htmlspecialchars($_SESSION['username']) ?>" readonly>
                </div>
                <div class="mb-2 flex gap-2">
                    <div class="flex-1">
                        <label class="block text-sm font-semibold mb-1">Tanggal Pinjam</label>
                        <input type="text" name="tanggal_pinjam" id="pinjam_tgl_pinjam" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-semibold mb-1">Tanggal Kembali</label>
                        <input type="date" name="tanggal_kembali" id="pinjam_tgl_kembali" class="w-full border rounded px-3 py-2" required>
                        <div id="pinjamKembaliInfo" class="text-xs mt-1"></div>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-semibold mb-1">Jumlah Pinjam</label>
                    <input type="number" name="jumlah" id="pinjam_jumlah" class="w-full border rounded px-3 py-2" min="1" value="1" required>
                    <input type="hidden" id="pinjam_stok">
                    <div id="pinjamError" class="text-red-500 text-xs mt-1 hidden"></div>
                </div>
                <div class="flex gap-2 mt-4">
                    <button type="button" onclick="closeModal('modalPinjam')" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg transition">Batal</button>
                    <button type="submit" class="flex-1 bg-perpusku1 hover:bg-perpusku2 text-white font-bold py-2 px-4 rounded-lg transition shadow">Pinjam</button>
                </div>
            </form>
        </div>
    </div>
</main>
<script>
function showDetail(id) {
    fetch('detail_buku.php?id=' + id + '&simple=1')
        .then(res => res.text())
        .then(html => {
            document.getElementById('detailContent').innerHTML = html;
            document.getElementById('modalDetail').classList.remove('hidden');
        });
}

function showPinjam(id_buku, judul, stok) {
    document.getElementById('pinjam_id_buku').value = id_buku;
    document.getElementById('pinjam_judul').value = judul;
    document.getElementById('pinjam_stok').value = stok;
    document.getElementById('pinjam_jumlah').value = 1;
    document.getElementById('pinjamError').classList.add('hidden');
    document.getElementById('pinjamKembaliInfo').textContent = '';
    document.getElementById('pinjamKembaliInfo').className = 'text-xs mt-1';
    // Set tanggal pinjam hari ini dan default kembali +3 hari
    const today = new Date();
    const tglPinjam = today.toISOString().slice(0,10);
    const tglKembali = new Date(today.getTime() + 3*24*60*60*1000).toISOString().slice(0,10);
    document.getElementById('pinjam_tgl_pinjam').value = tglPinjam;
    document.getElementById('pinjam_tgl_kembali').value = tglKembali;
    document.getElementById('modalPinjam').classList.remove('hidden');
}

function validatePinjam() {
    const jumlah = parseInt(document.getElementById('pinjam_jumlah').value);
    const stok = parseInt(document.getElementById('pinjam_stok').value);
    const tglPinjam = document.getElementById('pinjam_tgl_pinjam').value;
    const tglKembali = document.getElementById('pinjam_tgl_kembali').value;
    const info = document.getElementById('pinjamKembaliInfo');
    info.textContent = '';
    info.className = 'text-xs mt-1';
    // Validasi jumlah
    if (jumlah > stok) {
        document.getElementById('pinjamError').textContent = 'Jumlah pinjam melebihi stok yang tersedia!';
        document.getElementById('pinjamError').classList.remove('hidden');
        return false;
    }
    document.getElementById('pinjamError').classList.add('hidden');
    // Validasi tanggal kembali
    const d1 = new Date(tglPinjam);
    const d2 = new Date(tglKembali);
    const diff = Math.ceil((d2 - d1) / (1000*60*60*24));
    if (diff < 1) {
        info.textContent = 'Tanggal kembali minimal 1 hari setelah tanggal pinjam!';
        info.className = 'text-xs mt-1 text-red-500';
        return false;
    } else if (diff > 3) {
        info.textContent = 'Tanggal kembali tidak boleh lebih dari 3 hari!';
        info.className = 'text-xs mt-1 text-red-500';
        return false;
    } else {
        info.textContent = 'Bisa meminjam selama ' + diff + ' hari.';
        info.className = 'text-xs mt-1 text-green-600';
    }
    return true;
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
// Update info saat tanggal kembali diubah
document.addEventListener('DOMContentLoaded', function() {
    var tglKembali = document.getElementById('pinjam_tgl_kembali');
    if (tglKembali) {
        tglKembali.addEventListener('input', function() {
            validatePinjam();
        });
    }
});
</script>
</body>
</html>