<?php
include '../../model/koneksi.php';

// Ambil data user
$result = mysqli_query($koneksi, "SELECT * FROM users ORDER BY id_user DESC");

// Notifikasi
$notif = isset($_GET['notif']) ? $_GET['notif'] : '';

// Untuk edit
$edit = false;
$edit_data = null;
if (isset($_GET['edit'])) {
	$edit = true;
	$id_edit = mysqli_real_escape_string($koneksi, $_GET['edit']);
	$q = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$id_edit'");
	$edit_data = mysqli_fetch_assoc($q);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>CRUD User</title>
	<script src="https://cdn.tailwindcss.com"></script>
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

<body class="bg-perpusku4 min-h-screen">
	<?php include '../partials/admin_sidebar.php'; ?>
	<div id="mainContent" class="flex-1 flex flex-col min-h-screen md:ml-64 transition-all duration-300 p-4 md:p-6">
		<div class="max-w-4xl mx-auto">
			<div class="flex items-center justify-between mb-6">
				<h2 class="text-2xl font-bold text-gray-800">Manajemen User</h2>
				<a href="tambah_user.php" class="bg-perpusku3 hover:bg-yellow-400 text-perpusku1 font-semibold px-4 py-2 rounded shadow">+ Tambah User</a>
			</div>
			<?php if ($notif == 'added'): ?>
				<div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300">User berhasil ditambahkan!</div>
			<?php elseif ($notif == 'edited'): ?>
				<div class="mb-4 p-3 rounded bg-blue-100 text-blue-800 border border-blue-300">User berhasil diubah!</div>
			<?php elseif ($notif == 'deleted'): ?>
				<div class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-300">User berhasil dihapus!</div>
			<?php endif; ?>

  
			<!-- Tabel User -->
			<div class="bg-white rounded-xl shadow-xl p-6 md:p-8 mb-8">
				<div class="font-semibold text-lg mb-4 text-gray-700 flex items-center gap-2">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" /></svg>
					Daftar User
				</div>
				<div class="overflow-x-auto">
					<table class="min-w-full text-sm text-left text-gray-700">
						<thead class="bg-gray-100 text-gray-700 uppercase">
							<tr>
								<th class="py-2 px-3">ID</th>
								<th class="py-2 px-3">Nama</th>
								<th class="py-2 px-3">Username</th>
								<th class="py-2 px-3">Role</th>
								<th class="py-2 px-3">Created At</th>
								<th class="py-2 px-3">Aksi</th>
							</tr>
						</thead>
						<tbody>
						<?php while ($row = mysqli_fetch_assoc($result)): ?>
							<tr class="border-b hover:bg-blue-50">
								<td class="py-2 px-3 font-mono text-xs text-gray-500"><?= htmlspecialchars($row['id_user']) ?></td>
								<td class="py-2 px-3"><?= htmlspecialchars($row['nama']) ?></td>
								<td class="py-2 px-3"><?= htmlspecialchars($row['username']) ?></td>
								<td class="py-2 px-3">
									<span class="inline-block px-2 py-1 rounded text-xs font-semibold <?= $row['role'] == 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-700' ?>">
										<?= htmlspecialchars($row['role']) ?>
									</span>
								</td>
								<td class="py-2 px-3 text-xs text-gray-500"><?= isset($row['create_at']) ? htmlspecialchars($row['create_at']) : '<span class="italic text-red-400">-</span>' ?></td>
								<td class="py-2 px-3 flex gap-2">
									<a href="tambah_user.php?edit=<?= $row['id_user'] ?>" class="bg-perpusku1 hover:bg-perpusku2 text-white px-3 py-1 rounded shadow text-xs">Edit</a>
									<a href="../../controller/aksi_crud_user.php?hapus=<?= $row['id_user'] ?>" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow text-xs" onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
								</td>
							</tr>
						<?php endwhile; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
