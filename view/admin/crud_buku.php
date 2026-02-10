<?php
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
$buku_count = mysqli_num_rows($buku);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">  
	<script src="https://cdn.tailwindcss.com"></script>
	<title>Data Buku</title>
	<link rel="icon" type="image/png" href="../../public/image/perpusku.png">
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
</head>
<body class="bg-gradient-to-br from-perpusku4 to-white min-h-screen">
	<?php include '../partials/admin_sidebar.php'; ?>
	<button id="showSidebarBtn" class="fixed top-4 left-4 z-40 bg-perpusku1 text-perpusku3 w-14 h-14 rounded-full flex items-center justify-center shadow-lg transition hover:bg-perpusku2" style="display:none" onclick="showSidebar()">
		<img src="../../public/image/menu.png" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
		<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16" />
		</svg>
	</button>

	<?php if (isset($_GET['notif']) && isset($_GET['judul'])): ?>
		<div id="notifToast" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-perpusku1 text-white px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 animate-fade-in">
			<?php
				$aksi = $_GET['notif'];
				$judul = htmlspecialchars($_GET['judul']);
				$pesan = '';
				if ($aksi === 'added') $pesan = 'Buku <b>"'.$judul.'"</b> berhasil ditambahkan!';
				elseif ($aksi === 'edited') $pesan = 'Buku <b>"'.$judul.'"</b> berhasil diubah!';
				elseif ($aksi === 'deleted') $pesan = 'Buku <b>"'.$judul.'"</b> berhasil dihapus!';
			?>
			<span><?= $pesan ?></span>
			<button onclick="document.getElementById('notifToast').remove()" class="ml-4 text-perpusku3 hover:text-white font-bold">&times;</button>
		</div>
		<style>
		@keyframes fade-in {
			from { opacity: 0; transform: translateY(-20px) scale(0.95); }
			to { opacity: 1; transform: translateY(0) scale(1); }
		}
		.animate-fade-in { animation: fade-in 0.4s; }
		</style>
		<script>
		setTimeout(function(){
			var toast = document.getElementById('notifToast');
			if (toast) toast.remove();
		}, 3500);
		</script>
	<?php endif; ?>
	<div id="mainContent" class="flex-1 flex flex-col min-h-screen md:ml-64 transition-all duration-300 p-4 md:p-6">
		<!-- Header Section -->
		<div class="mb-10 bg-gradient-to-r from-perpusku1 via-perpusku2 to-perpusku1 rounded-2xl shadow-lg p-8 text-white">
			<h1 class="text-4xl md:text-5xl font-bold mb-2">Data Buku</h1>
			<p class="text-perpusku4 text-lg">Total Buku: <span class="font-bold text-2xl"><?= $buku_count ?></span></p>
		</div>

		<!-- Search Engine -->
		<form method="get" class="mb-8">
			<div class="flex flex-col sm:flex-row gap-3">
				<div class="flex-1">
					<input type="text" name="search" class="w-full border-2 border-perpusku2 rounded-xl p-4 focus:border-perpusku1 focus:outline-none focus:ring-2 focus:ring-perpusku3 focus:ring-opacity-50 transition duration-300 shadow-sm" placeholder="Cari judul atau penulis..." value="<?= htmlspecialchars($search) ?>">
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
		<div id="mainList">
			<!-- Tombol tambah buku -->
			<div class="mb-10">
				<a href="tambah.php" class="inline-flex items-center gap-3 bg-gradient-to-r from-perpusku1 to-perpusku2 hover:from-perpusku2 hover:to-perpusku1 text-white px-8 py-4 rounded-xl font-bold transition duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105">
					<span class="text-lg">Tambah Buku Baru</span>
				</a>
			</div>

			<?php if ($buku_count > 0): ?>
			<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-8">
			<?php foreach ($buku as $row): ?>
				<div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 overflow-hidden flex flex-col group">
					<!-- Cover Section -->
					<div class="relative overflow-hidden bg-gray-200 flex items-center justify-center">
						<?php if (!empty($row['cover'])): ?>
							<img src="../../public/cover/<?= htmlspecialchars($row['cover']) ?>" alt="Cover" class="w-full h-auto object-contain group-hover:scale-105 transition duration-300">
						<?php else: ?>
							<div class="w-full aspect-[2/3] flex items-center justify-center text-perpusku2 bg-gray-200 font-semibold text-xs">Tidak ada cover</div>
						<?php endif; ?>
					</div>

					<!-- Content Section -->
					<div class="p-3 flex flex-col justify-between flex-1">
						<div>
							<h3 class="font-semibold text-perpusku1 text-sm line-clamp-2"><?= htmlspecialchars($row['judul']) ?></h3>
							<p class="text-xs text-gray-600 mt-1 line-clamp-1"><?= htmlspecialchars($row['penulis']) ?></p>
						</div>
						<div class="flex gap-2 mt-3 text-center">
							<div class="flex-1">
								<p class="text-xs text-gray-500">Tahun</p>
								<p class="text-xs font-bold text-perpusku1"><?= $row['tahun'] ?></p>
							</div>
							<div class="flex-1">
								<p class="text-xs text-gray-500">Stok</p>
								<p class="text-xs font-bold text-perpusku3"><?= $row['stok'] ?></p>
							</div>
						</div>
					</div>

					<!-- Action Buttons -->
					<div class="px-3 pb-3 flex gap-2">
						<a href="tambah.php?edit=<?= $row['id_buku'] ?>" class="flex-1 bg-perpusku2 hover:bg-perpusku1 text-white px-2 py-2 rounded text-xs font-bold transition duration-300 text-center">Edit</a>
						<a href="../../controller/aksi_crud_buku.php?hapus=<?= $row['id_buku'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')" class="flex-1 bg-red-500 hover:bg-red-600 text-white px-2 py-2 rounded text-xs font-bold transition duration-300 text-center">Hapus</a>
					</div>
				</div>
			<?php endforeach; ?>
			</div>
			<?php else: ?>
			<div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg p-12 text-center border border-gray-200">
				<p class="text-perpusku2 text-xl font-semibold mb-4">Tidak ada buku yang ditemukan</p>
				<p class="text-gray-500 mb-6">Mulai dengan menambahkan buku pertama Anda</p>
				<a href="crud_buku.php" class="inline-block bg-gradient-to-r from-perpusku1 to-perpusku2 text-white px-6 py-3 rounded-lg font-bold hover:shadow-lg transition duration-300">Kembali ke Daftar</a>
			</div>
			<?php endif; ?>
		</div>
	</div>
</body>
</html>
