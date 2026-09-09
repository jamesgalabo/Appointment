<template>
  <AppLayout page-title="My Reservations">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">My Room Reservations</h1>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Track your room reservation request status, payments, and move-in details</p>
      </div>
      <Link
        href="/student/explore"
        class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition cursor-pointer"
      >
        <span>+</span> Reserve Another Room
      </Link>
    </div>

    <!-- Reservations Card List -->
    <div v-if="reservationList.length > 0" class="space-y-4">
      <div
        v-for="res in reservationList"
        :key="res.id"
        class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-5"
      >
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <div>
            <div class="flex items-center gap-2 mb-1 flex-wrap">
              <h3 class="text-base font-extrabold text-slate-900">{{ res.room?.boarding_house?.name ?? 'Boarding House' }}</h3>
              <span
                :class="statusColors[res.status] || 'bg-slate-100 text-slate-700'"
                class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize flex items-center gap-1"
              >
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span> {{ res.status }}
              </span>
              <span class="px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-bold flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                {{ res.payment_method || 'Cash' }}
              </span>
            </div>

            <div class="flex items-center gap-4 text-xs text-slate-600 font-medium flex-wrap mt-1">
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <strong>Room {{ res.room?.room_number }}</strong>
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Monthly: <strong>₱{{ formatNumber(res.room?.monthly_rent) }}</strong>
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                Move-in: <strong class="text-slate-800">{{ formatDate(res.intended_move_in_date) }}</strong>
              </span>
            </div>

            <div class="flex items-center gap-3 text-xs text-slate-500 mt-2 flex-wrap">
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                {{ res.room?.boarding_house?.address || 'Kidapawan City' }}
              </span>
              <span class="font-mono text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded text-[11px] font-bold">
                Ref: #{{ res.qr_reference }}
              </span>
              <a
                v-if="res.payment_receipt_url"
                :href="res.payment_receipt_url"
                target="_blank"
                class="text-indigo-600 font-bold underline text-[11px] flex items-center gap-1 hover:text-indigo-800"
              >
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                View Uploaded Receipt
              </a>
            </div>

            <div v-if="res.cancellation_reason" class="mt-2.5 px-3 py-2 rounded-xl bg-rose-50 border border-rose-200/80 text-rose-800 text-xs font-medium max-w-xl">
              <span class="font-extrabold block text-[11px] text-rose-900 uppercase tracking-wider mb-0.5">Notice / Reason:</span>
              "{{ res.cancellation_reason }}"
            </div>
          </div>
        </div>

        <div class="text-right self-end md:self-center shrink-0 flex items-center gap-2">
          <div v-if="res.status === 'approved'" class="px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-800 font-bold text-xs border border-emerald-200 text-center flex items-center gap-1.5">
            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            Reservation Approved
          </div>
          <div v-else-if="res.status === 'pending'" class="px-3.5 py-2 rounded-xl bg-amber-50 text-amber-800 font-bold text-xs border border-amber-200 text-center flex items-center gap-1.5">
            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Pending Owner Review
          </div>
          <div v-else class="text-xs text-slate-400 font-medium capitalize">
            Status: {{ res.status }}
          </div>

          <button
            type="button"
            @click="messageHost(res)"
            class="px-3.5 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-bold transition flex items-center gap-1 cursor-pointer"
          >
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
            Message Host
          </button>

          <!-- Cancel Reservation Button -->
          <button
            v-if="res.status === 'pending' || res.status === 'approved'"
            @click="askCancel(res)"
            class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition cursor-pointer"
          >
            Cancel Booking
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-16 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
      <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3 shadow-2xs">
        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
      </div>
      <h3 class="text-base font-extrabold text-slate-900">No Reservations Yet</h3>
      <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto mb-4">
        Explore verified boarding houses and reserve your preferred room slot today.
      </p>
      <Link href="/student/explore" class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold text-xs shadow-xs">
        Explore Available Houses
      </Link>
    </div>

    <!-- Confirm Cancel Modal -->
    <ConfirmModal
      :show="showCancelModal"
      title="Cancel Room Reservation"
      :message="`Are you sure you want to cancel your reservation for Room ${resToCancel?.room?.room_number || ''} at ${resToCancel?.room?.boarding_house?.name || 'this house'}?`"
      confirm-text="Yes, Cancel Booking"
      cancel-text="Keep Reservation"
      variant="danger"
      icon-type="warning"
      @confirm="performCancel"
      @cancel="showCancelModal = false"
      @update:show="showCancelModal = $event"
    />
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';

const props = defineProps({ reservations: Object });
const reservationList = computed(() => props.reservations?.data ?? props.reservations ?? []);

const showCancelModal = ref(false);
const resToCancel = ref(null);

function formatNumber(val) {
  return Number(val || 0).toLocaleString();
}

function formatDate(iso) {
  if (!iso) return 'Not set';
  try {
    const d = new Date(iso);
    return isNaN(d) ? iso : d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
  } catch {
    return iso;
  }
}

function messageHost(res) {
  const ownerId = res.room?.boarding_house?.owner_id;
  if (ownerId && typeof window !== 'undefined') {
    window.dispatchEvent(new CustomEvent('open-chat', {
      detail: {
        recipientId: ownerId,
        houseId: res.room?.boarding_house_id,
        initialMessage: `Hi! Inquiring regarding my reservation (Ref #${res.qr_reference}) for Room ${res.room?.room_number} at ${res.room?.boarding_house?.name}.`,
      },
    }));
  }
}

function askCancel(res) {
  resToCancel.value = res;
  showCancelModal.value = true;
}

function performCancel() {
  if (!resToCancel.value) return;
  router.patch(`/student/reservations/${resToCancel.value.id}/cancel`, {}, {
    onSuccess: () => {
      showCancelModal.value = false;
      resToCancel.value = null;
    },
  });
}

const statusColors = {
  pending: 'bg-amber-50 text-amber-700 border-amber-200',
  approved: 'bg-emerald-50 text-emerald-700 border-emerald-200',
  rejected: 'bg-rose-50 text-rose-700 border-rose-200',
  reserved: 'bg-indigo-50 text-indigo-700 border-indigo-200',
  occupied: 'bg-purple-50 text-purple-700 border-purple-200',
  cancelled: 'bg-slate-100 text-slate-700 border-slate-200',
};
</script>
