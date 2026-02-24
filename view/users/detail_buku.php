<?php
include '../../model/koneksi.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$buku = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku='$id'");
$row = mysqli_fetch_assoc($buku);
if (!$row) {
    echo '<div class="text-center text-perpusku2">Buku tidak ditemukan.</div>';
    exit;
}
?>
<div class="flex flex-col items-center">
    <?php if (!empty($row['cover'])): ?>
        <img src="../../public/cover/<?= htmlspecialchars($row['cover']) ?>" alt="Cover" class="h-32 rounded shadow border border-perpusku3 mb-4">
    <?php else: ?>
        <span class="text-perpusku2 mb-4">Tidak ada cover</span>
    <?php endif; ?>
    <h4 class="text-xl font-bold text-perpusku1 mb-2"><?= htmlspecialchars($row['judul']) ?></h4>
    <div class="mb-2 text-gray-700">Penulis: <?= htmlspecialchars($row['penulis']) ?></div>
    <div class="mb-2 text-perpusku2">Kategori: <span class="font-semibold"><?= htmlspecialchars($row['kategori']) ?></span></div>
    <div class="mb-2 text-gray-500">Tahun: <?= $row['tahun'] ?></div>
    <div class="mb-2 text-perpusku3">Stok: <?= $row['stok'] ?></div>
    <div class="mb-2 text-gray-600">Deskripsi: <?= htmlspecialchars($row['deskripsi'] ?? '-') ?></div>
</div>
