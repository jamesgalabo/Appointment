@extends('layouts.app')

@section('title', 'Admin & Owner Analytics Dashboard  KidaBoard')

@section('content')
<div class="min-h-screen bg-slate-950 flex flex-col lg:flex-row">
    <!-- Sidebar Navigation -->
    <aside class="w-full lg:w-64 border-r border-slate-800 bg-slate-900/60 p-5 flex flex-col justify-between shrink-0">
        <div class="space-y-6">
            <div class="flex items-center gap-3 px-2 py-1">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center font-bold text-white shadow-md shadow-indigo-600/30">
                    KB
                </div>
                <div>
                    <h2 class="text-sm font-bold text-white">KidaBoard Portal</h2>
                    <p class="text-[11px] text-emerald-400 font-medium flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Online • Kidapawan
                    </p>
                </div>
            </div>

            <div class="space-y-1">
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Overview</p>
                <a href="/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-xl bg-indigo-600/20 text-indigo-300 font-semibold text-xs border border-indigo-500/30">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="/calendar" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 font-medium text-xs transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Calendar & Slots</span>
                </a>
                <a href="/dashboard" class="flex items-center justify-between px-3 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 font-medium text-xs transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Prospects</span>
                    </div>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">12</span>
                </a>
                <a href="/dashboard" class="flex items-center justify-between px-3 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 font-medium text-xs transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>Viewing Requests</span>
                    </div>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">5</span>
                </a>
            </div>

            <div class="space-y-1">
                <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Accommodation</p>
                <a href="/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 font-medium text-xs transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Boarding Houses</span>
                </a>
                <a href="/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 font-medium text-xs transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Rooms & Inventory</span>
                </a>
                <a href="/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 font-medium text-xs transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Reports & PDF</span>
                </a>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-800">
            <div class="flex items-center gap-3 px-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-pink-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold ring-2 ring-indigo-500/30">
                    SA
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-white truncate">Administrator</p>
                    <p class="text-[10px] text-slate-400 truncate">Kidapawan System Admin</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Dashboard -->
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <!-- Top Action Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                    <a href="/" class="hover:text-slate-400">Home</a>
                    <span>/</span>
                    <span class="text-slate-300">Bookings & Analytics</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Bookings & Operations</h1>
                <p class="text-xs text-slate-400">Academic Period: <span class="text-indigo-400 font-semibold">2026/27 (Kidapawan City Colleges & Universities)</span></p>
            </div>
            <div class="flex items-center gap-2">
                <button class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-lg shadow-indigo-600/30 flex items-center gap-1.5 transition">
                    <span>+ Update Records</span>
                </button>
                <button class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 font-semibold text-xs border border-slate-800 flex items-center gap-1.5 transition">
                    <span>📄 Export PDF</span>
                </button>
                <button class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 font-semibold text-xs border border-slate-800 flex items-center gap-1.5 transition">
                    <span>✉️ Send Report</span>
                </button>
            </div>
        </div>

        <!-- Metric KPI Cards Row 1 -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Metric 1 -->
            <div class="glass-card rounded-2xl p-5 border border-slate-800">
                <div class="flex items-center justify-between text-xs text-slate-400 uppercase font-bold tracking-wider mb-2">
                    <span>Current Bookings</span>
                    <div class="p-1.5 rounded-lg bg-indigo-500/10 text-indigo-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-white mb-1">320</div>
                <p class="text-xs text-emerald-400 font-medium flex items-center gap-1">
                    <span>↑ 18%</span>
                    <span class="text-slate-500">vs same period last year</span>
                </p>
            </div>

            <!-- Metric 2 -->
            <div class="glass-card rounded-2xl p-5 border border-slate-800">
                <div class="flex items-center justify-between text-xs text-slate-400 uppercase font-bold tracking-wider mb-2">
                    <span>Occupancy Rate</span>
                    <div class="p-1.5 rounded-lg bg-blue-500/10 text-blue-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-white mb-1">84.2%</div>
                <p class="text-xs text-emerald-400 font-medium flex items-center gap-1">
                    <span>↑ 6.4%</span>
                    <span class="text-slate-500">across 25 registered houses</span>
                </p>
            </div>

            <!-- Metric 3 -->
            <div class="glass-card rounded-2xl p-5 border border-slate-800">
                <div class="flex items-center justify-between text-xs text-slate-400 uppercase font-bold tracking-wider mb-2">
                    <span>New Appointments</span>
                    <div class="p-1.5 rounded-lg bg-emerald-500/10 text-emerald-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-white mb-1">+42</div>
                <p class="text-xs text-indigo-400 font-medium flex items-center gap-1">
                    <span>Active viewing slots today</span>
                </p>
            </div>

            <!-- Metric 4 -->
            <div class="glass-card rounded-2xl p-5 border border-slate-800">
                <div class="flex items-center justify-between text-xs text-slate-400 uppercase font-bold tracking-wider mb-2">
                    <span>Estimated Revenue</span>
                    <div class="p-1.5 rounded-lg bg-amber-500/10 text-amber-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-white mb-1">₱768,000</div>
                <p class="text-xs text-emerald-400 font-medium flex items-center gap-1">
                    <span>↑ 15.3%</span>
                    <span class="text-slate-500">monthly turnover</span>
                </p>
            </div>
        </div>

        <!-- Interactive Rooms Availability Grid (Hotelrev style) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 glass-card rounded-2xl p-6 border border-slate-800">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-lg font-bold text-white">Live Room Status</h3>
                        <p class="text-xs text-slate-400">Real-time room occupancy and inspection states</p>
                    </div>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-semibold">Available: 8</span>
                        <span class="px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20 font-semibold">Occupied: 16</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <!-- Room 101 -->
                    <div class="p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-200">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-extrabold text-base text-white">101</span>
                            <span class="text-[11px] font-bold text-emerald-400">₱2,200/mo</span>
                        </div>
                        <p class="text-[11px] text-slate-300">Deluxe Single</p>
                        <div class="mt-2 text-[10px] text-emerald-400 font-semibold flex items-center gap-1">
                            <span>● Available (1 pax)</span>
                        </div>
                    </div>

                    <!-- Room 102 -->
                    <div class="p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-200">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-extrabold text-base text-white">102</span>
                            <span class="text-[11px] font-bold text-slate-400">₱1,800/mo</span>
                        </div>
                        <p class="text-[11px] text-slate-400">Standard Duo</p>
                        <div class="mt-2 text-[10px] text-rose-400 font-semibold flex items-center gap-1">
                            <span>● Occupied (2 pax)</span>
                        </div>
                    </div>

                    <!-- Room 103 -->
                    <div class="p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-200">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-extrabold text-base text-white">103</span>
                            <span class="text-[11px] font-bold text-emerald-400">₱2,500/mo</span>
                        </div>
                        <p class="text-[11px] text-slate-300">Executive Solo</p>
                        <div class="mt-2 text-[10px] text-emerald-400 font-semibold flex items-center gap-1">
                            <span>● Available (1 pax)</span>
                        </div>
                    </div>

                    <!-- Room 104 -->
                    <div class="p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-200">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-extrabold text-base text-white">104</span>
                            <span class="text-[11px] font-bold text-slate-400">₱3,200/mo</span>
                        </div>
                        <p class="text-[11px] text-slate-400">Studio Suite</p>
                        <div class="mt-2 text-[10px] text-rose-400 font-semibold flex items-center gap-1">
                            <span>● Occupied (3 pax)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Student Bookings -->
            <div class="glass-card rounded-2xl p-6 border border-slate-800">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-white">Recent Activities</h3>
                    <a href="#" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium">View all</a>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-900/80 border border-slate-800">
                        <div>
                            <p class="text-xs font-bold text-white">John Mark (USM Student)</p>
                            <p class="text-[11px] text-slate-400">Room 101 • Viewing Confirmed</p>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-500/20 text-indigo-400">Confirmed</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-900/80 border border-slate-800">
                        <div>
                            <p class="text-xs font-bold text-white">Sarah Alcantara</p>
                            <p class="text-[11px] text-slate-400">Kost Oemah Kita • Reserved</p>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400">Paid Deposit</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-900/80 border border-slate-800">
                        <div>
                            <p class="text-xs font-bold text-white">Francis D. (NVC)</p>
                            <p class="text-[11px] text-slate-400">Room 205 • Appointment Scheduled</p>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-400">Pending</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
