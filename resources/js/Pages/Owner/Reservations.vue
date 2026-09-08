<template>
  <AppLayout page-title="Reservation Approvals">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Student Reservations</h1>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Review payment details, approve bookings, or reject reservation requests</p>
      </div>

      <!-- Filter status -->
      <div class="flex items-center gap-1.5 p-1 bg-white border border-slate-200/80 rounded-xl shadow-2xs">
        <button
          v-for="status in ['all', 'pending', 'approved', 'cancelled']"
          :key="status"
          @click="statusFilter = status"
          :class="statusFilter === status ? 'bg-indigo-600 text-white font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
          class="px-3 py-1.5 rounded-lg text-xs capitalize transition cursor-pointer"
        >
          {{ status }}
        </button>
      </div>
    </div>

    <!-- Reservations List / Cards -->
    <div v-if="filteredReservations.length > 0" class="space-y-4">
      <div
        v-for="res in filteredReservations"
        :key="res.id"
        :class="res.status === 'pending' ? 'border-amber-200 bg-amber-50/20' : 'border-slate-200/80 bg-white'"
        class="rounded-2xl p-5 border shadow-xs transition flex flex-col md:flex-row md:items-center justify-between gap-5"
      >
        <!-- Left info: Student + Room + Payment Proof -->
        <div class="flex items-start gap-4">
          <!-- Room photo -->
          <div class="w-16 h-16 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
            <img
              :src="res.room?.photo_url || 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=300&q=80'"
              class="w-full h-full object-cover"
            />
          </div>

          <div>
            <div class="flex items-center gap-2 mb-1 flex-wrap">
              <h3 class="text-base font-extrabold text-slate-900">{{ res.student?.name }}</h3>
              <span
                :class="statusColors[res.status] || 'bg-slate-100 text-slate-700'"
                class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize"
              >
                ● {{ res.status }}
              </span>
              <!-- Payment Method Badge -->
              <span class="px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-bold flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                {{ res.payment_method || 'Cash' }}
              </span>
              <span class="text-[11px] font-mono text-slate-400 bg-slate-100 px-2 py-0.5 rounded">
                Ref: #{{ res.qr_reference || res.id }}
              </span>
            </div>

            <div class="flex items-center gap-4 text-xs text-slate-600 font-medium flex-wrap mt-1">
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <strong class="text-slate-800">Room {{ res.room?.room_number }}</strong> ({{ res.room?.room_type }})
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Monthly Rent: <strong>₱{{ formatNumber(res.room?.monthly_rent) }}</strong>
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                Move-in: <strong class="text-slate-800">{{ res.intended_move_in_date || 'N/A' }}</strong>
              </span>
            </div>

            <div class="flex items-center gap-3 text-[11px] text-slate-500 mt-2 flex-wrap">
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                {{ res.student?.email }}
              </span>
              <span v-if="res.student?.phone" class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                {{ res.student.phone }}
              </span>
              <span v-if="res.payment_reference" class="font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">
                Txn Ref: {{ res.payment_reference }}
              </span>
              <!-- Uploaded Receipt Link -->
              <a
                v-if="res.payment_receipt_url"
                :href="res.payment_receipt_url"
                target="_blank"
                class="text-indigo-600 font-bold underline flex items-center gap-1 hover:text-indigo-800"
              >
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                View Receipt Proof ↗
              </a>
            </div>

            <p v-if="res.remarks" class="text-[11px] text-slate-500 italic mt-1.5 bg-slate-50 px-2.5 py-1 rounded border border-slate-100">
              Note: "{{ res.remarks }}"
            </p>
          </div>
        </div>

        <!-- Right actions: Approve / Reject with Confirm Dialog -->
        <div class="flex items-center gap-2 self-end md:self-center shrink-0">
          <template v-if="res.status === 'pending'">
            <button
              @click="askApproval(res, 'approved')"
              class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition cursor-pointer"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
              Approve Booking
            </button>
            <button
              @click="askApproval(res, 'rejected')"
              class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition cursor-pointer"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
              Reject
            </button>
          </template>

          <template v-else-if="res.status === 'approved'">
            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200 flex items-center gap-1.5">
              <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
              Active Tenant Enrolled
            </span>
          </template>

          <template v-else>
            <button
              @click="askApproval(res, 'approved')"
              class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs transition cursor-pointer"
            >
              Re-approve
            </button>
          </template>
        </div>
      </div>
    </div>

    <!-- Empty state -->
    <div v-else class="text-center py-16 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
      <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3 shadow-2xs">
        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
      </div>
      <h3 class="text-base font-extrabold text-slate-900">No Reservations Found</h3>
      <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
        When students reserve rooms from your boarding house, their requests will appear here for your instant approval.
      </p>
    </div>

    <!-- Confirm Action Modal (Yes / Cancel) -->
    <ConfirmModal
      :show="showConfirmModal"
      :title="modalTitle"
      :message="modalMessage"
      :confirm-text="modalConfirmText"
      cancel-text="Cancel"
      :variant="modalVariant"
      :icon-type="modalIconType"
      @confirm="executeAction"
      @cancel="showConfirmModal = false"
      @update:show="showConfirmModal = $event"
    />
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';

const props = defineProps({
  house: Object,
  reservations: Object,
});

const reservationsList = computed(() => props.reservations?.data ?? props.reservations ?? []);
const statusFilter = ref('all');

const showConfirmModal = ref(false);
const activeRes = ref(null);
const pendingActionStatus = ref('');

const filteredReservations = computed(() => {
  if (statusFilter.value === 'all') return reservationsList.value;
  return reservationsList.value.filter(r => r.status === statusFilter.value);
});

const modalTitle = computed(() => {
  if (pendingActionStatus.value === 'approved') return 'Approve Student Reservation';
  if (pendingActionStatus.value === 'rejected') return 'Reject Student Reservation';
  return 'Cancel Reservation';
});

const modalMessage = computed(() => {
  const student = activeRes.value?.student?.name || 'this student';
  const room = activeRes.value?.room?.room_number || '';
  if (pendingActionStatus.value === 'approved') {
    return `Are you sure you want to approve the reservation for ${student} in Room ${room}? The room will be marked as Occupied and enrolled in your Tenants list.`;
  }
  if (pendingActionStatus.value === 'rejected') {
    return `Are you sure you want to decline ${student}'s reservation request?`;
  }
  return `Are you sure you want to cancel ${student}'s active reservation?`;
});

const modalConfirmText = computed(() => {
  if (pendingActionStatus.value === 'approved') return 'Yes, Approve Booking';
  if (pendingActionStatus.value === 'rejected') return 'Yes, Reject';
  return 'Yes, Cancel Reservation';
});

const modalVariant = computed(() => {
  if (pendingActionStatus.value === 'approved') return 'success';
  return 'danger';
});

const modalIconType = computed(() => {
  if (pendingActionStatus.value === 'approved') return 'success';
  return 'warning';
});

const statusColors = {
  pending: 'bg-amber-50 text-amber-700 border-amber-200',
  approved: 'bg-emerald-50 text-emerald-700 border-emerald-200',
  reserved: 'bg-indigo-50 text-indigo-700 border-indigo-200',
  rejected: 'bg-rose-50 text-rose-700 border-rose-200',
  cancelled: 'bg-slate-100 text-slate-600 border-slate-200',
};

function formatNumber(val) {
  return Number(val || 0).toLocaleString();
}

function askApproval(res, status) {
  activeRes.value = res;
  pendingActionStatus.value = status;
  showConfirmModal.value = true;
}

function executeAction() {
  if (!activeRes.value || !pendingActionStatus.value) return;
  router.patch(`/owner/reservations/${activeRes.value.id}`, { status: pendingActionStatus.value }, {
    onSuccess: () => {
      showConfirmModal.value = false;
      activeRes.value = null;
    },
  });
}
</script>
