@extends('layouts.app')

@section('title', 'KidaBoard - Smart Boarding House System in Kidapawan City')

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden bg-slate-950 py-20 lg:py-28 border-b border-slate-800/80">
    <!-- Ambient Blur -->
    <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[650px] h-[450px] bg-indigo-600/15 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-semibold uppercase tracking-wider mb-6">
                <span class="w-2 h-2 rounded-full bg-indigo-400 animate-ping"></span>
                Kidapawan City Student Housing Portal
            </div>
            <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight leading-tight mb-6">
                Find your dream <span class="bg-gradient-to-r from-indigo-400 via-blue-400 to-emerald-400 bg-clip-text text-transparent">place to stay</span> near your university
            </h1>
            <p class="text-base sm:text-lg text-slate-400 leading-relaxed">
                Centralized platform for students in Kidapawan to view live room availability, book viewing appointments, secure verified reservations, and communicate with certified boarding house owners.
            </p>
        </div>

        <!-- Interactive Search Bar -->
        <div class="max-w-4xl mx-auto glass-card rounded-2xl p-4 sm:p-5 shadow-2xl shadow-indigo-950/50">
            <form action="/explore" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div class="space-y-1 sm:border-r border-slate-800 sm:pr-3">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Location / Barangay</label>
                    <input type="text" name="location" placeholder="e.g. Poblacion, Kidapawan" class="w-full bg-slate-900/90 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <div class="space-y-1 sm:border-r border-slate-800 sm:pr-3">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Room Type</label>
                    <select name="type" class="w-full bg-slate-900/90 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="">All Room Types</option>
                        <option value="single">Single Room</option>
                        <option value="duo">Duo / Double Sharing</option>
                        <option value="bedspace">Bedspace / Quad</option>
                        <option value="studio">Studio Apartment</option>
                    </select>
                </div>
                <div class="space-y-1 sm:border-r border-slate-800 sm:pr-3">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Monthly Budget</label>
                    <select name="price" class="w-full bg-slate-900/90 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="">Any Budget</option>
                        <option value="under_1500">Under ₱1,500 / mo</option>
                        <option value="1500_2500">₱1,500 - ₱2,500 / mo</option>
                        <option value="2500_4000">₱2,500 - ₱4,000 / mo</option>
                        <option value="above_4000">₱4,000+ / mo</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Search Places</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Explore Featured Boarding Houses -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Verified Boarding Houses</h2>
            <p class="text-sm text-slate-400 mt-1">Inspected facilities located near colleges, universities, and transit hubs in Kidapawan City</p>
        </div>
        <div class="flex gap-2 mt-4 sm:mt-0">
            <button class="px-3.5 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-semibold shadow">Best for you</button>
            <button class="px-3.5 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-slate-400 text-xs font-semibold border border-slate-800">Most viewed</button>
            <button class="px-3.5 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-slate-400 text-xs font-semibold border border-slate-800">Latest</button>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Item 1 -->
        <div class="glass-card rounded-2xl overflow-hidden border border-slate-800/90 group hover:border-indigo-500/50 transition-all duration-300">
            <div class="relative h-48 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&q=80&w=800" alt="Kost Oemah Kita" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-slate-950/80 backdrop-blur-md text-xs font-bold text-amber-400 flex items-center gap-1 border border-white/10">
                    <span>★</span> 4.8
                </div>
                <div class="absolute bottom-3 left-3 px-2.5 py-0.5 rounded bg-emerald-500/90 backdrop-blur-md text-[11px] font-bold text-white uppercase tracking-wider">
                    Available Now
                </div>
            </div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="font-bold text-lg text-white group-hover:text-indigo-400 transition">Kost Oemah Kita</h3>
                    <span class="text-indigo-400 font-extrabold text-sm">₱2,200<span class="text-xs text-slate-400 font-normal">/mo</span></span>
                </div>
                <p class="text-xs text-slate-400 mb-4 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                    Poblacion, Kidapawan City (Near NVC Campus)
                </p>
                <div class="flex items-center gap-3 text-[11px] text-slate-400 py-3 border-y border-slate-800/80 mb-4">
                    <span class="flex items-center gap-1">🛜 High-speed Wi-Fi</span>
                    <span class="flex items-center gap-1">❄️ Air Conditioning</span>
                    <span class="flex items-center gap-1">🔒 CCTV 24/7</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="/explore" class="flex-1 text-center py-2.5 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition">Book Viewing</a>
                    <a href="/explore" class="py-2.5 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition">Details</a>
                </div>
            </div>
        </div>

        <!-- Item 2 -->
        <div class="glass-card rounded-2xl overflow-hidden border border-slate-800/90 group hover:border-indigo-500/50 transition-all duration-300">
            <div class="relative h-48 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&q=80&w=800" alt="Kost Putra Residence" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-slate-950/80 backdrop-blur-md text-xs font-bold text-amber-400 flex items-center gap-1 border border-white/10">
                    <span>★</span> 4.5
                </div>
                <div class="absolute bottom-3 left-3 px-2.5 py-0.5 rounded bg-emerald-500/90 backdrop-blur-md text-[11px] font-bold text-white uppercase tracking-wider">
                    2 Rooms Left
                </div>
            </div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="font-bold text-lg text-white group-hover:text-indigo-400 transition">Kost Putra Residence</h3>
                    <span class="text-indigo-400 font-extrabold text-sm">₱1,800<span class="text-xs text-slate-400 font-normal">/mo</span></span>
                </div>
                <p class="text-xs text-slate-400 mb-4 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                    Sudapin, Kidapawan City
                </p>
                <div class="flex items-center gap-3 text-[11px] text-slate-400 py-3 border-y border-slate-800/80 mb-4">
                    <span class="flex items-center gap-1">💧 Water Supply</span>
                    <span class="flex items-center gap-1">🍳 Kitchen Access</span>
                    <span class="flex items-center gap-1">📚 Study Area</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="/explore" class="flex-1 text-center py-2.5 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition">Book Viewing</a>
                    <a href="/explore" class="py-2.5 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition">Details</a>
                </div>
            </div>
        </div>

        <!-- Item 3 -->
        <div class="glass-card rounded-2xl overflow-hidden border border-slate-800/90 group hover:border-indigo-500/50 transition-all duration-300">
            <div class="relative h-48 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&q=80&w=800" alt="Skyline Central Suites" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-slate-950/80 backdrop-blur-md text-xs font-bold text-amber-400 flex items-center gap-1 border border-white/10">
                    <span>★</span> 4.9
                </div>
                <div class="absolute bottom-3 left-3 px-2.5 py-0.5 rounded bg-emerald-500/90 backdrop-blur-md text-[11px] font-bold text-white uppercase tracking-wider">
                    Available Now
                </div>
            </div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="font-bold text-lg text-white group-hover:text-indigo-400 transition">Skyline Central Suites</h3>
                    <span class="text-indigo-400 font-extrabold text-sm">₱3,500<span class="text-xs text-slate-400 font-normal">/mo</span></span>
                </div>
                <p class="text-xs text-slate-400 mb-4 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                    Apo Sandawa Homes, Kidapawan
                </p>
                <div class="flex items-center gap-3 text-[11px] text-slate-400 py-3 border-y border-slate-800/80 mb-4">
                    <span class="flex items-center gap-1">⚡ Backup Power</span>
                    <span class="flex items-center gap-1">🧺 Laundry Area</span>
                    <span class="flex items-center gap-1">🛵 Parking</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="/explore" class="flex-1 text-center py-2.5 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition">Book Viewing</a>
                    <a href="/explore" class="py-2.5 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition">Details</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
