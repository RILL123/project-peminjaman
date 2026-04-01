
<?php
include_once '../../model/koneksi.php';

$query = "SELECT 
    p.id_peminjaman,
    u.id_user,
    u.nama as nama_user,
    b.id_buku,
    b.judul,
    b.kategori,
    b.penulis,
    b.cover,
    p.tanggal_pinjam,
    p.tanggal_kembali
FROM peminjaman p
JOIN users u ON p.id_user = u.id_user
JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
JOIN buku b ON dp.id_buku = b.id_buku
ORDER BY p.tanggal_pinjam DESC
";



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
    <title>Kelola Peminjaman</title>
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
<body class="bg-perpusku4">
    <?php include '../partials/admin_sidebar.php'; ?>
    <div id="mainContent" class="md:ml-64 transition-all duration-300 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <img src="../../public/image/info.png" class="w-8 h-8" />
                    <h2 class="text-3xl font-extrabold text-perpusku1 tracking-tight">Data Peminjaman</h2>
                </div>
                <a href="crud_transaksi.php" class="inline-flex items-center gap-2 px-5 py-2 bg-perpusku3 text-perpusku1 font-semibold rounded-lg shadow hover:bg-yellow-400 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah Peminjaman
                </a>
            </div>
            <div class="bg-white rounded-xl shadow-xl p-6 md:p-8 mb-8" id="tableSection">
                <div class="font-semibold text-lg mb-4 text-gray-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" /></svg>
                    Daftar Peminjaman Buku
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-100 text-gray-700 uppercase">
                            <tr>
                                <th class="py-2 px-3">No</th>
                                <th class="py-2 px-3">Nama Peminjam</th>
                                <th class="py-2 px-3">Judul Buku</th>
                                <th class="py-2 px-3">Kategori</th>
                                <th class="py-2 px-3">Tanggal Pinjam</th>
                                <th class="py-2 px-3">Tanggal Kembali</th>
                                <th class="py-2 px-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                                                <?php 
                                                $no = 1;
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<tr class='border-b hover:bg-blue-50'>";
                                                        echo "<td class='py-2 px-3'>{$no}</td>";
                                                        echo "<td class='py-2 px-3'>".htmlspecialchars($row['nama_user'])."</td>";
                                                        echo "<td class='py-2 px-3'>{$row['judul']}</td>";
                                                        echo "<td class='py-2 px-3'>{$row['kategori']}</td>";
                                                        echo "<td class='py-2 px-3'>".date('d-m-Y', strtotime($row['tanggal_pinjam']))."</td>";
                                                        echo "<td class='py-2 px-3'>".date('d-m-Y', strtotime($row['tanggal_kembali']))."</td>";
                                                        echo "<td class='py-2 px-3 flex gap-2'>
                                                            <form action='../../controller/aksi_peminjaman.php' method='POST' style='display:inline;'>
                                                                <input type='hidden' name='aksi' value='kembalikan'>
                                                                <input type='hidden' name='id_peminjaman' value='{$row['id_peminjaman']}'>
                                                                <input type='hidden' name='id_buku' value='{$row['id_buku']}'>
                                                                <button type='submit' class='bg-perpusku1 text-white px-3 py-1 rounded shadow text-xs font-semibold transition'>Kembalikan Buku</button>
                                                            </form>
                                                            <button type='button' onclick='printStrukJS(" . json_encode($row['id_peminjaman']) . ", " . json_encode($row['nama_user']) . ", " . json_encode($row['id_user']) . ", " . json_encode($row['judul']) . ", " . json_encode($row['kategori']) . ", " . json_encode($row['penulis']) . ", " . json_encode($row['tanggal_pinjam']) . ", " . json_encode($row['tanggal_kembali']) . ", " . json_encode($row['cover']) . ")' class='bg-perpusku3 text-perpusku1 px-3 py-1 rounded shadow text-xs font-semibold transition hover:bg-yellow-400'>Print</button>
                                                        </td>";
                                                        echo "</tr>";
                                                        $no++;
                                                }
                                                ?>
                    </tbody>
                </table>
                <div class="flex flex-col items-center py-8 text-gray-400" id="emptyState" style="display:none;">
                    <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 3v4M8 3v4m-5 4h18"/></svg>
                    <span class="text-lg">Belum ada data peminjaman</span>
                </div>
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
function printStrukJS(id, nama_user, id_user, judul, kategori, penulis, tanggal_pinjam, tanggal_kembali, cover) {
    let strukHTML = `
        <div style='font-family:sans-serif;padding:30px;max-width:350px;'>
            <h2 style='color:#1A3263;margin-bottom:16px;'>Struk Transaksi Peminjaman Buku</h2>
            <div style='margin-bottom:8px;'><b>ID Peminjaman:</b> ${id}</div>
            <div style='margin-bottom:8px;'><b>Nama Peminjam:</b> ${nama_user} <span style='font-size:11px;color:#547792;'>(ID: ${id_user})</span></div>
            <div style='margin-bottom:8px;'><b>Judul Buku:</b> ${judul}</div>
            <div style='margin-bottom:8px;'><b>Kategori:</b> ${kategori}</div>
            <div style='margin-bottom:8px;'><b>Penulis:</b> ${penulis}</div>
            <div style='margin-bottom:8px;'><b>Tanggal Pinjam:</b> ${new Date(tanggal_pinjam).toLocaleDateString('id-ID')}</div>
            <div style='margin-bottom:8px;'><b>Tanggal Kembali:</b> ${new Date(tanggal_kembali).toLocaleDateString('id-ID')}</div>
            <div style='margin-bottom:8px;'><b>Dicetak pada:</b> ${new Date().toLocaleString('id-ID')}</div>
            <div style='margin-bottom:8px;'><b>Cover:</b> ` + (cover && cover !== 'null' && cover !== '' ? `<img src='../../public/cover/${cover}' style='height:60px;margin-top:8px;border-radius:8px;border:1px solid #FAB95B;'>` : '<span style="color:#547792;">(tidak ada cover)</span>') + `</div>
        </div>
    `;
    let win = window.open('', 'PrintWindow', 'width=400,height=600');
    win.document.write(`
        <html><head><title>Struk Transaksi Peminjaman Buku</title></head><body>${strukHTML}</body></html>
    `);
    win.document.close();
    win.focus();
    win.print();
}
</script>

    <script>
    </script>
</body>
</html>

<?php mysqli_close($koneksi); ?>
