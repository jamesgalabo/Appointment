<template>
  <AppLayout page-title="My Appointments">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">My Viewing Appointments</h1>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Track and manage your scheduled in-person boarding house inspections (Free tours)</p>
      </div>
      <Link
        href="/student/explore"
        class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition cursor-pointer"
      >
        <span>+</span> Book New Viewing
      </Link>
    </div>

    <!-- Appointments Cards -->
    <div v-if="appointmentList.length > 0" class="space-y-4">
      <div
        v-for="apt in appointmentList"
        :key="apt.id"
        class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4"
      >
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
            <img
              :src="apt.boarding_house?.thumbnail_url ?? 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&q=80&w=200'"
              class="w-full h-full object-cover"
            />
          </div>
          <div>
            <div class="flex items-center gap-2 mb-1 flex-wrap">
              <h3 class="text-base font-extrabold text-slate-900">{{ apt.boarding_house?.name }}</h3>
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
                Date: <strong class="text-slate-800">{{ apt.scheduled_date }}</strong>
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Time: {{ apt.time_slot }}
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                {{ apt.boarding_house?.address }}
              </span>
            </div>

            <p v-if="apt.notes" class="text-xs text-slate-500 italic mt-2 bg-slate-50 px-3 py-1 rounded-lg border border-slate-100 max-w-xl">
              "{{ apt.notes }}"
            </p>

            <div v-if="apt.cancellation_reason" class="mt-2.5 px-3 py-2 rounded-xl bg-rose-50 border border-rose-200/80 text-rose-800 text-xs font-medium max-w-xl">
              <span class="font-extrabold block text-[11px] text-rose-900 uppercase tracking-wider mb-0.5">Notice / Reason:</span>
              "{{ apt.cancellation_reason }}"
            </div>
          </div>
        </div>

        <div class="text-right self-end md:self-center shrink-0 flex items-center gap-2">
          <div v-if="apt.status === 'approved'" class="px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-800 font-bold text-xs border border-emerald-200 text-center flex items-center gap-1.5">
            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            Confirmed with Landlord
          </div>
          <div v-else-if="apt.status === 'pending'" class="px-3.5 py-2 rounded-xl bg-amber-50 text-amber-800 font-bold text-xs border border-amber-200 text-center flex items-center gap-1.5">
            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Pending Confirmation
          </div>
          <div v-else class="text-xs text-slate-400 font-medium capitalize">
            Status: {{ apt.status }}
          </div>

          <button
            type="button"
            @click="messageHost(apt)"
            class="px-3.5 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-bold transition flex items-center gap-1 cursor-pointer"
          >
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
            Message Host
          </button>

          <button
            v-if="apt.status === 'pending' || apt.status === 'approved'"
            @click="askCancelAppointment(apt)"
            class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition cursor-pointer"
          >
            Cancel Visit
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-16 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
      <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3 shadow-2xs">
        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
      </div>
      <h3 class="text-base font-extrabold text-slate-900">No Appointments Booked</h3>
      <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto mb-4">
        Browse verified boarding houses and book a free in-person tour before making a decision.
      </p>
      <Link href="/student/explore" class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold text-xs shadow-xs">
        Find Boarding Houses
      </Link>
    </div>

    <!-- Confirm Cancel Modal -->
    <ConfirmModal
      :show="showCancelModal"
      title="Cancel Viewing Appointment"
      :message="`Are you sure you want to cancel your scheduled property visit at ${aptToCancel?.boarding_house?.name || 'this boarding house'}?`"
      confirm-text="Yes, Cancel Visit"
      cancel-text="Keep Appointment"
      variant="danger"
      icon-type="warning"
      @confirm="performCancelAppointment"
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

const props = defineProps({ appointments: Object });
const appointmentList = computed(() => props.appointments?.data ?? props.appointments ?? []);

const showCancelModal = ref(false);
const aptToCancel = ref(null);

function messageHost(apt) {
  const ownerId = apt.boarding_house?.owner_id;
  if (ownerId && typeof window !== 'undefined') {
    window.dispatchEvent(new CustomEvent('open-chat', {
      detail: {
        recipientId: ownerId,
        houseId: apt.boarding_house_id,
        initialMessage: `Hi! Inquiring regarding my viewing visit on ${apt.scheduled_date} (${apt.time_slot}) for ${apt.boarding_house?.name}.`,
      },
    }));
  }
}

function askCancelAppointment(apt) {
  aptToCancel.value = apt;
  showCancelModal.value = true;
}

function performCancelAppointment() {
  if (!aptToCancel.value) return;
  router.patch(`/student/appointments/${aptToCancel.value.id}/cancel`, {}, {
    onSuccess: () => {
      showCancelModal.value = false;
      aptToCancel.value = null;
    },
  });
}

const statusColors = {
  pending: 'bg-amber-50 text-amber-700 border-amber-200',
  approved: 'bg-emerald-50 text-emerald-700 border-emerald-200',
  rejected: 'bg-rose-50 text-rose-700 border-rose-200',
  rescheduled: 'bg-indigo-50 text-indigo-700 border-indigo-200',
  completed: 'bg-slate-100 text-slate-700 border-slate-200',
};
</script>
