<template>
  <AppLayout page-title="Student Dashboard">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white mb-7 shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative overflow-hidden">
      <div class="relative z-10">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-indigo-200 text-xs font-bold mb-3 backdrop-blur-md">
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M12 14l9-5-9-5-9 5 9 5z" />
            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.22" />
          </svg>
          Student Living Workspace
        </span>
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Welcome back, {{ user.name }}!</h1>
        <p class="text-xs text-indigo-100/80 mt-1 max-w-xl font-normal">
          Search for verified student boarding houses in Kidapawan City, book in-person viewings, and manage room reservations.
        </p>
      </div>
      <Link
        href="/student/explore"
        class="relative z-10 px-5 py-3 rounded-2xl bg-white text-indigo-900 hover:bg-slate-100 font-black text-xs shadow-md transition shrink-0 flex items-center justify-center gap-2"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <span>Search Boarding Houses</span>
      </Link>
    </div>

    <!-- 4 Stats Cards Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">
      <div v-for="stat in statCards" :key="stat.label" class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
          <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">{{ stat.label }}</span>
          <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
            <svg v-if="stat.iconType === 'calendar'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            <svg v-else-if="stat.iconType === 'clock'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <svg v-else-if="stat.iconType === 'document'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            <svg v-else-if="stat.iconType === 'key'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 0121 9z" /></svg>
          </div>
        </div>
        <div class="text-3xl font-black text-slate-900 tracking-tight">{{ stat.value }}</div>
        <p class="text-[11px] mt-1 font-semibold" :class="stat.subColor">{{ stat.sub }}</p>
      </div>
    </div>

    <!-- Two column layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <!-- Recent Appointments -->
      <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between mb-5">
          <h3 class="font-extrabold text-slate-900 text-base">My Appointments</h3>
          <Link href="/student/appointments" class="text-xs text-indigo-600 hover:text-indigo-700 font-bold transition">View All →</Link>
        </div>
        <div class="space-y-3" v-if="appointments.length">
          <div v-for="apt in appointments" :key="apt.id" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-3">
            <div>
              <p class="text-xs font-bold text-slate-900">{{ apt.boarding_house?.name }}</p>
              <p class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1">
                <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                {{ apt.scheduled_date }} • {{ apt.time_slot }}
              </p>
            </div>
            <span :class="statusColors[apt.status]" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize shrink-0">
              {{ apt.status }}
            </span>
          </div>
        </div>
        <div v-else class="text-center py-8">
          <p class="text-xs text-slate-500 mb-3 font-medium">No viewing appointments scheduled.</p>
          <Link href="/student/explore" class="inline-block px-4 py-2 rounded-xl bg-indigo-50 text-indigo-700 font-bold text-xs hover:bg-indigo-100 transition">Explore Houses to Book →</Link>
        </div>
      </div>

      <!-- Recent Reservations -->
      <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between mb-5">
          <h3 class="font-extrabold text-slate-900 text-base">My Reservations</h3>
          <Link href="/student/reservations" class="text-xs text-indigo-600 hover:text-indigo-700 font-bold transition">View All →</Link>
        </div>
        <div class="space-y-3" v-if="reservations.length">
          <div v-for="res in reservations" :key="res.id" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-3">
            <div>
              <p class="text-xs font-bold text-slate-900">{{ res.room?.boarding_house?.name }} — Room {{ res.room?.room_number }}</p>
              <p class="text-[11px] text-slate-500 mt-0.5">Intended Move-in: {{ res.intended_move_in_date }}</p>
            </div>
            <div class="text-right shrink-0">
              <span :class="statusColors[res.status]" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize">
                {{ res.status }}
              </span>
              <div class="text-[10px] font-mono text-indigo-600 font-bold mt-1">Ref: #{{ res.qr_reference }}</div>
            </div>
          </div>
        </div>
        <div v-else class="text-center py-8">
          <p class="text-xs text-slate-500 mb-3 font-medium">No room reservations submitted yet.</p>
          <Link href="/student/explore" class="inline-block px-4 py-2 rounded-xl bg-indigo-50 text-indigo-700 font-bold text-xs hover:bg-indigo-100 transition">Browse Available Rooms →</Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  stats: Object,
  appointments: Array,
  reservations: Array,
});

const page = usePage();
const user = computed(() => page.props.auth.user ?? {});

const statusColors = {
  pending: 'bg-amber-50 text-amber-700 border-amber-200',
  approved: 'bg-emerald-50 text-emerald-700 border-emerald-200',
  rejected: 'bg-rose-50 text-rose-700 border-rose-200',
  reserved: 'bg-indigo-50 text-indigo-700 border-indigo-200',
  occupied: 'bg-purple-50 text-purple-700 border-purple-200',
  completed: 'bg-slate-100 text-slate-700 border-slate-200',
};

const statCards = computed(() => [
  { label: 'Total Appointments', value: props.stats?.total_appointments ?? 0, iconType: 'calendar', sub: 'Scheduled visits', subColor: 'text-slate-600' },
  { label: 'Pending Appts', value: props.stats?.pending_appointments ?? 0, iconType: 'clock', sub: 'Awaiting owner review', subColor: 'text-amber-700' },
  { label: 'Reservations', value: props.stats?.total_reservations ?? 0, iconType: 'document', sub: 'Total requests', subColor: 'text-slate-600' },
  { label: 'Active Status', value: props.stats?.active_reservations ?? 0, iconType: 'key', sub: 'Confirmed slots', subColor: 'text-emerald-700' },
]);
</script>
