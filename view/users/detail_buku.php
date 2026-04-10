<?php
include '../../model/koneksi.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$simple = isset($_GET['simple']) ? true : false;
$buku = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku='$id'");
$row = mysqli_fetch_assoc($buku);
if (!$row) {
    echo '<div class="text-center text-perpusku2">Buku tidak ditemukan.</div>';
    exit;
}
?>
<div class="flex flex-col items-center w-full">
    <?php if (!empty($row['cover'])): ?>
        <img src="../../public/cover/<?= htmlspecialchars($row['cover']) ?>" alt="Cover" class="h-32 rounded shadow border border-perpusku3 mb-4">
    <?php else: ?>
        <span class="text-perpusku2 mb-4">Tidak ada cover</span>
    <?php endif; ?>
    <h4 class="text-xl font-bold text-perpusku1 mb-2"><?= htmlspecialchars($row['judul']) ?></h4>
    <div class="mb-2 text-gray-700">Penulis: <?= htmlspecialchars($row['penulis']) ?></div>
    <div class="mb-2 text-perpusku2">Kategori: <span class="font-semibold"><?= htmlspecialchars($row['kategori']) ?></span></div>
    <div class="mb-2 text-gray-500">Tahun: <?= $row['tahun'] ?></div>
    <div class="mb-2 <?= $row['stok'] <= 0 ? 'text-red-600 font-bold' : 'text-perpusku3' ?>">Stok: <?= $row['stok'] ?></div>

    <?php if (!$simple): ?>
        <?php
        // Ambil data peminjaman buku ini
        $peminjaman_query = mysqli_query($koneksi, "
            SELECT p.id_peminjaman, p.tanggal_pinjam, p.tanggal_kembali, u.nama 
            FROM peminjaman p
            JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
            JOIN users u ON p.id_user = u.id_user
            WHERE dp.id_buku = '$id'
            ORDER BY p.tanggal_pinjam DESC
            LIMIT 5
        ");
        $peminjaman_data = [];
        while ($pinjam = mysqli_fetch_assoc($peminjaman_query)) {
            $peminjaman_data[] = $pinjam;
        }
        ?>
        <!-- Data Peminjaman -->
        <?php if (!empty($peminjaman_data)): ?>
        <div class="w-full mt-4 pt-4 border-t border-gray-300">
            <h5 class="text-sm font-bold text-perpusku1 mb-3">Riwayat Peminjaman:</h5>
            <div class="max-h-48 overflow-y-auto">
                <?php foreach ($peminjaman_data as $item): ?>
                <div class="mb-3 p-3 bg-perpusku4 rounded-lg text-sm border-l-4 border-perpusku3">
                    <div class="font-semibold text-perpusku1">Peminjam: <?= htmlspecialchars($item['nama']) ?></div>
                    <div class="text-gray-700">Tgl Pinjam: <?= date('d M Y', strtotime($item['tanggal_pinjam'])) ?></div>
                    <div class="text-gray-700">Tgl Kembali: <?= date('d M Y', strtotime($item['tanggal_kembali'])) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="w-full mt-4 pt-4 border-t border-gray-300">
            <p class="text-sm text-gray-500 italic">Belum ada riwayat peminjaman</p>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
