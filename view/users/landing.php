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
    <title>Home</title>
    <link rel="icon" type="image/png" href="../../public/image/perpusku.png">
</head>
<body class="bg-perpusku4 min-h-screen">
<?php include '../partials/user_sidebar.php'; ?>

    <main id="mainContent" class="flex-1 p-4 md:p-8">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-perpusku1 to-perpusku2 rounded-2xl shadow-lg p-6 md:p-8 mb-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-3xl md:text-4xl font-bold mb-2">Selamat Datang !!</h2>
                            <p class="text-perpusku4 text-lg">Perpustakaan Yang Menyediakan Banyak Buku Yang bagus dan beragam</p>
                        </div>
                        <img src="../../public/image/perpusku.png" alt="Logo" class="hidden md:block w-24 h-24 rounded-full bg-white p-1" />
                    </div>
                </div>
    </main>
</body>
</html>