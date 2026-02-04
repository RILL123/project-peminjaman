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
    <title>Login - Perpusku</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
        .login-container {
            background: linear-gradient(135deg, #1A3263 0%, #547792 100%);
        }
        .card-shadow {
            box-shadow: 0 20px 60px rgba(26, 50, 99, 0.15);
        }
        .input-field {
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.95);
        }
        .input-field:focus {
            background-color: #ffffff;
            transform: translateY(-2px);
        }
        .btn-login {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #1A3263 0%, #547792 100%);
            position: relative;
            overflow: hidden;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(26, 50, 99, 0.3);
        }
        .btn-login:active {
            transform: translateY(0);
        }
        .logo-container {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .divider-line {
            background: linear-gradient(90deg, transparent, #FAB95B, transparent);
        }
    </style>
</head>
<body class="min-h-screen login-container flex items-center justify-center p-4">
    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-perpusku2 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
        <div class="absolute bottom-20 right-10 w-72 h-72 bg-perpusku3 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
    </div>

    <!-- Main Login Card -->
    <div class="relative w-full max-w-md">
        <!-- Decorative top line -->
        <div class="h-1 bg-gradient-to-r from-perpusku3 via-perpusku2 to-perpusku1 rounded-full mb-8"></div>

        <div class="bg-white rounded-2xl card-shadow overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-br from-perpusku1 to-perpusku2 px-8 py-12 text-white relative">
                <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -mr-20 -mt-20"></div>
                
                <div class="flex flex-col items-center relative z-10">
                    <div class="logo-container mb-4">
                        <div class="w-24 h-24 rounded-full bg-white shadow-xl flex items-center justify-center p-2">
                            <img src="image/perpusku.png" alt="Logo Perpusku" class="w-20 h-20 object-contain" />
                        </div>
                    </div>
                    <h1 class="text-4xl font-bold tracking-tight mb-2">Perpusku</h1>
                    <p class="text-perpusku4 text-sm font-light">Perpustakaan Digital Modern</p>
                </div>
            </div>

            <!-- Form Section -->
            <div class="px-8 py-10">
                <!-- Error Message -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                        <p class="text-red-700 text-sm font-medium">
                            <span class="font-bold"> Error:</span> <?php echo htmlspecialchars($_GET['error']); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <form class="space-y-5" action="../controller/aksi_login.php" method="POST">
                    <!-- Username Input -->
                    <div class="relative">
                        <label for="username" class="block text-perpusku1 font-semibold text-sm mb-2 ml-1">Username</label>
                        <div class="relative">
                            <img src="image/user.png" class="absolute left-4 top-3.5 text-perpusku2 w-5 h-5">
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                required 
                                class="input-field w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl text-perpusku1 placeholder-gray-400 focus:border-perpusku1 focus:ring-0 focus:outline-none"
                                placeholder="Masukkan username Anda"
                                autocomplete="username"
                            >
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="relative">
                        <label for="password" class="block text-perpusku1 font-semibold text-sm mb-2 ml-1">Password</label>
                        <div class="relative">
                            <img src="image/padlock.png" class="absolute left-4 top-3.5 text-perpusku2 w-5 h-5">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required 
                                class="input-field w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl text-perpusku1 placeholder-gray-400 focus:border-perpusku1 focus:ring-0 focus:outline-none"
                                placeholder="Masukkan password Anda"
                                autocomplete="current-password"
                            >
                        </div>
                    </div>

                    <!-- Login Button -->
                    <button 
                        type="submit" 
                        class="btn-login w-full py-3 px-4 text-white font-semibold rounded-xl text-lg mt-8 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-perpusku1"
                    >
                        Masuk
                    </button>
                </form>

                <!-- Divider -->
                <div class="divider-line h-px my-8"></div>

                <!-- Footer Info -->
                <div class="text-center space-y-2">
                    <p class="text-xs text-gray-500">Akses Perpustakaan Digital Anda</p>
                    <p class="text-xs text-gray-400">&copy; 2026 Perpusku. All rights reserved.</p>
                </div>
            </div>
        </div>

        <!-- Decorative bottom line -->
        <div class="h-1 bg-gradient-to-r from-perpusku1 to-perpusku3 rounded-full mt-8"></div>
    </div>
</body>
</html>