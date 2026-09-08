<template>
  <AppLayout page-title="Owner Dashboard">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <div class="flex items-center gap-2">
          <h1 class="text-2xl font-black text-slate-900 tracking-tight">
            {{ house?.name ?? 'My Boarding House' }}
          </h1>
          <span
            v-if="house"
            :class="house.status === 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200'"
            class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize"
          >
            ● {{ house.status }}
          </span>
        </div>
        <p class="text-xs text-slate-500 font-medium mt-1" v-if="house">
          <span class="inline-flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            {{ house.address }}, {{ house.barangay }}, Kidapawan City • Contact: {{ house.contact_number || 'N/A' }}
          </span>
        </p>
        <p class="text-xs text-amber-700 font-semibold mt-1 flex items-center gap-1" v-else>
          <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
          You haven't configured your boarding house profile yet.
          <Link href="/owner/profile" class="underline font-bold text-indigo-600">Complete House Profile →</Link>
        </p>
      </div>

      <div class="flex items-center gap-2">
        <Link
          href="/owner/rooms"
          class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition"
        >
          <span>+</span> Add / Manage Rooms
        </Link>
        <Link
          href="/owner/reservations"
          class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold text-xs shadow-2xs transition"
        >
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
          View Reservations
        </Link>
      </div>
    </div>

    <!-- 4 SaaS KPI Cards (Hotelrev Style) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <!-- Card 1: Occupancy Rate -->
      <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs relative overflow-hidden">
        <div class="flex items-center justify-between mb-2">
          <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Occupancy Rate</span>
          <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
          </div>
        </div>
        <div class="text-2xl font-black text-slate-900">{{ occupancyRate }}%</div>
        <p class="text-[11px] text-slate-500 mt-1">of total room capacity</p>
      </div>

      <!-- Card 2: Pending Requests -->
      <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs relative overflow-hidden">
        <div class="flex items-center justify-between mb-2">
          <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Action Needed</span>
          <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          </div>
        </div>
        <div class="text-2xl font-black text-slate-900">{{ (stats?.pending_appointments || 0) }}</div>
        <p class="text-[11px] text-amber-700 font-semibold mt-1">pending viewing appointments</p>
      </div>

      <!-- Card 3: Available Rooms -->
      <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs relative overflow-hidden">
        <div class="flex items-center justify-between mb-2">
          <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Available Rooms</span>
          <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
          </div>
        </div>
        <div class="text-2xl font-black text-slate-900">{{ stats?.available_rooms ?? 0 }}</div>
        <p class="text-[11px] text-slate-500 mt-1">rooms ready for booking</p>
      </div>

      <!-- Card 4: Total Revenue Potential -->
      <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs relative overflow-hidden">
        <div class="flex items-center justify-between mb-2">
          <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Estimated Rent</span>
          <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-black">₱</div>
        </div>
        <div class="text-2xl font-black text-slate-900">₱{{ formatNumber(estimatedMonthlyRevenue) }}</div>
        <p class="text-[11px] text-slate-500 mt-1">from active occupied units</p>
      </div>
    </div>

    <!-- 2 Column Layout: Room Status Board + Recent Bookings Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Left 2 Cols: Visual Room Status Grid (Like Image 5) -->
      <div class="lg:col-span-2 bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
          <div class="flex items-center gap-3">
            <h3 class="font-extrabold text-slate-900 text-base">Rooms Status Board</h3>
            <div class="flex items-center gap-2 text-xs">
              <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-bold border border-emerald-200">Available: {{ stats?.available_rooms ?? 0 }}</span>
              <span class="px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 font-bold border border-rose-200">Occupied: {{ stats?.occupied_rooms ?? 0 }}</span>
            </div>
          </div>
          <Link href="/owner/rooms" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition">
            Manage All Rooms →
          </Link>
        </div>

        <div v-if="rooms && rooms.length > 0" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3.5">
          <div
            v-for="room in rooms"
            :key="room.id"
            :class="room.availability_status === 'available'
              ? 'bg-emerald-50/60 border-emerald-200/90 hover:border-emerald-300'
              : room.availability_status === 'occupied'
              ? 'bg-rose-50/60 border-rose-200/90 hover:border-rose-300'
              : 'bg-amber-50/60 border-amber-200/90 hover:border-amber-300'"
            class="p-3.5 rounded-xl border transition duration-150 flex flex-col justify-between"
          >
            <div>
              <div class="flex items-center justify-between gap-1.5 mb-1">
                <span class="text-sm font-black text-slate-900 truncate" :title="displayRoomNumber(room.room_number)">
                  {{ displayRoomNumber(room.room_number) }}
                </span>
                <span
                  :class="room.availability_status === 'available'
                    ? 'bg-emerald-100 text-emerald-800 border-emerald-200'
                    : room.availability_status === 'occupied'
                    ? 'bg-rose-100 text-rose-800 border-rose-200'
                    : 'bg-amber-100 text-amber-800 border-amber-200'"
                  class="text-[10px] font-extrabold px-1.5 py-0.5 rounded-md border capitalize shrink-0 leading-none"
                >
                  {{ room.availability_status?.replace('_', ' ') }}
                </span>
              </div>
              <p class="text-[11px] text-slate-500 font-medium truncate mb-2.5">
                {{ room.room_type }} • {{ room.capacity }} pax
              </p>
            </div>

            <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
              <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rent</span>
              <div class="text-right shrink-0">
                <span class="text-xs sm:text-sm font-black text-indigo-700">₱{{ formatNumber(room.monthly_rent) }}</span>
                <span class="text-[10px] text-slate-500 font-medium">/mo</span>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="text-center py-12 border-2 border-dashed border-slate-200 rounded-2xl">
          <p class="text-sm font-bold text-slate-700">No rooms listed yet</p>
          <p class="text-xs text-slate-500 mt-1 mb-4">Add your boarding house rooms to start accepting student bookings.</p>
          <Link
            href="/owner/rooms"
            class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold text-xs shadow-xs"
          >
            + Add First Room
          </Link>
        </div>
      </div>

      <!-- Right 1 Col: Recent Appointments / Bookings -->
      <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col">
        <div class="flex items-center justify-between mb-4">
          <h3 class="font-extrabold text-slate-900 text-sm">Recent Appointments</h3>
          <Link href="/owner/appointments" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">
            View All →
          </Link>
        </div>

        <div v-if="recentAppointments && recentAppointments.length > 0" class="space-y-3 flex-1">
          <div
            v-for="apt in recentAppointments"
            :key="apt.id"
            class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 hover:bg-slate-100/70 transition"
          >
            <div class="flex items-start justify-between gap-2 mb-1">
              <p class="text-xs font-bold text-slate-900">{{ apt.student?.name || 'Student' }}</p>
              <span
                :class="statusColors[apt.status] || 'bg-slate-100 text-slate-700'"
                class="px-2 py-0.5 rounded-full text-[10px] font-bold border capitalize shrink-0"
              >
                {{ apt.status }}
              </span>
            </div>
            <p class="text-[11px] text-slate-500 font-medium flex items-center gap-1">
              <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
              {{ formatDate(apt.scheduled_date) }} • {{ apt.time_slot }}
            </p>
            <p v-if="apt.student?.phone" class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
              <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
              {{ apt.student.phone }}
            </p>
          </div>
        </div>

        <div v-else class="text-center py-10 text-xs text-slate-400 font-medium my-auto">
          No appointments recorded yet.
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  house: Object,
  stats: Object,
  recentAppointments: Array,
  rooms: Array,
});

const occupancyRate = computed(() => {
  const total = (props.stats?.available_rooms ?? 0) + (props.stats?.occupied_rooms ?? 0);
  if (!total) return 0;
  return Math.round(((props.stats?.occupied_rooms ?? 0) / total) * 100);
});

const estimatedMonthlyRevenue = computed(() => {
  if (!props.rooms) return 0;
  return props.rooms
    .filter(r => r.availability_status === 'occupied')
    .reduce((acc, cur) => acc + parseFloat(cur.monthly_rent || 0), 0);
});

function formatNumber(val) {
  return Number(val || 0).toLocaleString();
}

function displayRoomNumber(val) {
  if (!val) return '';
  const str = String(val).trim();
  if (/^room\b/i.test(str)) {
    return str;
  }
  return 'Room ' + str;
}

function formatDate(dateStr) {
  if (!dateStr) return '';
  try {
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return dateStr;
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
  } catch (e) {
    return dateStr;
  }
}

const statusColors = {
  pending: 'bg-amber-50 text-amber-700 border-amber-200',
  approved: 'bg-emerald-50 text-emerald-700 border-emerald-200',
  rejected: 'bg-rose-50 text-rose-700 border-rose-200',
  completed: 'bg-indigo-50 text-indigo-700 border-indigo-200',
  rescheduled: 'bg-purple-50 text-purple-700 border-purple-200',
};
</script>
