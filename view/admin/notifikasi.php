<?php
session_start();
include '../../model/koneksi.php';

$pending = mysqli_query($koneksi, "SELECT p.id_peminjaman, p.id_user, u.nama, b.judul, b.penulis, b.kategori, p.tanggal_kembali, p.tanggal_pinjam, b.cover FROM peminjaman p JOIN users u ON p.id_user = u.id_user JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman JOIN buku b ON dp.id_buku = b.id_buku WHERE p.status = 'pending' ORDER BY p.tanggal_pinjam ASC");

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Peminjaman - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-perpusku4 min-h-screen">
    <div class="max-w-3xl mx-auto mt-20 bg-white rounded-xl shadow-xl p-8">
        <h2 class="text-2xl font-bold mb-6 text-perpusku1">Notifikasi Peminjaman Buku (Menunggu Konfirmasi)</h2>
        <?php if (mysqli_num_rows($pending) === 0): ?>
            <div class="text-center text-perpusku2 font-semibold">Tidak ada peminjaman yang menunggu konfirmasi.</div>
        <?php else: ?>
            <table class="min-w-full mb-8">
                <thead class="bg-gradient-to-r from-perpusku1 to-perpusku2 text-white">
                    <tr>
                        <th class="px-4 py-3 border-b text-left font-semibold">Nama</th>
                        <th class="px-4 py-3 border-b text-left font-semibold">Judul Buku</th>
                        <th class="px-4 py-3 border-b text-left font-semibold">Tanggal Pinjam</th>
                        <th class="px-4 py-3 border-b text-left font-semibold">Tanggal Kembali</th>
                        <th class="px-4 py-3 border-b text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                <?php while ($row = mysqli_fetch_assoc($pending)): ?>
                    <tr>
                        <td class="px-4 py-3"><?= htmlspecialchars($row['nama']) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($row['judul']) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($row['tanggal_pinjam']) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($row['tanggal_kembali']) ?></td>
                        <td class="px-4 py-3">
                            <form method="POST" action="../../controller/aksi_peminjaman.php" style="display:inline-block;">
                                <input type="hidden" name="id_peminjaman" value="<?= $row['id_peminjaman'] ?>">
                                <button type="submit" name="aksi" value="approve" class="bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg px-4 py-2 shadow transition mr-2">Terima</button>
                                <button type="submit" name="aksi" value="reject" class="bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg px-4 py-2 shadow transition">Tolak</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
