<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-900 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'KidaBoard - Boarding House Search & Reservation')</title>
    <link rel="icon" href="{{ asset('NVC.jpg') }}" type="image/jpeg">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind & Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .glass-panel {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.07);
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-slate-950 text-slate-100 selection:bg-indigo-500 selection:text-white antialiased">
    <!-- Navigation Bar -->
    <header class="sticky top-0 z-50 glass-panel border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-blue-600 to-indigo-400 p-0.5 shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-300">
                        <div class="w-full h-full bg-slate-950 rounded-[10px] flex items-center justify-center overflow-hidden">
                            <span class="text-lg font-black tracking-wider bg-gradient-to-r from-indigo-400 to-blue-400 bg-clip-text text-transparent">KB</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xl font-bold tracking-tight text-white">Kida<span class="text-indigo-400">Board</span></span>
                            <span class="text-[10px] uppercase font-bold tracking-widest px-1.5 py-0.5 rounded bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">Kidapawan</span>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-none">Smart Student Accommodation</p>
                    </div>
                </a>
            </div>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="/" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Home</a>
                <a href="/explore" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Find Rooms</a>
                <a href="/dashboard" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Owner & Admin Portal</a>
                <a href="/calendar" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Schedule Calendar</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="/login" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition-colors">Sign In</a>
                <a href="/register" class="px-4 py-2 text-sm font-semibold rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white shadow-lg shadow-indigo-600/30 transition-all duration-200 hover:shadow-indigo-600/50 hover:scale-[1.02]">
                    Get Started
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800/80 bg-slate-950/80 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span>KidaBoard • Smart Web-Based Boarding House Search & Reservation System</span>
            </div>
            <div>
                &copy; {{ date('Y') }} Kidapawan City Student Housing Initiative. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
