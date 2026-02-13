<?php
include '../../model/koneksi.php';

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
	<title><?= $edit ? 'Edit User' : 'Tambah User' ?> - Perpusku</title>
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
    <div class="max-w-xl mx-auto w-full">
			<h2 class="text-2xl font-bold mb-6 text-gray-800"><?= $edit ? 'Edit User' : 'Tambah User' ?></h2>
			<div class="bg-white rounded-xl shadow-xl p-6 md:p-8">
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
						<a href="crud_user.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded shadow">Batal</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
