    <!-- Sidebar -->
        <div id="app">
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-40 z-20 hidden md:hidden"></div>
            <style>
                .sidebar-minimized {
                    width: 5rem !important;
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
            </style>
    <div id="sidebar" class="fixed z-30 inset-y-0 left-0 w-64 md:w-64 transition-all duration-300 bg-perpusku1 text-perpusku4 flex flex-col shadow-lg sidebar-expanded">
            <div class="flex items-center gap-2 px-6 py-4 border-b border-perpusku2 justify-between">
                <div class="flex items-center gap-2">
                    <img src="../../public/image/perpusku.png" alt="Logo" class="w-10 h-10 rounded-full bg-white" />
                    <span class="font-bold text-xl tracking-wide sidebar-label">Perpusku</span>
                </div>
                <button id="collapseBtn" class="text-perpusku3 focus:outline-none w-14 h-14 flex items-center justify-center rounded-full hover:bg-perpusku2 transition" onclick="toggleCollapseSidebar()">
                    <svg id="collapseIcon" xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                </button>
            </div>
            <nav class="flex-1 px-2 py-6 space-y-2">
                <a href="#" class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-perpusku2 hover:text-white transition sidebar-link">
                    <img src="../../public/image/dashboard.png" class="h-6 w-6 flex-shrink-0" alt="Dashboard Icon">
                    <span class="sidebar-label">Dashboard</span>
                </a>
                <a href="#" class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-perpusku2 hover:text-white transition sidebar-link">
                    <img src="../../public/image/book.png" class="h-6 w-6 flex-shrink-0" alt="Book Icon">
                    <span class="sidebar-label">Data Buku</span>
                </a>
                <a href="#" class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-perpusku2 hover:text-white transition sidebar-link">
                    <img src="../../public/image/pinjam.png" class="h-6 w-6 flex-shrink-0" alt="Peminjaman Icon">
                    <span class="sidebar-label">Data Peminjaman</span>
                </a>
                <a href="#" class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-perpusku2 hover:text-white transition sidebar-link">
                    <img src="../../public/image/user.png" class="h-6 w-6 flex-shrink-0" alt="User Icon">
                    <span class="sidebar-label">Data User</span>
                </a>
                <a href="../../controller/aksi_logout.php" class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-perpusku3 hover:text-perpusku1 transition sidebar-link">
                    <img src="../../public/image/logout.png" class="h-6 w-6 flex-shrink-0" alt="Logout Icon">
                    <span class="sidebar-label">Logout</span>
                </a>
            </nav>
        </div>
</div>
<script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const collapseBtn = document.getElementById('collapseBtn');
        let collapsed = false;

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
            const mainContent = document.getElementById('mainContent');
            const showSidebarBtn = document.getElementById('showSidebarBtn');
            if (collapsed) {
                sidebar.classList.add('sidebar-minimized');
                mainContent.classList.remove('md:ml-64');
                mainContent.classList.add('md:ml-20');
            } else {
                sidebar.classList.remove('sidebar-minimized');
                mainContent.classList.remove('md:ml-20');
                mainContent.classList.add('md:ml-64');
            }
        }

        function showSidebar() {
            collapsed = false;
            sidebar.classList.remove('sidebar-minimized');
            const mainContent = document.getElementById('mainContent');
            const showSidebarBtn = document.getElementById('showSidebarBtn');
            sidebar.classList.remove('-translate-x-64');
            mainContent.classList.remove('md:ml-0', 'md:ml-20');
            mainContent.classList.add('md:ml-64');
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
    </script>
    <!-- End Sidebar -->
