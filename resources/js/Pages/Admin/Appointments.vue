<template>
  <AppLayout page-title="All Appointments">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Viewing Appointments</h1>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Platform-wide student viewing visits and property appointments</p>
      </div>

      <div class="flex items-center gap-1.5 p-1 bg-white border border-slate-200/80 rounded-xl shadow-2xs">
        <button
          v-for="st in ['all', 'pending', 'approved', 'completed']"
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
              <th class="px-5 py-3.5 text-left font-bold"># ID</th>
              <th class="px-5 py-3.5 text-left font-bold">Student</th>
              <th class="px-5 py-3.5 text-left font-bold">Boarding House</th>
              <th class="px-5 py-3.5 text-left font-bold">Scheduled Date</th>
              <th class="px-5 py-3.5 text-left font-bold">Time Slot</th>
              <th class="px-5 py-3.5 text-right font-bold">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="apt in filteredAppointments" :key="apt.id" class="hover:bg-slate-50/80 transition">
              <td class="px-5 py-3.5 text-slate-400 font-mono">#{{ apt.id }}</td>
              <td class="px-5 py-3.5 font-bold text-slate-900">{{ apt.student?.name ?? '—' }}</td>
              <td class="px-5 py-3.5 text-slate-700 font-semibold">{{ apt.boarding_house?.name ?? '—' }}</td>
              <td class="px-5 py-3.5 text-slate-800 font-medium">{{ apt.scheduled_date }}</td>
              <td class="px-5 py-3.5 text-slate-600">{{ apt.time_slot }}</td>
              <td class="px-5 py-3.5 text-right">
                <span
                  :class="statusColors[apt.status]"
                  class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize"
                >
                  ● {{ apt.status }}
                </span>
              </td>
            </tr>
            <tr v-if="!filteredAppointments.length">
              <td colspan="6" class="px-5 py-10 text-center text-slate-500 font-medium">No appointments match the selected filter.</td>
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

const props = defineProps({ appointments: Object });
const appointmentList = computed(() => props.appointments?.data ?? props.appointments ?? []);
const filterStatus = ref('all');

const filteredAppointments = computed(() => {
  if (filterStatus.value === 'all') return appointmentList.value;
  return appointmentList.value.filter(a => a.status === filterStatus.value);
});

const statusColors = {
  pending: 'bg-amber-50 text-amber-700 border-amber-200',
  approved: 'bg-emerald-50 text-emerald-700 border-emerald-200',
  rejected: 'bg-rose-50 text-rose-700 border-rose-200',
  completed: 'bg-indigo-50 text-indigo-700 border-indigo-200',
  rescheduled: 'bg-purple-50 text-purple-700 border-purple-200',
};
</script>
