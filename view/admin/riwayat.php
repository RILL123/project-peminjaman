<?php
session_start();
include '../../model/koneksi.php';

$id_user = $_SESSION['id_user'] ?? null;
if (!$id_user) {
    header('Location: ../../public/login.php');
    exit;
}

$query = "SELECT p.id_peminjaman, b.judul, p.tanggal_pinjam, p.tanggal_kembali FROM peminjaman p JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman JOIN buku b ON dp.id_buku = b.id_buku WHERE p.id_user = '$id_user' ORDER BY p.tanggal_pinjam DESC";
$result = mysqli_query($koneksi, $query);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman</title>
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
        <h2 class="text-2xl font-bold mb-4 text-perpusku1">Aktifitas Tertbaru</h2>
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
