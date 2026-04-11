<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../src/output.css" rel="stylesheet">
    <title>Buku Dipinjam</title>
    <link rel="icon" type="image/png" href="../../public/image/perpusku.png"> 
</head>
<body class="bg-perpusku4 min-h-screen">
<?php 
include '../partials/user_sidebar.php'; 
include '../../model/koneksi.php';

$id_user = $_SESSION['id_user'] ?? null;

if (!$id_user) {
    header('Location: ../../public/login.php');
    exit;
}

// Query untuk mendapatkan buku yang dipinjam user
$query = "SELECT p.id_peminjaman, p.tanggal_pinjam, p.tanggal_kembali, 
          b.id_buku, b.judul, b.penulis, b.kategori, b.cover, b.tahun,
          dp.id_detail
          FROM peminjaman p
          JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
          JOIN buku b ON dp.id_buku = b.id_buku
          WHERE p.id_user = '$id_user'
          ORDER BY p.tanggal_pinjam DESC";

$buku_dipinjam = mysqli_query($koneksi, $query);
$count_buku = mysqli_num_rows($buku_dipinjam);
?>

<main id="mainContent" class="flex-1 flex flex-col min-h-screen md:ml-64 transition-all duration-300 p-4 md:p-8">

    <!-- Header Section -->
    <div class="bg-linear-to-r from-perpusku1 to-perpusku2 rounded-2xl shadow-lg p-6 md:p-8 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold mb-2">Buku Dipinjam</h2>
                <p class="text-perpusku4 text-lg">Total: <span class="font-bold"><?php echo $count_buku; ?></span> buku</p>
            </div>
        </div>
    </div>

    <!-- Buku Dipinjam List - Card Kecil -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php if ($count_buku > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($buku_dipinjam)):
                $tanggal_pinjam = new DateTime($row['tanggal_pinjam']);
                $tanggal_kembali = new DateTime($row['tanggal_kembali']);
                $hari_ini = new DateTime();
                $selisih_hari = $hari_ini->diff($tanggal_kembali)->days;
                $sudah_passed = $hari_ini > $tanggal_kembali;
                $status_color = $sudah_passed ? 'red' : ($selisih_hari <= 1 ? 'orange' : 'green');
            ?>
            <div class="bg-white rounded-lg shadow p-3 flex flex-col items-center text-center hover:shadow-lg transition">
                <div class="w-20 h-28 bg-gray-100 rounded mb-2 flex items-center justify-center overflow-hidden">
                    <?php if (!empty($row['cover'])): ?>
                        <img src="../../public/cover/<?= htmlspecialchars($row['cover']) ?>" alt="Cover" class="h-full w-auto object-contain">
                    <?php else: ?>
                        <span class="text-perpusku2 text-xs">No Cover</span>
                    <?php endif; ?>
                </div>
                <div class="flex-1 w-full">
                    <h4 class="font-bold text-perpusku1 text-base mb-1 line-clamp-2"><?= htmlspecialchars($row['judul']) ?></h4>
                    <div class="text-xs text-gray-600 mb-1 truncate">Penulis: <?= htmlspecialchars($row['penulis']) ?></div>
                    <div class="text-xs text-perpusku2 mb-1 truncate">Kategori: <?= htmlspecialchars($row['kategori']) ?></div>
                    <div class="text-xs text-gray-500 mb-1">Tahun: <?= $row['tahun'] ?></div>
                    <div class="text-xs <?= $sudah_passed ? 'text-red-600' : ($selisih_hari <= 1 ? 'text-orange-600' : 'text-green-600') ?> font-semibold mb-1">
                        <?php if ($sudah_passed): ?>
                            Terlambat (<?= $selisih_hari ?> hari)
                        <?php elseif ($selisih_hari <= 1): ?>
                            Segera Kembali (<?= $selisih_hari ?> hari)
                        <?php else: ?>
                            Normal (<?= $selisih_hari ?> hari)
                        <?php endif; ?>
                    </div>
                    <div class="flex justify-between text-xs text-gray-400 mb-2">
                        <span>Pinjam: <?= $tanggal_pinjam->format('d M y') ?></span>
                        <span>Kembali: <?= $tanggal_kembali->format('d M y') ?></span>
                    </div>
                </div>
                <div class="flex flex-col gap-2 w-full mt-2">
                    <button onclick="showRequestKembali(<?= $row['id_peminjaman'] ?>, '<?= htmlspecialchars($row['judul']) ?>')"
                        class="w-full bg-perpusku1 hover:bg-perpusku2 text-white font-bold py-1 px-2 rounded text-xs transition shadow">
                        Request Kembali
                    </button>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center col-span-full">
                <h3 class="text-2xl font-bold text-perpusku1 mb-2">Belum ada buku yang dipinjam</h3>
                <p class="text-gray-600 mb-6">Silakan kunjungi halaman beranda untuk meminjam buku</p>
                <a href="landing.php" class="inline-block bg-perpusku1 hover:bg-perpusku2 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                    Pinjam Buku Sekarang
                </a>
            </div>
        <?php endif; ?>
    </div>





</main>

<!-- Modal Request Kembali -->
<div id="modalRequestKembali" class="fixed inset-0 hidden z-50 flex items-center justify-center">
    <div class="bg-white border border-perpusku1 rounded-xl shadow-xl p-8 max-w-md w-full mx-4">
        <button onclick="closeModal('modalRequestKembali')" class="absolute top-3 right-3 text-perpusku1 text-xl font-bold">&times;</button>
        
        <h3 class="text-lg font-bold text-perpusku1 mb-4">Request Pengembalian Buku</h3>
        
        <div class="mb-4 p-3 bg-blue-100 border-l-4 border-blue-500 rounded text-sm text-blue-800">
            <p><strong id="modalBukuJudul"></strong></p>
        </div>

        <form id="formRequestKembali" method="POST" action="../../controller/aksi_request_kembali.php">
            <input type="hidden" name="id_peminjaman" id="request_id_peminjaman">
            <input type="hidden" name="aksi" value="request_kembali">
            <div class="flex gap-2">
                <button type="button" onclick="closeModal('modalRequestKembali')" 
                        class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg transition">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 bg-perpusku1 hover:bg-perpusku2 text-white font-bold py-2 px-4 rounded-lg transition shadow">
                    Ajukan Request
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Notification Message -->
<?php if (isset($_SESSION['message'])): ?>
    <div class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 <?= $_SESSION['message_type'] == 'success' ? 'bg-green-500' : 'bg-red-500' ?> text-white px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 animate-fade-in">
        <span><?= $_SESSION['message'] ?></span>
        <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200 font-bold">&times;</button>
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

<script>
function showRequestKembali(id_peminjaman, judul_buku) {
    document.getElementById('request_id_peminjaman').value = id_peminjaman;
    document.getElementById('modalBukuJudul').textContent = judul_buku;
    document.getElementById('modalRequestKembali').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
</script>

</body>
</html>
