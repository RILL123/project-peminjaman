<?php
session_start();
include '../../model/koneksi.php';

// Pagination & Search
$items_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$where_clause = "";
if ($search_term !== '') {
	$search_term_escaped = mysqli_real_escape_string($koneksi, $search_term);
	$where_clause = "WHERE (id_user LIKE '%$search_term_escaped%' 
		OR nama LIKE '%$search_term_escaped%'
		OR username LIKE '%$search_term_escaped%'
		OR role LIKE '%$search_term_escaped%')";
}

// Total data
$count_query = "SELECT COUNT(*) AS total FROM users $where_clause";
$count_result = mysqli_query($koneksi, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'];
$total_pages = ceil($total_records / $items_per_page);

// Data user
$query = "SELECT * FROM users $where_clause ORDER BY id_user DESC LIMIT $items_per_page OFFSET $offset";
$result = mysqli_query($koneksi, $query);
if (!$result) {
	die("Query error: " . mysqli_error($koneksi));
}

$notif = isset($_GET['notif']) ? $_GET['notif'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>CRUD User</title>
	<link href="../../src/output.css" rel="stylesheet">
</head>

<body class="bg-perpusku4 min-h-screen">
	<?php include '../partials/admin_sidebar.php'; ?>
	<div id="mainContent" class="flex-1 flex flex-col min-h-screen md:ml-64 transition-all duration-300">
		<main class="flex-1 p-4 md:p-8">
			<!-- Header -->
			<div class="bg-linear-to-r from-perpusku1 to-perpusku2 rounded-2xl shadow-lg p-6 md:p-8 mb-8 text-white">
				<div class="flex items-center justify-between">
					<div>
						<h2 class="text-3xl md:text-4xl font-bold mb-2">Manajemen User</h2>
						<p class="text-perpusku4 text-lg">Pantau seluruh data user</p>
					</div>
					<a href="tambah_user.php" class="bg-perpusku3 text-perpusku1 px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition">
						+ Tambah User
					</a>
				</div>
			</div>

			<?php if ($notif == 'added'): ?>
				<div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300">User berhasil ditambahkan!</div>
			<?php elseif ($notif == 'edited'): ?>
				<div class="mb-4 p-3 rounded bg-blue-100 text-blue-800 border border-blue-300">User berhasil diubah!</div>
			<?php elseif ($notif == 'deleted'): ?>
				<div class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-300">User berhasil dihapus!</div>
			<?php endif; ?>

			<!-- Search Bar -->
			<div class="mb-6">
				<form method="GET" class="flex gap-2">
					<input 
						type="text" 
						name="search" 
						id="searchInput" 
						placeholder="Cari user, username, atau role..." 
						value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
						class="flex-1 px-4 py-3 border border-perpusku2 rounded-xl p-4 focus:border-perpusku1 transition"
					/>
					<button type="submit" class="bg-perpusku1 text-white px-6 py-3 rounded-lg hover:bg-perpusku2 transition font-semibold">
						Cari
					</button>
					<?php if (isset($_GET['search']) && $_GET['search'] !== ''): ?>
						<a href="crud_user.php" class="bg-perpusku2 text-white px-6 py-3 rounded-lg hover:bg-perpusku1 transition font-semibold">
							Reset
						</a>
					<?php endif; ?>
				</form>
				<div class="mt-2 text-sm text-gray-600">
					Total data: <span><?= $total_records ?></span>
				</div>
			</div>

			<!-- Tabel User -->
			<div class="bg-white rounded-xl shadow-md overflow-x-auto" style="max-height: 600px; overflow-y: auto;">
				<table class="w-full">
					<thead>
						<tr class="bg-perpusku1 text-white">
							<th class="px-6 py-4 text-left text-sm font-semibold">ID</th>
							<th class="px-6 py-4 text-left text-sm font-semibold">Nama</th>
							<th class="px-6 py-4 text-left text-sm font-semibold">Username</th>
							<th class="px-6 py-4 text-left text-sm font-semibold">Role</th>
							<th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($row = mysqli_fetch_assoc($result)): ?>
						<tr class="border-b border-gray-200 hover:bg-perpusku4 transition">
							<td class="px-6 py-4 font-mono text-xs text-gray-500"><?= htmlspecialchars($row['id_user']) ?></td>
							<td class="px-6 py-4 text-sm"><?= htmlspecialchars($row['nama']) ?></td>
							<td class="px-6 py-4 text-sm"><?= htmlspecialchars($row['username']) ?></td>
							<td class="px-6 py-4 text-sm">
								<span class="inline-block px-2 py-1 rounded text-xs font-semibold <?= $row['role'] == 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-700' ?>">
									<?= htmlspecialchars($row['role']) ?>
								</span>
							</td>
							<td class="px-6 py-4 text-center text-sm flex gap-2">
								<a href="tambah_user.php?edit=<?= $row['id_user'] ?>" class="bg-perpusku1 hover:bg-perpusku2 text-white px-3 py-1 rounded shadow text-xs">Edit</a>
								<a href="../../controller/aksi_crud_user.php?hapus=<?= $row['id_user'] ?>" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow text-xs" onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
							</td>
						</tr>
						<?php endwhile; ?>
						<?php if ($total_records == 0): ?>
						<tr>
							<td colspan="6" class="px-6 py-8 text-center text-gray-600">
								Tidak ada data user
							</td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<!-- Pagination -->
			<div class="mt-6 flex items-center justify-between">
				<div class="text-sm text-gray-600">
					Halaman <span class="font-semibold"><?= $current_page ?></span> dari <span class="font-semibold"><?= $total_pages ?></span> | Total: <span class="font-semibold"><?= $total_records ?></span> data
				</div>
				<div class="flex gap-2">
					<?php $search_param = $search_term !== '' ? '&search=' . urlencode($search_term) : ''; ?>
					<?php if ($current_page > 1): ?>
						<a href="?page=1<?= $search_param ?>" class="bg-perpusku1 text-white px-4 py-2 rounded-lg hover:bg-perpusku2 transition">First</a>
						<a href="?page=<?= $current_page - 1 ?><?= $search_param ?>" class="bg-perpusku1 text-white px-4 py-2 rounded-lg hover:bg-perpusku2 transition">← Previous</a>
					<?php endif; ?>
					<?php if ($current_page < $total_pages): ?>
						<a href="?page=<?= $current_page + 1 ?><?= $search_param ?>" class="bg-perpusku3 text-perpusku1 px-4 py-2 rounded-lg hover:opacity-90 transition font-semibold">Next →</a>
						<a href="?page=<?= $total_pages ?><?= $search_param ?>" class="bg-perpusku3 text-perpusku1 px-4 py-2 rounded-lg hover:opacity-90 transition font-semibold">Last</a>
					<?php endif; ?>
				</div>
			</div>

		</main>
	</div>
</body>
</html>
