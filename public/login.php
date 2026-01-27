<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <link rel="icon" type="image/png" href="image/perpusku.png">
        <title>Login Perpusku</title>
</head>
<body class="min-h-screen flex items-center justify-center bg-perpusku4">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-xl shadow-2xl border-2 border-perpusku2">
        <div class="flex flex-col items-center">
            <div class="w-20 h-20 mb-3 flex items-center justify-center">
                <img src="image/perpusku.png" alt="Logo Perpusku" class="w-20 h-20 object-cover rounded-full shadow-lg bg-white" />
            </div>
            <h1 class="text-3xl font-extrabold text-perpusku1 mb-1 tracking-wide">Perpusku</h1>
            <p class="text-perpusku2 text-sm">Selamat datang di Perpustakaan Digital</p>
        </div>
        <form class="space-y-4" action="../controller/aksi_login.php" method="POST">
            <div>
                <label for="username" class="block text-perpusku1 font-semibold">Username</label>
                <input type="text" id="username" name="username" required class="w-full px-3 py-2 rounded-lg bg-perpusku4 text-perpusku1 border border-perpusku2 focus:outline-none focus:ring-2 focus:ring-perpusku1" placeholder="Masukkan username">
            </div>
            <div>
                <label for="password" class="block text-perpusku1 font-semibold">Password</label>
                <input type="password" id="password" name="password" required class="w-full px-3 py-2 rounded-lg bg-perpusku4 text-perpusku1 border border-perpusku2 focus:outline-none focus:ring-2 focus:ring-perpusku1" placeholder="Masukkan password">
            </div>
            <!-- Role input dihapus, role akan ditentukan otomatis di backend -->
            <button type="submit" class="w-full py-2 px-4 bg-perpusku1 hover:bg-perpusku2 text-perpusku4 font-bold rounded-lg transition">Login</button>
        </form>
        <?php if (isset($_GET['error'])): ?>
            <div class="mt-4 text-center text-red-500 font-semibold">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        <div class="text-xs text-center text-perpusku2 mt-6">&copy; 2026 Perpusku. All rights reserved.</div>
    </div>
</body>
</html>