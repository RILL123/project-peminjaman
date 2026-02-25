<?php
include '../../model/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Peminjaman</title>
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
    <?php include '../partials/user_sidebar.php'; ?>
    <div id="mainContent" class="flex-1 flex flex-col min-h-screen md:ml-64 transition-all duration-300 p-4 md:p-6">
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-perpusku1 mb-2 flex items-center gap-2">
                <svg class="w-8 h-8 text-perpusku3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Peminjaman Buku
            </h1>
            <p class="text-perpusku2">Isi data peminjaman di bawah ini.</p>
        </div>
        <div class="bg-white rounded-xl shadow-xl max-w-xl mx-auto w-full overflow-hidden border border-perpusku2">
            <form action="../../controller/aksi_peminjaman.php" method="POST" class="p-8">
                <input type="hidden" name="aksi" value="tambah">
                <div class="mb-4">
                    <label class="block font-semibold text-perpusku1 mb-1">Peminjam</label>
                    <input type="text" id="userInput" class="w-full border-2 border-perpusku4 rounded-lg p-3" placeholder="Ketik nama peminjam..." autocomplete="off" required>
                    <input type="hidden" name="id_user" id="userIdHidden" required>
                    <div id="userSuggestions" class="absolute z-10 bg-white border border-perpusku4 rounded-lg mt-1 w-full shadow-lg hidden"></div>
                </div>
                <div class="mb-4 relative">
                    <label class="block font-semibold text-perpusku1 mb-1">Buku</label>
                    <input type="text" id="bukuInput" class="w-full border-2 border-perpusku4 rounded-lg p-3" placeholder="Ketik judul buku..." autocomplete="off" required>
                    <input type="hidden" name="id_buku" id="bukuIdHidden" required>
                    <div id="bukuSuggestions" class="absolute z-10 bg-white border border-perpusku4 rounded-lg mt-1 w-full shadow-lg hidden max-h-64 overflow-y-auto"></div>
                    <div id="bukuPreview" class="mt-2 flex items-center gap-3"></div>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold text-perpusku1 mb-1">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="w-full border-2 border-perpusku4 rounded-lg p-3" required>
                </div>
                <div class="mb-6">
                    <label class="block font-semibold text-perpusku1 mb-1">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali" class="w-full border-2 border-perpusku4 rounded-lg p-3" required>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-perpusku1 hover:bg-perpusku2 text-white px-6 py-2 rounded-lg font-bold transition">Simpan</button>
                    <a href="transaksi.php" class="bg-gray-300 hover:bg-gray-400 text-perpusku1 px-6 py-2 rounded-lg font-bold transition text-center">Batal</a>
                </div>
            </form>
        </div>
    </div>
    <script>
    // --- Autocomplete Peminjam (user) ---
    const userInput = document.getElementById('userInput');
    const userIdHidden = document.getElementById('userIdHidden');
    const userSuggestions = document.getElementById('userSuggestions');
    let debounceTimeout;
    userInput.addEventListener('input', function() {
        clearTimeout(debounceTimeout);
        const val = userInput.value.trim();
        userIdHidden.value = '';
        if (val.length < 2) {
            userSuggestions.innerHTML = '';
            userSuggestions.classList.add('hidden');
            return;
        }
        debounceTimeout = setTimeout(() => {
            fetch('user_autocomplete.php?q=' + encodeURIComponent(val))
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        userSuggestions.innerHTML = '<div class="p-2 text-gray-400">Tidak ditemukan</div>';
                        userSuggestions.classList.remove('hidden');
                        return;
                    }
                    userSuggestions.innerHTML = data.map(u => `<div class='p-2 hover:bg-perpusku3 hover:text-perpusku1 cursor-pointer truncate' data-id='${u.id_user}' data-nama='${u.nama}'>${u.nama}</div>`).join('');
                    userSuggestions.classList.remove('hidden');
                });
        }, 200);
    });
    userSuggestions.addEventListener('click', function(e) {
        if (e.target && e.target.dataset.id) {
            userInput.value = e.target.dataset.nama;
            userIdHidden.value = e.target.dataset.id;
            userSuggestions.classList.add('hidden');
        }
    });
    document.addEventListener('click', function(e) {
        if (!userSuggestions.contains(e.target) && e.target !== userInput) {
            userSuggestions.classList.add('hidden');
        }
    });
    // Validasi sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!userIdHidden.value) {
            e.preventDefault();
            userInput.focus();
            userSuggestions.innerHTML = '<div class="p-2 text-red-500">Pilih peminjam dari daftar!</div>';
            userSuggestions.classList.remove('hidden');
        }
        if (!bukuIdHidden.value) {
            e.preventDefault();
            bukuInput.focus();
            bukuSuggestions.innerHTML = '<div class="p-2 text-red-500">Pilih buku dari daftar!</div>';
            bukuSuggestions.classList.remove('hidden');
        }
    });

    // --- Autocomplete Buku ---
    const bukuInput = document.getElementById('bukuInput');
    const bukuIdHidden = document.getElementById('bukuIdHidden');
    const bukuSuggestions = document.getElementById('bukuSuggestions');
    const bukuPreview = document.getElementById('bukuPreview');
    let bukuDebounce;
    bukuInput.addEventListener('input', function() {
        clearTimeout(bukuDebounce);
        const val = bukuInput.value.trim();
        bukuIdHidden.value = '';
        bukuPreview.innerHTML = '';
        if (val.length < 2) {
            bukuSuggestions.innerHTML = '';
            bukuSuggestions.classList.add('hidden');
            return;
        }
        bukuDebounce = setTimeout(() => {
            fetch('buku_autocomplete.php?q=' + encodeURIComponent(val))
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        bukuSuggestions.innerHTML = '<div class="p-2 text-gray-400">Tidak ditemukan</div>';
                        bukuSuggestions.classList.remove('hidden');
                        return;
                    }
                    bukuSuggestions.innerHTML = data.map(b =>
                        `<div class='flex items-center gap-2 p-2 hover:bg-perpusku3 hover:text-perpusku1 cursor-pointer truncate' data-id='${b.id_buku}' data-judul='${b.judul}' data-cover='${b.cover || ''}' data-kategori='${b.kategori || ''}' data-penulis='${b.penulis || ''}'>`+
                        `<span class='truncate max-w-[120px]'>${b.judul}</span>`+
                        (b.cover ? `<img src='../../public/cover/${b.cover}' class='h-8 w-6 object-cover rounded border border-perpusku4' alt='cover'>` : '')+
                        `</div>`
                    ).join('');
                    bukuSuggestions.classList.remove('hidden');
                });
        }, 200);
    });
    bukuSuggestions.addEventListener('click', function(e) {
        let target = e.target;
        // If click on img or span, get parent div
        if (target.tagName !== 'DIV') target = target.closest('div[data-id]');
        if (target && target.dataset.id) {
            bukuInput.value = target.dataset.judul;
            bukuIdHidden.value = target.dataset.id;
            bukuSuggestions.classList.add('hidden');
            // Show preview lengkap
            let html = '';
            if (target.dataset.cover) {
                html += `<img src='../../public/cover/${target.dataset.cover}' class='h-24 rounded shadow border border-perpusku3'>`;
            }
            html += `<div class='ml-3'>`;
            html += `<div class='font-bold text-perpusku1 text-lg mb-1'>${target.dataset.judul}</div>`;
            if (target.dataset.kategori) html += `<div class='text-xs text-perpusku2 mb-1'>Kategori: ${target.dataset.kategori}</div>`;
            if (target.dataset.penulis) html += `<div class='text-xs text-perpusku2 mb-1'>Penulis: ${target.dataset.penulis}</div>`;
            html += `</div>`;
            bukuPreview.innerHTML = html;
        }
    });
    document.addEventListener('click', function(e) {
        if (!bukuSuggestions.contains(e.target) && e.target !== bukuInput) {
            bukuSuggestions.classList.add('hidden');
        }
    });
    </script>
    <script>
    const userInput = document.getElementById('userInput');
    const userIdHidden = document.getElementById('userIdHidden');
    const userSuggestions = document.getElementById('userSuggestions');
    let debounceTimeout;
    userInput.addEventListener('input', function() {
        clearTimeout(debounceTimeout);
        const val = userInput.value.trim();
        userIdHidden.value = '';
        if (val.length < 2) {
            userSuggestions.innerHTML = '';
            userSuggestions.classList.add('hidden');
            return;
        }
        debounceTimeout = setTimeout(() => {
            fetch('user_autocomplete.php?q=' + encodeURIComponent(val))
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        userSuggestions.innerHTML = '<div class="p-2 text-gray-400">Tidak ditemukan</div>';
                        userSuggestions.classList.remove('hidden');
                        return;
                    }
                    userSuggestions.innerHTML = data.map(u => `<div class='p-2 hover:bg-perpusku3 hover:text-perpusku1 cursor-pointer' data-id='${u.id_user}' data-nama='${u.nama}'>${u.nama}</div>`).join('');
                    userSuggestions.classList.remove('hidden');
                });
        }, 200);
    });
    userSuggestions.addEventListener('click', function(e) {
        if (e.target && e.target.dataset.id) {
            userInput.value = e.target.dataset.nama;
            userIdHidden.value = e.target.dataset.id;
            userSuggestions.classList.add('hidden');
        }
    });
    document.addEventListener('click', function(e) {
        if (!userSuggestions.contains(e.target) && e.target !== userInput) {
            userSuggestions.classList.add('hidden');
        }
    });
    // Validasi sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!userIdHidden.value) {
            e.preventDefault();
            userInput.focus();
            userSuggestions.innerHTML = '<div class="p-2 text-red-500">Pilih peminjam dari daftar!</div>';
            userSuggestions.classList.remove('hidden');
        }
    });
    </script>
</body>
</html>
