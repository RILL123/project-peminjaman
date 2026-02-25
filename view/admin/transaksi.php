
<?php
include_once '../../model/koneksi.php';

$query = "SELECT 
    p.id_peminjaman,
    u.id_user,
    u.nama as nama_user,
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
                            <th class="px-4 py-3 border-b text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                                                <?php 
                                                $no = 1;
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<tr>";
                                                        echo "<td class='px-4 py-3'>{$no}</td>";
                                                        echo "<td class='px-4 py-3'>".htmlspecialchars($row['nama_user'])."</td>";
                                                        echo "<td class='px-4 py-3'>{$row['judul']}</td>";
                                                        echo "<td class='px-4 py-3'>{$row['kategori']}</td>";
                                                        echo "<td class='px-4 py-3'>".date('d-m-Y', strtotime($row['tanggal_pinjam']))."</td>";
                                                        echo "<td class='px-4 py-3'>".date('d-m-Y', strtotime($row['tanggal_kembali']))."</td>";
                                                        echo "<td class='px-4 py-3'>
                                                            <button 
                                                                onclick=\"setDetailData('{$row['id_peminjaman']}', '{$row['nama_user']}', '{$row['id_user']}', '{$row['tanggal_pinjam']}', '{$row['tanggal_kembali']}', '{$row['judul']}', '{$row['kategori']}', '{$row['penulis']}', '{$row['cover']}')\" 
                                                                class='bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold shadow transition mr-2'
                                                                data-judul='{$row['judul']}'
                                                                data-kategori='{$row['kategori']}'
                                                                data-penulis='{$row['penulis']}'
                                                                data-cover='{$row['cover']}'
                                                            >Detail</button>
                                                            <button onclick=\"setReturnData('{$row['id_peminjaman']}', '{$row['nama_user']}', '{$row['id_user']}', '{$row['judul']}', '{$row['kategori']}', '{$row['penulis']}', '{$row['cover']}', '{$row['tanggal_pinjam']}', '{$row['tanggal_kembali']}')\" class='bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold shadow transition'>Kembalikan Buku</button>
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
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-perpusku2">Judul:</span> <span id="detail_judul" class="text-gray-700"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-perpusku2">Kategori:</span> <span id="detail_kategori" class="text-gray-700"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-perpusku2">Penulis:</span> <span id="detail_penulis" class="text-gray-700"></span>
                    </div>
                    <div class="flex items-center gap-2" id="detail_cover_row" style="display:none;">
                        <span class="font-semibold text-perpusku2">Cover:</span> <span id="detail_cover"></span>
                    </div>
                </div>
                <button onclick="backToTable()" class="px-6 py-2 bg-perpusku3 text-perpusku1 font-semibold rounded-lg shadow hover:bg-yellow-400 transition">Kembali</button>
            </div>
            <!-- Halaman Struk Pengembalian -->
            <div id="strukSection" class="hidden bg-white rounded-2xl shadow-xl p-10 max-w-lg mx-auto mt-12 border border-green-200 animate-fadeIn">
                <div class="flex items-center gap-3 mb-6">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 014-4h2a4 4 0 014 4v2"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/></svg>
                    <h3 class="text-2xl font-bold text-green-600">Struk Pengembalian Buku</h3>
                </div>
                <div id="strukContent" class="mb-8 text-perpusku1">
                    <!-- Populated by JS -->
                </div>
                <form id="returnForm" action="../../controller/aksi_peminjaman.php" method="POST" target="_blank">
                    <input type="hidden" name="id_peminjaman" id="return_id">
                    <input type="hidden" name="aksi" value="reject">
                    <div class="flex gap-2">
                        <button type="button" onclick="backToTable()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-green-500 text-white font-semibold rounded-lg shadow hover:bg-green-600 transition">Konfirmasi & Kembalikan Buku</button>
                        <button type="button" onclick="printStruk()" class="px-6 py-2 bg-perpusku3 text-perpusku1 font-semibold rounded-lg shadow hover:bg-yellow-400 transition">Print Struk</button>
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
            document.getElementById('strukSection').classList.add('hidden');
            document.getElementById(section).classList.remove('hidden');
        }
        function backToTable() {
            showSection('tableSection');
        }
        function setDetailData(id, nama_user, id_user, tglPinjam, tglKembali, judul, kategori, penulis, cover) {
            document.getElementById('detail_id').textContent = id;
            document.getElementById('detail_nama').innerHTML = `${nama_user} <span class='text-xs text-perpusku2'>(ID: ${id_user})</span>`;
            document.getElementById('detail_tgl_pinjam').textContent = new Date(tglPinjam).toLocaleDateString('id-ID');
            document.getElementById('detail_tgl_kembali').textContent = new Date(tglKembali).toLocaleDateString('id-ID');
            document.getElementById('detail_judul').textContent = judul || '-';
            document.getElementById('detail_kategori').textContent = kategori || '-';
            document.getElementById('detail_penulis').textContent = penulis || '-';
            if (cover && cover !== 'null' && cover !== '') {
                document.getElementById('detail_cover').innerHTML = `<img src='../../public/cover/${cover}' class='h-24 rounded shadow border border-perpusku3'>`;
                document.getElementById('detail_cover_row').style.display = '';
            } else {
                document.getElementById('detail_cover').innerHTML = '<span class="text-perpusku2">(tidak ada cover)</span>';
                document.getElementById('detail_cover_row').style.display = '';
            }
            showSection('detailSection');
        }
        function setReturnData(id, nama_user, id_user, judul, kategori, penulis, cover, tglPinjam, tglKembali) {
            // Populate struk content
            let strukHTML = `
                <div class='mb-2'><span class='font-semibold'>ID Peminjaman:</span> ${id}</div>
                <div class='mb-2'><span class='font-semibold'>Nama Peminjam:</span> ${nama_user} <span class='text-xs text-perpusku2'>(ID: ${id_user})</span></div>
                <div class='mb-2'><span class='font-semibold'>Judul Buku:</span> ${judul}</div>
                <div class='mb-2'><span class='font-semibold'>Kategori:</span> ${kategori}</div>
                <div class='mb-2'><span class='font-semibold'>Penulis:</span> ${penulis}</div>
                <div class='mb-2'><span class='font-semibold'>Tanggal Pinjam:</span> ${new Date(tglPinjam).toLocaleDateString('id-ID')}</div>
                <div class='mb-2'><span class='font-semibold'>Tanggal Kembali:</span> ${new Date(tglKembali).toLocaleDateString('id-ID')}</div>
                <div class='mb-2'><span class='font-semibold'>Tanggal Pengembalian:</span> ${new Date().toLocaleDateString('id-ID')}</div>
                <div class='mb-2'><span class='font-semibold'>Cover:</span> ` + (cover && cover !== 'null' && cover !== '' ? `<img src='../../public/cover/${cover}' class='h-20 inline-block border border-perpusku3 rounded shadow ml-2'>` : '<span class="text-perpusku2">(tidak ada cover)</span>') + `</div>
            `;
            document.getElementById('strukContent').innerHTML = strukHTML;
            document.getElementById('return_id').value = id;
            showSection('strukSection');
        }

        function printStruk() {
            let struk = document.getElementById('strukContent').innerHTML;
            let win = window.open('', 'PrintWindow', 'width=600,height=700');
            win.document.write(`
                <html><head><title>Struk Pengembalian Buku</title>
                <style>body{font-family:sans-serif;padding:30px;} img{margin-top:10px;}</style>
                </head><body>
                <h2 style='color:#1A3263;'>Struk Pengembalian Buku</h2>
                ${struk}
                <hr><div style='margin-top:30px;font-size:13px;color:#888;'>Dicetak pada: ${new Date().toLocaleString('id-ID')}</div>
                </body></html>
            `);
            win.document.close();
            win.focus();
            win.print();
        }
    </script>
</body>
</html>

<?php mysqli_close($koneksi); ?>
