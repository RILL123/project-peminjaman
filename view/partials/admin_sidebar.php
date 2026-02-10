    <!-- Sidebar -->
    <div id="app">
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden transition-opacity duration-300"></div>
        <style>
            .sidebar-minimized {
                width: 5.5rem !important;
            }
            .sidebar-minimized .sidebar-label {
                display: none !important;
            }
            .sidebar-minimized .sidebar-link {
                justify-content: center;
            }
            .sidebar-link img {
                filter: brightness(0) saturate(100%) invert(1);
            }
            .sidebar-link {
                position: relative;
                overflow: hidden;
            }
            .sidebar-link::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.1);
                transition: left 0.3s ease;
            }
            .sidebar-link:hover::before {
                left: 0;
            }
            .sidebar-link > * {
                position: relative;
                z-index: 1;
            }
        </style>
        <script>
        // Cek localStorage sebelum sidebar dirender
        (function() {
            try {
                var collapsed = window.localStorage.getItem('sidebar-collapsed') === 'true';
                if (collapsed) {
                    document.write('<div id="sidebar" class="fixed z-30 inset-y-0 left-0 transition-all duration-300 bg-gradient-to-b from-perpusku1 to-perpusku1 text-perpusku4 flex flex-col shadow-2xl sidebar-expanded sidebar-minimized">');
                } else {
                    document.write('<div id="sidebar" class="fixed z-30 inset-y-0 left-0 w-64 md:w-64 transition-all duration-300 bg-gradient-to-b from-perpusku1 to-perpusku1 text-perpusku4 flex flex-col shadow-2xl sidebar-expanded">');
                }
            } catch (e) {
                document.write('<div id="sidebar" class="fixed z-30 inset-y-0 left-0 w-64 md:w-64 transition-all duration-300 bg-gradient-to-b from-perpusku1 to-perpusku1 text-perpusku4 flex flex-col shadow-2xl sidebar-expanded">');
            }
        })();
        </script>
            <!-- Header -->
            <div class="flex items-center gap-3 px-6 py-5 border-b border-perpusku2">
                <div class="flex items-center gap-3 flex-1 cursor-pointer" onclick="toggleCollapseSidebar()" title="Toggle Sidebar">
                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center flex-shrink-0">
                        <img src="../../public/image/perpusku.png" alt="Logo" class="w-8 h-8" />
                    </div>
                    <div class="sidebar-label">
                        <span class="font-bold text-lg block leading-tight">Perpusku</span>
                        <span class="text-perpusku3 text-xs">Perpustakaan</span>
                    </div>
                </div>
            </div>
            <!-- Navigation -->
            <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
                <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-perpusku4 hover:bg-perpusku2 transition duration-300 sidebar-link group">
                    <img src="../../public/image/dashboard.png" class="h-5 w-5 flex-shrink-0" alt="Dashboard">
                    <span class="sidebar-label font-medium text-sm group-hover:text-white">Dashboard</span>
                </a>
                <a href="../admin/crud_buku.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-perpusku4 hover:bg-perpusku2 transition duration-300 sidebar-link group">
                    <img src="../../public/image/book.png" class="h-5 w-5 flex-shrink-0" alt="Data Buku">
                    <span class="sidebar-label font-medium text-sm group-hover:text-white">Data Buku</span>
                </a>
                <a href="transaksi.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-perpusku4 hover:bg-perpusku2 transition duration-300 sidebar-link group">
                    <img src="../../public/image/pinjam.png" class="h-5 w-5 flex-shrink-0" alt="Peminjaman">
                    <span class="sidebar-label font-medium text-sm group-hover:text-white">Data Peminjaman</span>
                </a>
                <a href="../admin/crud_user.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-perpusku4 hover:bg-perpusku2 transition duration-300 sidebar-link group">
                    <img src="../../public/image/user.png" class="h-5 w-5 flex-shrink-0" alt="User">
                    <span class="sidebar-label font-medium text-sm group-hover:text-white">Data User</span>
                </a>
            </nav>

            <!-- Footer - Logout -->
            <div class="px-3 py-4 border-t border-perpusku2">
                <a href="../../controller/aksi_logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-perpusku4 hover:bg-red-600 transition duration-300 sidebar-link group">
                    <img src="../../public/image/logout.png" class="h-5 w-5 flex-shrink-0" alt="Logout">
                    <span class="sidebar-label font-medium text-sm group-hover:text-white">Logout</span>
                </a>
            </div>
        </div>
</div>
<script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const collapseBtn = document.getElementById('collapseBtn');

        // Ambil status sidebar dari localStorage
        let collapsed = localStorage.getItem('sidebar-collapsed') === 'true';

        // Toggle sidebar for mobile
        function toggleSidebar() {
            if (window.innerWidth < 768) {
                if (sidebar.classList.contains('-translate-x-64')) {
                    sidebar.classList.remove('-translate-x-64');
                    overlay.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-64');
                    overlay.classList.add('hidden');
                }
            }
        }

        // Overlay click only closes sidebar in mobile
        overlay.addEventListener('click', function(e) {
            if (window.innerWidth < 768) {
                sidebar.classList.add('-translate-x-64');
                overlay.classList.add('hidden');
            }
        });

        // Minimize sidebar (icon only)
        function toggleCollapseSidebar() {
            collapsed = !collapsed;
            localStorage.setItem('sidebar-collapsed', collapsed);
            const mainContent = document.getElementById('mainContent');
            if (collapsed) {
                sidebar.classList.add('sidebar-minimized');
                sidebar.classList.remove('w-64', 'md:w-64');
                mainContent?.classList.remove('md:ml-64');
                mainContent?.classList.add('md:ml-20');
            } else {
                sidebar.classList.remove('sidebar-minimized');
                sidebar.classList.add('w-64', 'md:w-64');
                mainContent?.classList.remove('md:ml-20');
                mainContent?.classList.add('md:ml-64');
            }
        }

        function showSidebar() {
            collapsed = false;
            localStorage.setItem('sidebar-collapsed', 'false');
            sidebar.classList.remove('sidebar-minimized');
            const mainContent = document.getElementById('mainContent');
            const showSidebarBtn = document.getElementById('showSidebarBtn');
            sidebar.classList.remove('-translate-x-64');
            mainContent?.classList.remove('md:ml-0', 'md:ml-20');
            mainContent?.classList.add('md:ml-64');
            showSidebarBtn.style.display = 'none';
        }

        // Responsive: reset sidebar state on resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-64');
                overlay.classList.add('hidden');
            } else {
                sidebar.classList.add('-translate-x-64');
            }
        });

        // Set sidebar state saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const mainContent = document.getElementById('mainContent');
            if (collapsed) {
                sidebar.classList.add('sidebar-minimized');
                sidebar.classList.remove('w-64', 'md:w-64');
                mainContent?.classList.remove('md:ml-64');
                mainContent?.classList.add('md:ml-20');
            } else {
                sidebar.classList.remove('sidebar-minimized');
                sidebar.classList.add('w-64', 'md:w-64');
                mainContent?.classList.remove('md:ml-20');
                mainContent?.classList.add('md:ml-64');
            }
        });
    </script>
    <!-- End Sidebar -->
