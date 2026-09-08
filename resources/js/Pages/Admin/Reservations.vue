<template>
  <AppLayout page-title="All Reservations">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Room Reservations</h1>
        <p class="text-xs text-slate-500 font-medium mt-0.5">All student room reservations across Kidapawan boarding houses</p>
      </div>

      <div class="flex items-center gap-1.5 p-1 bg-white border border-slate-200/80 rounded-xl shadow-2xs">
        <button
          v-for="st in ['all', 'pending', 'approved', 'cancelled']"
          :key="st"
          @click="filterStatus = st"
          :class="filterStatus === st ? 'bg-indigo-600 text-white font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
          class="px-3 py-1.5 rounded-lg text-xs capitalize transition"
        >
          {{ st }}
        </button>
      </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs">
      <div class="overflow-x-auto">
        <table class="w-full text-xs">
          <thead>
            <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider">
              <th class="px-5 py-3.5 text-left font-bold">QR Ref / ID</th>
              <th class="px-5 py-3.5 text-left font-bold">Student Name</th>
              <th class="px-5 py-3.5 text-left font-bold">Boarding House & Room</th>
              <th class="px-5 py-3.5 text-left font-bold">Move-in Date</th>
              <th class="px-5 py-3.5 text-left font-bold">Monthly Rent</th>
              <th class="px-5 py-3.5 text-right font-bold">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="res in filteredReservations" :key="res.id" class="hover:bg-slate-50/80 transition">
              <td class="px-5 py-3.5">
                <span class="font-mono text-indigo-700 font-bold bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200/60">
                  #{{ res.qr_reference ?? res.id }}
                </span>
              </td>
              <td class="px-5 py-3.5 font-bold text-slate-900">{{ res.student?.name ?? '—' }}</td>
              <td class="px-5 py-3.5">
                <div class="font-semibold text-slate-800">{{ res.room?.boarding_house?.name ?? '—' }}</div>
                <div class="text-[11px] text-slate-400">Room {{ res.room?.room_number ?? '—' }}</div>
              </td>
              <td class="px-5 py-3.5 text-slate-700 font-medium">{{ res.intended_move_in_date }}</td>
              <td class="px-5 py-3.5 font-bold text-indigo-700">₱{{ formatNumber(res.room?.monthly_rent) }}</td>
              <td class="px-5 py-3.5 text-right">
                <span
                  :class="statusColors[res.status]"
                  class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize"
                >
                  ● {{ res.status }}
                </span>
              </td>
            </tr>
            <tr v-if="!filteredReservations.length">
              <td colspan="6" class="px-5 py-10 text-center text-slate-500 font-medium">No reservations match the selected status.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ reservations: Object });
const reservationList = computed(() => props.reservations?.data ?? props.reservations ?? []);
const filterStatus = ref('all');

const filteredReservations = computed(() => {
  if (filterStatus.value === 'all') return reservationList.value;
  return reservationList.value.filter(r => r.status === filterStatus.value);
});

function formatNumber(val) {
  return Number(val || 0).toLocaleString();
}

const statusColors = {
  pending: 'bg-amber-50 text-amber-700 border-amber-200',
  approved: 'bg-emerald-50 text-emerald-700 border-emerald-200',
  rejected: 'bg-rose-50 text-rose-700 border-rose-200',
  reserved: 'bg-indigo-50 text-indigo-700 border-indigo-200',
  cancelled: 'bg-slate-100 text-slate-600 border-slate-200',
};
</script>
