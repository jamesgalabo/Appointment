<template>
  <div class="min-h-screen bg-slate-50 text-slate-800 flex flex-col lg:flex-row antialiased">
    <!-- Sidebar Navigation -->
    <aside class="w-full lg:w-64 border-r border-slate-200 bg-white p-5 flex flex-col justify-between shrink-0 shadow-2xs">
      <div class="space-y-6">
        <div class="flex items-center gap-3 px-2 py-1">
          <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center font-black text-white text-sm shadow-md shadow-indigo-500/20">
            KB
          </div>
          <div>
            <h2 class="text-sm font-extrabold text-slate-900">KidaBoard</h2>
            <p class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Kidapawan Portal
            </p>
          </div>
        </div>

        <div class="space-y-1">
          <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Overview</p>
          <button
            @click="activeTab = 'dashboard'"
            :class="activeTab === 'dashboard' ? 'bg-indigo-50 text-indigo-700 font-bold border-indigo-200 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 border-transparent'"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs border transition"
          >
            <span>📊</span>
            <span>Dashboard</span>
          </button>
          <button
            @click="activeTab = 'calendar'"
            :class="activeTab === 'calendar' ? 'bg-indigo-50 text-indigo-700 font-bold border-indigo-200 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 border-transparent'"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs border transition"
          >
            <span>📅</span>
            <span>Calendar Schedule</span>
          </button>
        </div>

        <div class="space-y-1">
          <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Navigation</p>
          <a href="/" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-slate-100 font-semibold text-xs transition">
            <span>🏠 Return Home</span>
          </a>
          <a href="/login" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-slate-100 font-semibold text-xs transition">
            <span>🔐 Login / Switch Account</span>
          </a>
        </div>
      </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            {{ activeTab === 'dashboard' ? 'Bookings & Operations' : 'Interactive Inspection Calendar' }}
          </h1>
          <p class="text-xs text-slate-500">Period: <span class="text-indigo-600 font-bold">2026 Academic Year (Kidapawan City)</span></p>
        </div>
      </div>

      <!-- TAB 1: DASHBOARD VIEW -->
      <div v-if="activeTab === 'dashboard'">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
          <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs">
            <div class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">Current Bookings</div>
            <div class="text-3xl font-extrabold text-slate-900 mb-1">320</div>
            <p class="text-xs text-emerald-700 font-bold">↑ 18% vs last year</p>
          </div>

          <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs">
            <div class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">Occupancy Rate</div>
            <div class="text-3xl font-extrabold text-slate-900 mb-1">84.2%</div>
            <p class="text-xs text-emerald-700 font-bold">Across 25 boarding houses</p>
          </div>

          <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs">
            <div class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">New Appointments</div>
            <div class="text-3xl font-extrabold text-slate-900 mb-1">+42</div>
            <p class="text-xs text-indigo-700 font-bold">Scheduled viewing visits</p>
          </div>

          <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs">
            <div class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-2">Estimated Revenue</div>
            <div class="text-3xl font-extrabold text-slate-900 mb-1">₱768,000</div>
            <p class="text-xs text-emerald-700 font-bold">↑ 15.3% monthly</p>
          </div>
        </div>
      </div>

      <!-- TAB 2: CALENDAR TIMELINE VIEW -->
      <div v-else class="bg-white rounded-2xl p-6 border border-slate-200 shadow-2xs">
        <h3 class="text-lg font-bold text-slate-900 mb-2">Room Reservation Timeline</h3>
        <p class="text-xs text-slate-500">Scheduled appointments and student stays across Kidapawan boarding houses</p>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const activeTab = ref('dashboard');
</script>
