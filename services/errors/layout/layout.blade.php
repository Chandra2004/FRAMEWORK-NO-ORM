<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTV2Ztz7uNKx5W4ZwFxFc00k6QjBgT_2y8A6w&s" type="image/x-icon">
    <title>Civil Praktikum</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ url('/assets/css/custom.css') }}">
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
</head>

<body class="bg-background min-h-screen flex flex-col font-body antialiased">

    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md shadow-soft">
        <div class="container mx-auto flex h-20 items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="/"
                class="flex items-center gap-3 font-bold text-xl font-headline transition-colors hover:text-primary"
                aria-label="Beranda Civil Praktikum">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                    stroke="#468B97" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-hard-hat">
                    <path d="M2 18a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v2z" />
                    <path d="M10 10V5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5" />
                    <path d="M4 15v-3a6 6 0 0 1 6-6h0" />
                    <path d="M14 6h0a6 6 0 0 1 6 6v3" />
                </svg>
                <span>Civil Praktikum</span>
            </a>
            <nav class="flex items-center gap-4">
                @php
                $userRole = isset($_SESSION['user']['role_name']) ? $_SESSION['user']['role_name'] : null;
                @endphp
                @if ($userRole == null)
                <a href="/login"
                    class="text-sm font-medium text-gray-700 hover:text-primary px-4 py-2 rounded-md transition-colors"
                    aria-label="Masuk ke akun">Login</a>
                <a href="/register"
                    class="text-sm font-medium bg-primary text-white hover:bg-primary/90 px-4 py-2 rounded-md transition-colors"
                    aria-label="Daftar akun baru">Register</a>
                @else
                <a href="/logout"
                    class="text-sm font-medium bg-[#468B97] text-white hover:bg-[#468B97]/90 px-4 py-2 rounded-md transition-colors"
                    aria-label="Daftar akun baru">Logout</a>
                @endif
            </nav>
        </div>
    </header>

    <!-- Error Content -->
    <main class="flex-1">
        <div class="error-container">
            <div class="error-content">
                <!-- Error Number -->
                @yield('content-error')
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200/50 mt-auto">
        <div class="container mx-auto py-8 text-center text-sm text-gray-600">
            <p>© 2025 Civil Praktikum Manager. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>