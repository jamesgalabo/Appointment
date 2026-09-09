<template>
  <AppLayout page-title="Viewing Appointments">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Viewing Appointments</h1>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Manage scheduled in-person property visits from students</p>
      </div>

      <div class="flex items-center gap-1.5 p-1 bg-white border border-slate-200/80 rounded-xl shadow-2xs">
        <button
          v-for="st in ['all', 'pending', 'approved', 'completed']"
          :key="st"
          @click="statusFilter = st"
          :class="statusFilter === st ? 'bg-indigo-600 text-white font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
          class="px-3 py-1.5 rounded-lg text-xs capitalize transition cursor-pointer"
        >
          {{ st }}
        </button>
      </div>
    </div>

    <!-- Appointments Cards -->
    <div v-if="filteredAppointments.length > 0" class="space-y-4">
      <div
        v-for="apt in filteredAppointments"
        :key="apt.id"
        :class="apt.status === 'pending' ? 'border-amber-200 bg-amber-50/20' : 'border-slate-200/80 bg-white'"
        class="rounded-2xl p-5 border shadow-xs transition flex flex-col md:flex-row md:items-center justify-between gap-4"
      >
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
          </div>
          <div>
            <div class="flex items-center gap-2 mb-1 flex-wrap">
              <h3 class="text-base font-extrabold text-slate-900">{{ apt.student?.name ?? 'Student' }}</h3>
              <span
                :class="statusColors[apt.status] || 'bg-slate-100 text-slate-700'"
                class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize"
              >
                ● {{ apt.status }}
              </span>
            </div>

            <div class="flex items-center gap-4 text-xs text-slate-600 font-medium flex-wrap mt-1">
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                <strong class="text-slate-800">{{ apt.scheduled_date }}</strong>
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ apt.time_slot }}
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                {{ apt.student?.email }}
              </span>
              <span v-if="apt.student?.phone" class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                {{ apt.student.phone }}
              </span>
            </div>

            <p v-if="apt.notes" class="text-xs text-slate-500 italic mt-2 bg-slate-50 px-3 py-1 rounded-lg border border-slate-100 max-w-xl">
              "{{ apt.notes }}"
            </p>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2 self-end md:self-center shrink-0">
          <button
            type="button"
            @click="messageStudent(apt)"
            class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 font-bold text-xs transition cursor-pointer"
            title="Chat with Student"
          >
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
            Chat
          </button>

          <template v-if="apt.status === 'pending' || apt.status === 'rescheduled'">
            <button
              @click="askAppointmentAction(apt, 'approved')"
              class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition cursor-pointer"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
              Confirm Visit
            </button>
            <button
              @click="askAppointmentAction(apt, 'rejected')"
              class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition cursor-pointer"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
              Decline
            </button>
          </template>

          <template v-else-if="apt.status === 'approved'">
            <button
              @click="askAppointmentAction(apt, 'completed')"
              class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs border border-indigo-200 transition cursor-pointer"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
              Mark as Completed
            </button>
          </template>

          <template v-else>
            <span class="text-xs font-semibold text-slate-400">Processed</span>
          </template>
        </div>
      </div>
    </div>

    <!-- Empty state -->
    <div v-else class="text-center py-16 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
      <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3 shadow-2xs">
        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
      </div>
      <h3 class="text-base font-extrabold text-slate-900">No Appointments Recorded</h3>
      <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
        When students request a viewing date for your boarding house, they will appear right here.
      </p>
    </div>

    <!-- Confirm Action / Rejection Modal -->
    <div v-if="showConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
      <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl border border-slate-100">
        <h3 class="text-base font-extrabold text-slate-900 mb-1">{{ modalTitle }}</h3>
        <p class="text-xs text-slate-500 mb-4">{{ modalMessage }}</p>

        <!-- Rejection Reason selection when declining -->
        <div v-if="pendingStatus === 'rejected'" class="space-y-3 mb-5">
          <label class="block text-xs font-bold text-slate-700">Reason for declining (sent to student):</label>
          <div class="space-y-1.5">
            <button
              v-for="preset in ['Time slot unavailable - reserved by another student', 'Property undergoing maintenance', 'Landlord unavailable on selected date']"
              :key="preset"
              type="button"
              @click="rejectionReason = preset"
              class="w-full text-left px-3 py-2 rounded-xl text-xs border transition cursor-pointer"
              :class="rejectionReason === preset ? 'bg-indigo-50 border-indigo-300 text-indigo-900 font-bold' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100'"
            >
              • {{ preset }}
            </button>
          </div>

          <textarea
            v-model="rejectionReason"
            rows="2"
            placeholder="Or type a custom reason..."
            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
          ></textarea>
        </div>

        <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
          <button
            type="button"
            @click="showConfirmModal = false"
            class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer"
          >
            Cancel
          </button>
          <button
            type="button"
            @click="executeAppointmentAction"
            :class="pendingStatus === 'rejected' ? 'bg-rose-600 hover:bg-rose-700' : 'bg-indigo-600 hover:bg-indigo-700'"
            class="px-5 py-2 rounded-xl text-white text-xs font-bold shadow-xs transition cursor-pointer"
          >
            {{ modalConfirmText }}
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ house: Object, appointments: Object });
const appointmentList = computed(() => props.appointments?.data ?? props.appointments ?? []);
const statusFilter = ref('all');

const showConfirmModal = ref(false);
const activeApt = ref(null);
const pendingStatus = ref('');

function messageStudent(apt) {
  if (apt.student_id && typeof window !== 'undefined') {
    window.dispatchEvent(new CustomEvent('open-chat', {
      detail: {
        recipientId: apt.student_id,
        houseId: apt.boarding_house_id,
        initialMessage: `Hello ${apt.student?.name || 'student'}, regarding your viewing appointment on ${apt.scheduled_date} (${apt.time_slot}):`,
      },
    }));
  }
}

const filteredAppointments = computed(() => {
  if (statusFilter.value === 'all') return appointmentList.value;
  return appointmentList.value.filter(a => a.status === statusFilter.value);
});

const modalTitle = computed(() => {
  if (pendingStatus.value === 'approved') return 'Confirm Viewing Appointment';
  if (pendingStatus.value === 'rejected') return 'Decline Appointment';
  return 'Mark Appointment as Completed';
});

const modalMessage = computed(() => {
  const student = activeApt.value?.student?.name || 'this student';
  const date = activeApt.value?.scheduled_date || '';
  if (pendingStatus.value === 'approved') {
    return `Are you sure you want to confirm ${student}'s property visit on ${date}?`;
  }
  if (pendingStatus.value === 'rejected') {
    return `Are you sure you want to decline this viewing appointment request from ${student}?`;
  }
  return `Mark this viewing appointment with ${student} as completed?`;
});

const modalConfirmText = computed(() => {
  if (pendingStatus.value === 'approved') return 'Yes, Confirm Visit';
  if (pendingStatus.value === 'rejected') return 'Yes, Decline';
  return 'Yes, Mark Completed';
});

const modalVariant = computed(() => {
  if (pendingStatus.value === 'approved') return 'success';
  if (pendingStatus.value === 'rejected') return 'danger';
  return 'primary';
});

const modalIconType = computed(() => {
  if (pendingStatus.value === 'approved') return 'info';
  if (pendingStatus.value === 'rejected') return 'danger';
  return 'success';
});

const statusColors = {
  pending: 'bg-amber-50 text-amber-700 border-amber-200',
  approved: 'bg-emerald-50 text-emerald-700 border-emerald-200',
  rejected: 'bg-rose-50 text-rose-700 border-rose-200',
  rescheduled: 'bg-indigo-50 text-indigo-700 border-indigo-200',
  completed: 'bg-slate-100 text-slate-700 border-slate-200',
};

const rejectionReason = ref('');

function askAppointmentAction(apt, status) {
  activeApt.value = apt;
  pendingStatus.value = status;
  if (status === 'rejected') {
    rejectionReason.value = 'Time slot unavailable - reserved by another student';
  } else {
    rejectionReason.value = '';
  }
  showConfirmModal.value = true;
}

function executeAppointmentAction() {
  if (!activeApt.value || !pendingStatus.value) return;
  
  const payload = {
    status: pendingStatus.value,
  };

  if (pendingStatus.value === 'rejected' && rejectionReason.value) {
    payload.cancellation_reason = rejectionReason.value;
  }

  router.patch(`/owner/appointments/${activeApt.value.id}`, payload, {
    onSuccess: () => {
      showConfirmModal.value = false;
      activeApt.value = null;
      rejectionReason.value = '';
    },
  });
}
</script>
