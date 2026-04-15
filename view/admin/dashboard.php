<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../src/output.css" rel="stylesheet">
    <title>Dashboard</title>
    <link rel="icon" type="image/png" href="../../public/image/perpusku.png">
    <script src="../../public/realtime.js"></script>
</head>
<body class="font-poppins bg-perpusku4 min-h-screen">
        <?php include '../partials/admin_sidebar.php'; ?>
        <button id="showSidebarBtn" class="fixed top-4 left-4 z-40 bg-perpusku1 text-perpusku3 w-14 h-14 rounded-full flex items-center justify-center shadow-lg transition hover:bg-perpusku2" style="display:none" onclick="showSidebar()">
            <img src="../../public/image/menu.png" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16" />
            </svg>
        </button>
        <!-- Main content -->
        <div id="mainContent" class="flex-1 flex flex-col min-h-screen md:ml-64 transition-all duration-300">
            
            <main class="flex-1 p-4 md:p-8">
                <!-- Welcome Card -->
                <div class="bg-linear-to-r from-perpusku1 to-perpusku2 rounded-2xl shadow-lg p-6 md:p-8 mb-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-3xl md:text-4xl font-bold mb-2">Selamat Datang, <?php echo $_SESSION['username']; ?>!</h2>
                            <p class="text-perpusku4 text-lg">Kelola perpustakaan dengan mudah dan efisien</p>
                        </div>
                        <img src="../../public/image/perpusku.png" alt="Logo" class="hidden md:block w-24 h-24 rounded-full bg-white p-1" />
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                    <!-- Card 1: Total Buku -->
                    <div class="bg-perpusku1 rounded-xl shadow-md hover:shadow-lg transition p-6 border-t">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-white text-sm font-semibold mb-2">Total Buku</p>
                                <?php
                                include '../../model/koneksi.php';
                                $result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku");
                                $row = mysqli_fetch_assoc($result);
                                $total_buku = $row['total'];
                                ?>
                                <h3 class="text-3xl font-bold text-white" id="total-buku"><?= $total_buku ?></h3>
                            </div>
                            <div class=" rounded-full flex-none p-4">
                                <img src="../../public/image/data.png" class="h-12 w-12" style="filter: brightness(0) invert(1);" />
                            </div>
                        </div>
                    </div>
                    <!-- Card 2: Peminjaman Aktif -->
                    <div class="bg-perpusku1 rounded-xl shadow-md hover:shadow-lg transition p-6 border-t">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-white text-sm font-semibold mb-2">Peminjaman Aktif</p>
                                 <?php
                                include '../../model/koneksi.php';
                                $result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman");
                                $row = mysqli_fetch_assoc($result);
                                $total_peminjaman = $row['total'];
                                ?>
                                <h3 class="text-3xl font-bold text-white" id="total-peminjaman"><?= $total_peminjaman ?></h3>
                            </div>
                            <div class=" rounded-full flex-none p-4">
                                <img src="../../public/image/peminjam.png" class="h-12 w-12" style="filter: brightness(0) invert(1);" />
                            </div>
                        </div>
                    </div>
                    <!-- Card 3: Total User -->
                    <div class="bg-perpusku1 rounded-xl shadow-md hover:shadow-lg transition p-6 border-t">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-white text-sm font-semibold mb-2">Total User</p>
                                <?php
                                include '../../model/koneksi.php';
                                $result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users");
                                $row = mysqli_fetch_assoc($result);
                                $total_user = $row['total'];
                                ?>
                                <h3 class="text-3xl font-bold text-white" id="total-user"><?= $total_user ?></h3>
                            </div>
                            <div class="bg-opacity-10 rounded-full flex-none p-4">
                                <img src="../../public/image/account.png" class="h-12 w-12" style="filter: brightness(0) invert(1);" />
                            </div>
                        </div>
                    </div>
                    <!-- Card 4: Peminjaman Tertunda -->
                    <div class="bg-perpusku1 rounded-xl shadow-md hover:shadow-lg transition p-6 border-t">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-white text-sm font-semibold mb-2">Peminjaman Tertunda</p>
                                 <?php
                                include '../../model/koneksi.php';
                                $result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM request_peminjaman");
                                $row = mysqli_fetch_assoc($result);
                                $total_request = $row['total'];
                                ?>
                                <h3 class="text-3xl font-bold text-white" id="total-request"><?= $total_request ?></h3>
                            </div>
                            <div class="bg-opacity-10 rounded-full flex-none p-4">
                                <img src="../../public/image/pinjam.png" class="h-12 w-12" style="filter: brightness(0) invert(1);" />
                            </div>
                        </div>
                    </div>
                    <!-- Card 5: Pengembalian Tertunda -->
                    <div class="bg-perpusku1 rounded-xl shadow-md hover:shadow-lg transition p-6 border-t">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-white text-sm font-semibold mb-2">Pengembalian Tertunda</p>
                                 <?php
                                include '../../model/koneksi.php';
                                $result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM request_pengembalian");
                                $row = mysqli_fetch_assoc($result);
                                $total_kembali_tertunda = $row['total'];
                                ?>
                                <h3 class="text-3xl font-bold text-white" id="total-kembali-tertunda"><?= $total_kembali_tertunda ?></h3>
                            </div>
                            <div class="bg-opacity-10 rounded-full flex-none ">
                                <img src="../../public/image/back.png" class="h-12 w-12" style="filter: brightness(0) invert(1);" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Log Aktivitas -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-perpusku1">Aktivitas Terbaru</h3>
                        </div>
                        <div id="logs-container" class="space-y-4">
                            <?php
                            include '../../model/koneksi.php';
                            $log_query = "SELECT l.*, 
                                u.nama AS nama_user,
                                b.judul AS judul_buku
                                FROM log_aktivitas l
                                LEFT JOIN users u ON l.id_user = u.id_user
                                LEFT JOIN buku b ON l.id_buku = b.id_buku
                                ORDER BY l.tanggal DESC
                                LIMIT 2";
                            $log_result = mysqli_query($koneksi, $log_query);
                            if (mysqli_num_rows($log_result) > 0):
                                while ($log = mysqli_fetch_assoc($log_result)):
                            ?>
                            <div class="flex flex-col md:flex-row md:items-center p-3 bg-perpusku4 rounded-lg hover:bg-opacity-75 transition">
                                <div class="flex-1">
                                    <div class="font-semibold text-perpusku1">
                                        <?= htmlspecialchars($log['aktivitas']) ?>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <?= htmlspecialchars($log['keterangan']) ?>
                                        <?php if ($log['judul_buku']): ?>
                                            <span class="ml-2 text-perpusku2">[<?= htmlspecialchars($log['judul_buku']) ?>]</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex flex-col md:items-end md:w-48 mt-2 md:mt-0">
                                    <span class="text-xs text-gray-500">Oleh: <?= $log['nama_user'] ? htmlspecialchars($log['nama_user']) : 'Admin' ?></span>
                                    <span class="text-xs text-gray-400"><?= date('d M Y H:i', strtotime($log['tanggal'])) ?></span>
                                </div>
                            </div>
                            <?php endwhile; else: ?>
                            <div class="flex items-center p-3 bg-perpusku4 rounded-lg">
                                <span class="text-gray-500">Belum ada aktivitas.</span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="mt-4">
                            <a href="log_aktivitas.php" class="inline-block w-full text-center px-4 py-2 bg-perpusku1 text-white rounded-lg hover:bg-perpusku2 transition font-semibold">
                                Lihat Semua
                            </a>
                        </div>
                    </div>

                    <!-- Akses cepat -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-bold text-perpusku1 mb-4">Akses Cepat</h3>
                        <div class="space-y-3">
                            <a href="tambah.php" class="flex items-center p-3 rounded-lg bg-perpusku1 text-white hover:bg-perpusku2 transition font-semibold">
                                <img src="../../public/image/add.png" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z" /></svg>
                                Tambah Buku
                            </a>
                            <a href="crud_transaksi.php" class="flex items-center p-3 rounded-lg bg-perpusku2 text-white hover:opacity-90 transition font-semibold">
                                <img src="../../public/image/add.png" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z" /></svg>
                                Tambah Data Peminjaman
                            </a>
                            <a href="tambah_user.php" class="flex items-center p-3 rounded-lg bg-perpusku3 text-perpusku1 hover:opacity-90 transition font-semibold">
                                <img src="../../public/image/add.png" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z" /></svg>
                                Tambah User
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

</body>
</html>