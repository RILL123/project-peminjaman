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

<body class="bg-gray-100 min-h-screen">
	<?php include '../partials/admin_sidebar.php'; ?>
	<div class="lg:ml-64 p-4">
		<div class="max-w-4xl mx-auto">
			<h2 class="text-2xl font-bold mb-6 text-gray-800">Manajemen User</h2>
			<?php if ($notif == 'added'): ?>
				<div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300">User berhasil ditambahkan!</div>
			<?php elseif ($notif == 'edited'): ?>
				<div class="mb-4 p-3 rounded bg-blue-100 text-blue-800 border border-blue-300">User berhasil diubah!</div>
			<?php elseif ($notif == 'deleted'): ?>
				<div class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-300">User berhasil dihapus!</div>
			<?php endif; ?>

			<!-- Form Tambah/Edit User -->
			<div class="bg-white rounded-lg shadow p-6 mb-8">
				<div class="font-semibold text-lg mb-4 text-gray-700 flex items-center gap-2">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
					<?= $edit ? 'Edit User' : 'Tambah User' ?>
				</div>
				<form method="post" action="../../controller/aksi_crud_user.php" class="space-y-4">
					<?php if ($edit): ?>
						<input type="hidden" name="id_user" value="<?= htmlspecialchars($edit_data['id_user']) ?>">
					<?php endif; ?>
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
						<input type="text" name="nama" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200" required value="<?= $edit ? htmlspecialchars($edit_data['nama']) : '' ?>">
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
						<input type="text" name="username" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200" required value="<?= $edit ? htmlspecialchars($edit_data['username']) : '' ?>">
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-1">Password <?= $edit ? '<span class=\'text-xs text-gray-500\'>(Kosongkan jika tidak diubah)</span>' : '' ?></label>
						<input type="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200" <?= $edit ? '' : 'required' ?> autocomplete="new-password">
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
						<select name="role" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
							<option value="">Pilih Role</option>
							<option value="admin" <?= $edit && $edit_data['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
							<option value="user" <?= $edit && $edit_data['role'] == 'user' ? 'selected' : '' ?>>User</option>
						</select>
					</div>
					<div class="flex gap-2 mt-2">
						<button type="submit" name="<?= $edit ? 'edit' : 'tambah' ?>" class="bg-perpusku1 hover:bg-perpusku2 text-white px-4 py-2 rounded shadow">Simpan</button>
						<?php if ($edit): ?>
							<a href="crud_user.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded shadow">Batal</a>
						<?php endif; ?>
					</div>
				</form>
			</div>

			<!-- Tabel User -->
			<div class="bg-white rounded-lg shadow p-6">
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
									<a href="crud_user.php?edit=<?= $row['id_user'] ?>" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded shadow text-xs">Edit</a>
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
