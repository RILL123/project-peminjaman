<?php
session_start();
include '../../model/koneksi.php';

$id_user = $_SESSION['id_user'] ?? null;
if (!$id_user) {
    header('Location: ../../public/login.php');
    exit;
}

$query = "SELECT p.id_peminjaman, b.judul, p.tanggal_pinjam, p.tanggal_kembali, p.status FROM peminjaman p JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman JOIN buku b ON dp.id_buku = b.id_buku WHERE p.id_user = '$id_user' ORDER BY p.tanggal_pinjam DESC";
$result = mysqli_query($koneksi, $query);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-perpusku4 min-h-screen">
    <div class="max-w-3xl mx-auto mt-20 bg-white rounded-xl shadow-xl p-8">
        <h2 class="text-2xl font-bold mb-4 text-perpusku1">Riwayat Peminjaman Buku</h2>
        <table class="min-w-full">
            <thead class="bg-gradient-to-r from-perpusku1 to-perpusku2 text-white">
                <tr>
                    <th class="px-4 py-3 border-b text-left font-semibold">Judul Buku</th>
                    <th class="px-4 py-3 border-b text-left font-semibold">Tanggal Pinjam</th>
                    <th class="px-4 py-3 border-b text-left font-semibold">Tanggal Kembali</th>
                    <th class="px-4 py-3 border-b text-left font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['judul']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['tanggal_pinjam']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['tanggal_kembali']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['status']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
