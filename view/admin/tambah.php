<?php
include '../../model/koneksi.php';
// Cek mode edit
$isEdit = false;
$editData = [
    'id_buku' => '',
    'judul' => '',
    'kategori' => '',
    'penulis' => '',
    'tahun' => '',
    'stok' => '',
    'cover' => ''
];
if (isset($_GET['edit'])) {
    $isEdit = true;
    $id = intval($_GET['edit']);
    $result = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku='$id' LIMIT 1");
    if ($row = mysqli_fetch_assoc($result)) {
        $editData = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Edit Buku' : 'Tambah Buku' ?> - Perpusku</title>
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
<body class="bg-gradient-to-br from-perpusku4 to-white min-h-screen">
    <?php include '../partials/admin_sidebar.php'; ?>
    <div id="mainContent" class="flex-1 flex flex-col min-h-screen md:ml-64 transition-all duration-300 p-4 md:p-6">
  
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-perpusku1 mb-2">
                <?= $isEdit ? ' Edit Buku' : '+ Tambah Buku' ?>
            </h1>
            <p class="text-perpusku2">
                <?= $isEdit ? 'Ubah data buku di bawah ini.' : 'Tambahkan buku baru ke perpustakaan' ?>
            </p>
        </div>

     
        <div class="bg-white rounded-xl shadow-xl max-w-2xl mx-auto w-full overflow-hidden">
    
            <div class="bg-gradient-to-r from-perpusku1 to-perpusku2 text-white p-6">
                <h2 class="text-2xl font-bold">Informasi Buku</h2>
            </div>

          
            <form method="post" enctype="multipart/form-data" action="../../controller/aksi_crud_buku.php" class="p-6 md:p-8">
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id_buku" value="<?= htmlspecialchars($editData['id_buku']) ?>">
                <?php endif; ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
           
                    <div class="space-y-5">
                        <div>
                            <label class="block text-perpusku1 font-bold text-sm mb-2">Judul Buku *</label>
                            <input type="text" name="judul" class="w-full border-2 border-perpusku4 rounded-lg p-3 focus:border-perpusku2 focus:outline-none transition focus:ring-2 focus:ring-perpusku3" placeholder="Masukkan judul buku" required value="<?= htmlspecialchars($editData['judul']) ?>">
                        </div>
                        <div>
                            <label class="block text-perpusku1 font-bold text-sm mb-2">Penulis *</label>
                            <input type="text" name="penulis" class="w-full border-2 border-perpusku4 rounded-lg p-3 focus:border-perpusku2 focus:outline-none transition focus:ring-2 focus:ring-perpusku3" placeholder="Masukkan nama penulis" required value="<?= htmlspecialchars($editData['penulis']) ?>">
                        </div>
                        <div>
                            <label class="block text-perpusku1 font-bold text-sm mb-2">Kategori *</label>
                            <select name="kategori" class="w-full border-2 border-perpusku4 rounded-lg p-3 focus:border-perpusku2 focus:outline-none transition focus:ring-2 focus:ring-perpusku3" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Fiksi" <?= ($editData['kategori'] ?? '') == 'Fiksi' ? 'selected' : '' ?>>Fiksi</option>
                                <option value="Non-Fiksi" <?= ($editData['kategori'] ?? '') == 'Non-Fiksi' ? 'selected' : '' ?>>Non-Fiksi</option>
                                <option value="Komik" <?= ($editData['kategori'] ?? '') == 'Komik' ? 'selected' : '' ?>>Komik</option>
                                <option value="Ensiklopedia" <?= ($editData['kategori'] ?? '') == 'Ensiklopedia' ? 'selected' : '' ?>>Ensiklopedia</option>
                                <option value="Novel" <?= ($editData['kategori'] ?? '') == 'Novel' ? 'selected' : '' ?>>Novel</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-perpusku1 font-bold text-sm mb-2">Tahun Terbit *</label>
                            <input type="number" name="tahun" class="w-full border-2 border-perpusku4 rounded-lg p-3 focus:border-perpusku2 focus:outline-none transition focus:ring-2 focus:ring-perpusku3" placeholder="Contoh: 2024" min="1900" max="2100" required value="<?= htmlspecialchars($editData['tahun']) ?>">
                        </div>
                        <div>
                            <label class="block text-perpusku1 font-bold text-sm mb-2">Stok Buku *</label>
                            <input type="number" name="stok" class="w-full border-2 border-perpusku4 rounded-lg p-3 focus:border-perpusku2 focus:outline-none transition focus:ring-2 focus:ring-perpusku3" placeholder="Jumlah stok buku" min="0" required value="<?= htmlspecialchars($editData['stok']) ?>">
                        </div>
                    </div>

                    <div>
                
                        <label class="block text-perpusku1 font-bold text-sm mb-2">Cover Buku (Opsional)</label>
                        <div class="border-2 border-dashed border-perpusku4 rounded-lg p-6 text-center hover:border-perpusku2 hover:bg-perpusku4 transition duration-300 cursor-pointer group">
                            <input type="file" name="cover" accept="image/*" class="hidden" id="coverInput" onchange="previewCover(this)">
                            <label for="coverInput" class="cursor-pointer block">
                                <div class="text-4xl mb-3 group-hover:scale-110 transition"></div>
                                <span class="text-perpusku1 font-bold block">Klik untuk upload</span>
                                <span class="text-sm text-perpusku2 block">atau seret gambar ke sini</span>
                                <span class="text-xs text-gray-400 block mt-1">JPG, PNG, atau format gambar lainnya</span>
                            </label>
                        </div>
                        <div id="coverPreview" class="mt-4">
                            <?php if ($isEdit && $editData['cover']): ?>
                                <img src="../../public/cover/<?= htmlspecialchars($editData['cover']) ?>" class="h-32 rounded-lg shadow-md mx-auto mb-2">
                                <p class="text-sm text-perpusku2 text-center">Cover saat ini</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
     
                <div class="flex gap-3 mt-8 pt-6 border-t border-perpusku4">
                    <button type="submit" name="<?= $isEdit ? 'edit' : 'tambah' ?>" class="flex-1 bg-perpusku1 hover:bg-perpusku2 text-white px-6 py-3 rounded-lg font-bold transition duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <span><?= $isEdit ? 'Update' : 'Simpan Buku' ?></span>
                    </button>
                    <a href="crud_buku.php" class="flex-1 bg-gray-300 hover:bg-gray-400 text-perpusku1 px-6 py-3 rounded-lg font-bold transition duration-300 text-center flex items-center justify-center gap-2">
                        <span>Batal</span>
                    </a>
                </div>
            </form>
        </div>
   
        <div class="bg-perpusku3 bg-opacity-20 border border-perpusku3 rounded-lg p-4 mt-6 max-w-2xl mx-auto w-full">
            <p class="text-perpusku1 text-sm"><strong>ℹ️ Catatan:</strong> Semua field yang bertanda (*) wajib diisi. Pastikan data yang Anda masukkan sudah benar sebelum menyimpan.</p>
        </div>
    </div>
    <script>
    function previewCover(input) {
        const preview = document.getElementById('coverPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.innerHTML = `
                    <div class=\"flex justify-center\">
                        <div class=\"relative\">
                            <img src=\"${e.target.result}\" class=\"h-40 rounded-lg shadow-lg border-4 border-perpusku3\">
                            <p class=\"text-sm text-perpusku2 mt-2 text-center font-semibold\">✓ File terpilih</p>
                        </div>
                    </div>
                `;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
  
    const dropZone = document.querySelector('[for="coverInput"]').closest('div');
    if (dropZone) {
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-perpusku2', 'bg-perpusku4');
        });
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-perpusku2', 'bg-perpusku4');
        });
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-perpusku2', 'bg-perpusku4');
            const files = e.dataTransfer.files;
            document.getElementById('coverInput').files = files;
            previewCover(document.getElementById('coverInput'));
        });
    }
    </script>
</body>
</html>
