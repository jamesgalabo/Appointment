<template>
  <AppLayout page-title="Admin Dashboard">
    <!-- Header -->
    <div class="flex flex-col gap-3 mb-6">
      <div>
        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Admin Dashboard</h1>
        <p class="text-xs text-slate-500 font-medium mt-0.5">KidaBoard Platform Overview • Kidapawan City</p>
      </div>
      <Link
        href="/admin/reports"
        class="inline-flex items-center justify-center sm:self-start gap-2 w-full sm:w-auto px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 20V10M12 20V4M6 20v-6" /></svg> View Reports & Analytics
      </Link>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-7">
      <div v-for="stat in statCards" :key="stat.label" class="bg-white rounded-2xl p-3.5 sm:p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition">
        <div class="flex items-center justify-between mb-2 sm:mb-3">
          <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 leading-tight pr-1">{{ stat.label }}</span>
          <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" :d="statIconPath(stat.iconKey)" /></svg>
          </div>
        </div>
        <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ stat.value }}</div>
        <p class="text-[10px] sm:text-[11px] mt-1 font-semibold leading-tight hidden sm:block" :class="stat.subColor">{{ stat.sub }}</p>
      </div>
    </div>

    <!-- Two column layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
      <!-- Recent Users -->
      <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between mb-5">
          <h3 class="font-extrabold text-slate-900 text-base">Recent Registrations</h3>
          <Link href="/admin/users" class="text-xs text-indigo-600 hover:text-indigo-700 font-bold transition">View All Users →</Link>
        </div>
        <div class="space-y-3">
          <div v-for="user in recentUsers" :key="user.id" class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200/80">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-2xs">
              {{ user.name.charAt(0).toUpperCase() }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-bold text-slate-900 truncate">{{ user.name }}</p>
              <p class="text-[11px] text-slate-500 truncate">{{ user.email }}</p>
            </div>
            <span :class="roleColors[user.role]" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold capitalize border shrink-0">{{ user.role }}</span>
          </div>
        </div>
      </div>

      <!-- Recent Houses -->
      <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between mb-5">
          <h3 class="font-extrabold text-slate-900 text-base">Boarding Houses</h3>
          <Link href="/admin/houses" class="text-xs text-indigo-600 hover:text-indigo-700 font-bold transition">Manage Houses →</Link>
        </div>
        <div class="space-y-3">
          <div v-for="house in recentHouses" :key="house.id" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
            <p class="text-xs font-bold text-slate-900 mb-0.5">{{ house.name }}</p>
            <p class="text-[11px] text-slate-500">{{ house.barangay }}</p>
            <div class="flex items-center justify-between mt-2">
              <span class="flex items-center gap-1 text-[11px] text-amber-600 font-bold">&#9733; {{ house.rating }}</span>
              <span :class="house.status === 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200'"
                class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize">{{ house.status }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Occupancy Bar -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200/80 shadow-xs">
      <div class="flex flex-wrap items-start sm:items-center justify-between gap-2 mb-4">
        <h3 class="font-extrabold text-slate-900 text-sm">Room Occupancy Overview</h3>
        <span class="text-xs text-slate-600 font-semibold">{{ stats.available_rooms }} available / {{ stats.occupied_rooms }} occupied</span>
      </div>
      <div class="w-full bg-slate-100 rounded-full h-3.5 overflow-hidden border border-slate-200">
        <div
          class="h-full bg-indigo-600 rounded-full transition-all duration-700"
          :style="{ width: occupancyRate + '%' }"
        ></div>
      </div>
      <div class="flex items-center justify-between mt-2 text-xs text-slate-500">
        <span>0%</span>
        <span class="text-slate-900 font-bold">{{ occupancyRate }}% Occupied</span>
        <span>100%</span>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  stats: Object,
  recentUsers: Array,
  recentHouses: Array,
  recentReservations: Array,
});

const roleColors = {
  admin: 'bg-rose-50 text-rose-700 border-rose-200',
  owner: 'bg-indigo-50 text-indigo-700 border-indigo-200',
  student: 'bg-emerald-50 text-emerald-700 border-emerald-200',
};

const occupancyRate = computed(() => {
  const total = (props.stats?.available_rooms ?? 0) + (props.stats?.occupied_rooms ?? 0);
  if (!total) return 0;
  return Math.round(((props.stats?.occupied_rooms ?? 0) / total) * 100);
});

const statCards = computed(() => [
  { label: 'Total Students', value: props.stats?.total_students ?? 0, iconKey: 'user', sub: 'Registered on KidaBoard', subColor: 'text-emerald-700' },
  { label: 'Property Owners', value: props.stats?.total_owners ?? 0, iconKey: 'building', sub: 'Active listing partners', subColor: 'text-indigo-700' },
  { label: 'Boarding Houses', value: props.stats?.total_houses ?? 0, iconKey: 'layers', sub: `${props.stats?.pending_houses ?? 0} pending approval`, subColor: 'text-amber-700' },
  { label: 'Active Tenants', value: props.stats?.active_tenants ?? 0, iconKey: 'users', sub: 'Currently occupying rooms', subColor: 'text-indigo-700' },
  { label: 'Total Rooms', value: props.stats?.total_rooms ?? 0, iconKey: 'grid', sub: `${props.stats?.available_rooms ?? 0} available now`, subColor: 'text-emerald-700' },
  { label: 'Reservations', value: props.stats?.total_reservations ?? 0, iconKey: 'clipboard', sub: 'All-time reservations', subColor: 'text-slate-600' },
  { label: 'Appointments', value: props.stats?.total_appointments ?? 0, iconKey: 'calendar', sub: `${props.stats?.pending_appointments ?? 0} pending`, subColor: 'text-amber-700' },
  { label: 'Platform Users', value: props.stats?.total_users ?? 0, iconKey: 'user', sub: 'Total registered accounts', subColor: 'text-slate-600' },
]);

const ICON_PATHS = {
  user:      'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
  building:  'M3 21h18M9 8h1m-1 4h1m-1 4h1M13 8h1m-1 4h1m-1 4h1M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16',
  layers:    'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5',
  users:     'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8zm9 3a3 3 0 11-6 0 3 3 0 016 0zm-1 7v-2a3 3 0 00-3-3h-1',
  grid:      'M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z',
  clipboard: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
  calendar:  'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
};

function statIconPath(key) {
  return ICON_PATHS[key] || ICON_PATHS.grid;
}
</script>
