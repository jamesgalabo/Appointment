<template>
  <AppLayout page-title="Analytics & Reports">
    <!-- Header Section with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Platform Analytics</h1>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Performance metrics, occupancy trends, and booking statistics</p>
      </div>

      <div class="flex items-center gap-2 flex-wrap">
        <button
          @click="selectedTab = 'overview'"
          :class="selectedTab === 'overview' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-50 border border-slate-200'"
          class="px-3.5 py-2 rounded-xl text-xs font-bold transition"
        >
          Overview
        </button>
        <button
          @click="selectedTab = 'trends'"
          :class="selectedTab === 'trends' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-50 border border-slate-200'"
          class="px-3.5 py-2 rounded-xl text-xs font-bold transition"
        >
          Trends
        </button>
        <button
          @click="exportSummary"
          class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 transition shadow-2xs"
        >
          <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          Export Summary
        </button>
      </div>
    </div>

    <!-- 8 Metric Cards Grid (Lettiwo style with colored accent bars) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <!-- Card 1: Total Reservations -->
      <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
        <div class="flex items-center justify-between mb-2">
          <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Reservations</span>
          <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">📑</div>
        </div>
        <div class="text-2xl font-black text-slate-900">{{ totalReservationsCount }}</div>
        <div class="flex items-center gap-1.5 mt-1 text-[11px] text-emerald-600 font-semibold">
          <span>↑ +18.4%</span>
          <span class="text-slate-400 font-normal">vs last month</span>
        </div>
      </div>

      <!-- Card 2: Occupancy Rate -->
      <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-400 to-teal-500"></div>
        <div class="flex items-center justify-between mb-2">
          <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Occupancy Rate</span>
          <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">🏢</div>
        </div>
        <div class="text-2xl font-black text-slate-900">{{ occupancyRate }}%</div>
        <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-500">
          <span class="font-semibold text-slate-700">{{ occupiedRooms }}/{{ totalRooms }}</span> rooms occupied
        </div>
      </div>

      <!-- Card 3: Est. Monthly Revenue -->
      <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-500 to-indigo-600"></div>
        <div class="flex items-center justify-between mb-2">
          <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Monthly Volume</span>
          <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs">₱</div>
        </div>
        <div class="text-2xl font-black text-slate-900">₱{{ formatNumber(totalMonthlyRent) }}</div>
        <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-500">
          <span class="text-slate-400">Potential: ₱{{ formatNumber(potentialMonthlyRent) }}</span>
        </div>
      </div>

      <!-- Card 4: Appointments -->
      <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 to-orange-500"></div>
        <div class="flex items-center justify-between mb-2">
          <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Appointments</span>
          <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">📅</div>
        </div>
        <div class="text-2xl font-black text-slate-900">{{ totalAppointmentsCount }}</div>
        <div class="flex items-center gap-1.5 mt-1 text-[11px] text-amber-700 font-semibold">
          <span>● {{ statusBreakdown?.appointments_pending || 0 }} pending</span>
        </div>
      </div>
    </div>

    <!-- Chart & Timeline Card (Lettiwo Analytics visual) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 mb-6">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
          <h3 class="text-base font-extrabold text-slate-900">Booking & Activity Trajectory</h3>
          <p class="text-xs text-slate-500 font-medium">Monthly booking volume throughout 2026</p>
        </div>

        <!-- Month Filter Tabs -->
        <div class="flex items-center gap-1 overflow-x-auto pb-1 sm:pb-0">
          <button
            @click="filterMonth = 'all'"
            :class="filterMonth === 'all' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold'"
            class="px-2.5 py-1 rounded-lg text-xs transition shrink-0"
          >
            All
          </button>
          <button
            v-for="m in monthlyData"
            :key="m.month"
            @click="filterMonth = m.month"
            :class="filterMonth === m.month ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold'"
            class="px-2.5 py-1 rounded-lg text-xs transition shrink-0"
          >
            {{ m.month }}
          </button>
        </div>
      </div>

      <!-- Interactive SVG Chart Area -->
      <div class="relative w-full h-64 bg-slate-50/70 rounded-xl p-4 border border-slate-100 flex flex-col justify-end">
        <!-- SVG Canvas -->
        <svg class="w-full h-48 overflow-visible" viewBox="0 0 800 200" preserveAspectRatio="none">
          <defs>
            <linearGradient id="gradientReservations" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#10b981" stop-opacity="0.35" />
              <stop offset="100%" stop-color="#10b981" stop-opacity="0.0" />
            </linearGradient>
            <linearGradient id="gradientAppointments" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#6366f1" stop-opacity="0.25" />
              <stop offset="100%" stop-color="#6366f1" stop-opacity="0.0" />
            </linearGradient>
          </defs>

          <!-- Grid lines -->
          <line x1="0" y1="40" x2="800" y2="40" stroke="#e2e8f0" stroke-dasharray="4" stroke-width="1" />
          <line x1="0" y1="90" x2="800" y2="90" stroke="#e2e8f0" stroke-dasharray="4" stroke-width="1" />
          <line x1="0" y1="140" x2="800" y2="140" stroke="#e2e8f0" stroke-dasharray="4" stroke-width="1" />

          <!-- Area Fills -->
          <path :d="reservationAreaPath" fill="url(#gradientReservations)" />
          <path :d="appointmentAreaPath" fill="url(#gradientAppointments)" />

          <!-- Curves -->
          <path :d="reservationLinePath" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" />
          <path :d="appointmentLinePath" fill="none" stroke="#6366f1" stroke-width="3" stroke-linecap="round" />

          <!-- Data Points -->
          <g v-for="(pt, idx) in chartPoints" :key="idx">
            <circle :cx="pt.x" :cy="pt.resY" r="5" fill="#10b981" class="transition hover:r-7 cursor-pointer" />
            <circle :cx="pt.x" :cy="pt.appY" r="5" fill="#6366f1" class="transition hover:r-7 cursor-pointer" />
          </g>
        </svg>

        <!-- Chart X-axis month labels -->
        <div class="flex justify-between items-center pt-3 text-[10px] font-bold text-slate-400 border-t border-slate-200">
          <span v-for="m in monthlyData" :key="m.month">{{ m.month }}</span>
        </div>
      </div>

      <!-- Chart Legend -->
      <div class="flex items-center gap-6 mt-4 pt-3 border-t border-slate-100 text-xs">
        <div class="flex items-center gap-2">
          <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
          <span class="font-semibold text-slate-700">Room Reservations (Active / Completed)</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="w-3 h-3 rounded-full bg-indigo-600"></span>
          <span class="font-semibold text-slate-700">Student Viewing Appointments</span>
        </div>
      </div>
    </div>

    <!-- 2 Column Breakdown Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Left 2 Cols: Boarding Houses Occupancy Summary -->
      <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h3 class="font-extrabold text-slate-900 text-sm">Boarding House Performance</h3>
            <p class="text-xs text-slate-500">Occupancy rate per registered boarding house</p>
          </div>
          <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">
            {{ housesSummary?.length || 0 }} Houses Listed
          </span>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-xs">
            <thead>
              <tr class="border-b border-slate-200 text-slate-500 uppercase tracking-wider bg-slate-50/70">
                <th class="py-2.5 px-3 text-left font-bold">House Name</th>
                <th class="py-2.5 px-3 text-left font-bold">Barangay</th>
                <th class="py-2.5 px-3 text-center font-bold">Total Rooms</th>
                <th class="py-2.5 px-3 text-center font-bold">Occupied</th>
                <th class="py-2.5 px-3 text-left font-bold">Occupancy</th>
                <th class="py-2.5 px-3 text-right font-bold">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="house in housesSummary" :key="house.id" class="hover:bg-slate-50/80 transition">
                <td class="py-3 px-3 font-bold text-slate-900">{{ house.name }}</td>
                <td class="py-3 px-3 text-slate-600">{{ house.barangay }}</td>
                <td class="py-3 px-3 text-center font-semibold text-slate-700">{{ house.rooms_count }}</td>
                <td class="py-3 px-3 text-center font-bold text-emerald-600">{{ house.occupied_count }}</td>
                <td class="py-3 px-3">
                  <div class="flex items-center gap-2">
                    <div class="w-24 bg-slate-200 h-2 rounded-full overflow-hidden">
                      <div class="h-full bg-emerald-500 rounded-full" :style="{ width: house.occupancy_rate + '%' }"></div>
                    </div>
                    <span class="font-bold text-slate-700">{{ house.occupancy_rate }}%</span>
                  </div>
                </td>
                <td class="py-3 px-3 text-right">
                  <span
                    :class="house.status === 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200'"
                    class="px-2 py-0.5 rounded-full text-[10px] font-bold border"
                  >
                    {{ house.status }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Right 1 Col: Status Distribution Card -->
      <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
        <h3 class="font-extrabold text-slate-900 text-sm mb-1">Status Overview</h3>
        <p class="text-xs text-slate-500 mb-4">Breakdown of current requests</p>

        <div class="space-y-3">
          <div class="p-3 rounded-xl bg-emerald-50/80 border border-emerald-100">
            <div class="flex items-center justify-between text-xs mb-1">
              <span class="font-bold text-emerald-900">Approved Reservations</span>
              <span class="font-extrabold text-emerald-700">{{ statusBreakdown?.reservations_approved || 0 }}</span>
            </div>
            <p class="text-[11px] text-emerald-600">Successfully booked students</p>
          </div>

          <div class="p-3 rounded-xl bg-amber-50/80 border border-amber-100">
            <div class="flex items-center justify-between text-xs mb-1">
              <span class="font-bold text-amber-900">Pending Requests</span>
              <span class="font-extrabold text-amber-700">{{ (statusBreakdown?.reservations_pending || 0) + (statusBreakdown?.appointments_pending || 0) }}</span>
            </div>
            <p class="text-[11px] text-amber-600">Awaiting house owner confirmation</p>
          </div>

          <div class="p-3 rounded-xl bg-indigo-50/80 border border-indigo-100">
            <div class="flex items-center justify-between text-xs mb-1">
              <span class="font-bold text-indigo-900">Completed Appointments</span>
              <span class="font-extrabold text-indigo-700">{{ statusBreakdown?.appointments_completed || 0 }}</span>
            </div>
            <p class="text-[11px] text-indigo-600">Finished boarding house viewings</p>
          </div>

          <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
            <div class="flex items-center justify-between text-xs mb-1">
              <span class="font-bold text-slate-700">Available Vacancies</span>
              <span class="font-extrabold text-slate-900">{{ availableRooms }} units</span>
            </div>
            <p class="text-[11px] text-slate-500">Ready for instant reservation</p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  monthlyData: { type: Array, default: () => [] },
  occupancyRate: { type: Number, default: 0 },
  totalRooms: { type: Number, default: 0 },
  occupiedRooms: { type: Number, default: 0 },
  availableRooms: { type: Number, default: 0 },
  maintenanceRooms: { type: Number, default: 0 },
  totalMonthlyRent: { type: Number, default: 0 },
  potentialMonthlyRent: { type: Number, default: 0 },
  statusBreakdown: { type: Object, default: () => ({}) },
  housesSummary: { type: Array, default: () => [] },
});

const selectedTab = ref('overview');
const filterMonth = ref('all');

const totalReservationsCount = computed(() => {
  return props.monthlyData.reduce((acc, cur) => acc + (cur.reservations || 0), 0);
});

const totalAppointmentsCount = computed(() => {
  return props.monthlyData.reduce((acc, cur) => acc + (cur.appointments || 0), 0);
});

function formatNumber(num) {
  return Number(num || 0).toLocaleString();
}

function exportSummary() {
  window.print();
}

// SVG Chart path generators
const chartPoints = computed(() => {
  const data = props.monthlyData || [];
  if (data.length === 0) return [];
  const maxVal = Math.max(10, ...data.map(d => Math.max(d.reservations || 0, d.appointments || 0)));
  const stepX = 800 / (data.length - 1 || 1);

  return data.map((d, i) => {
    const x = i * stepX;
    // Map value to 20..170 (higher is lower Y in SVG)
    const resY = 170 - ((d.reservations || 0) / maxVal) * 130;
    const appY = 170 - ((d.appointments || 0) / maxVal) * 130;
    return { x, resY, appY, reservations: d.reservations, appointments: d.appointments };
  });
});

const reservationLinePath = computed(() => {
  const pts = chartPoints.value;
  if (pts.length === 0) return '';
  return pts.reduce((acc, p, i) => `${acc} ${i === 0 ? 'M' : 'L'} ${p.x} ${p.resY}`, '');
});

const reservationAreaPath = computed(() => {
  const pts = chartPoints.value;
  if (pts.length === 0) return '';
  const first = pts[0];
  const last = pts[pts.length - 1];
  return `${reservationLinePath.value} L ${last.x} 190 L ${first.x} 190 Z`;
});

const appointmentLinePath = computed(() => {
  const pts = chartPoints.value;
  if (pts.length === 0) return '';
  return pts.reduce((acc, p, i) => `${acc} ${i === 0 ? 'M' : 'L'} ${p.x} ${p.appY}`, '');
});

const appointmentAreaPath = computed(() => {
  const pts = chartPoints.value;
  if (pts.length === 0) return '';
  const first = pts[0];
  const last = pts[pts.length - 1];
  return `${appointmentLinePath.value} L ${last.x} 190 L ${first.x} 190 Z`;
});
</script>
