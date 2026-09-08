<template>
  <AppLayout page-title="Boarding Houses">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Boarding Houses Directory</h1>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Manage partner boarding properties, review new registrations, and inspect rooms</p>
      </div>

      <div class="flex items-center gap-2">
        <span class="px-3 py-1.5 rounded-xl bg-white border border-slate-200/80 text-xs font-bold text-slate-700 shadow-2xs">
          {{ housesList.length }} Total Properties
        </span>
      </div>
    </div>

    <!-- Houses Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs">
      <div class="overflow-x-auto">
        <table class="w-full text-xs">
          <thead>
            <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider">
              <th class="px-5 py-3.5 text-left font-bold">Property Name</th>
              <th class="px-5 py-3.5 text-left font-bold">Owner / Contact</th>
              <th class="px-5 py-3.5 text-left font-bold">Barangay</th>
              <th class="px-5 py-3.5 text-center font-bold">Total Rooms</th>
              <th class="px-5 py-3.5 text-left font-bold">Rating</th>
              <th class="px-5 py-3.5 text-left font-bold">Status</th>
              <th class="px-5 py-3.5 text-right font-bold">Approval Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="house in housesList" :key="house.id" class="hover:bg-slate-50/80 transition">
              <td class="px-5 py-3.5">
                <div class="flex items-center gap-3">
                  <img
                    :src="house.thumbnail_url ?? 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&q=80&w=200'"
                    class="w-10 h-10 rounded-xl object-cover bg-slate-100 border border-slate-200/60"
                  />
                  <div>
                    <span class="font-bold text-slate-900 text-xs">{{ house.name }}</span>
                    <p class="text-[11px] text-slate-400 truncate max-w-xs">{{ house.address }}</p>
                  </div>
                </div>
              </td>
              <td class="px-5 py-3.5">
                <div class="font-semibold text-slate-800">{{ house.owner?.name ?? '—' }}</div>
                <div class="text-[11px] text-slate-400">{{ house.contact_number || 'No contact' }}</div>
              </td>
              <td class="px-5 py-3.5 text-slate-600 font-medium">{{ house.barangay }}, {{ house.city ?? 'Kidapawan' }}</td>
              <td class="px-5 py-3.5 text-center font-bold text-slate-800">{{ house.rooms_count }} units</td>
              <td class="px-5 py-3.5 text-amber-600 font-bold">⭐ {{ house.rating }}</td>
              <td class="px-5 py-3.5">
                <span
                  :class="house.status === 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : house.status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-rose-50 text-rose-700 border-rose-200'"
                  class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize"
                >
                  ● {{ house.status }}
                </span>
              </td>
              <td class="px-5 py-3.5 text-right">
                <div class="flex items-center justify-end gap-2" v-if="house.status === 'pending'">
                  <button @click="askHouseApproval(house, 'approved')" class="px-3 py-1 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold transition cursor-pointer">
                    ✓ Approve
                  </button>
                  <button @click="askHouseApproval(house, 'rejected')" class="px-3 py-1 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold transition cursor-pointer">
                    ✕ Reject
                  </button>
                </div>
                <span v-else class="text-xs font-semibold text-slate-400">Verified</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Confirm Action Modal -->
    <ConfirmModal
      :show="showConfirmModal"
      :title="modalTitle"
      :message="modalMessage"
      :confirm-text="modalConfirmText"
      cancel-text="Cancel"
      :variant="modalVariant"
      :icon="modalIcon"
      @confirm="executeHouseAction"
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

const props = defineProps({ houses: Object });
const housesList = computed(() => props.houses?.data ?? props.houses ?? []);

const showConfirmModal = ref(false);
const activeHouse = ref(null);
const actionType = ref('');

const modalTitle = computed(() => {
  return actionType.value === 'approved' ? 'Approve Boarding House' : 'Reject Boarding House';
});

const modalMessage = computed(() => {
  const name = activeHouse.value?.name || 'this property';
  return actionType.value === 'approved'
    ? `Are you sure you want to approve "${name}" to be visible in the public search directory?`
    : `Are you sure you want to decline registration for "${name}"?`;
});

const modalConfirmText = computed(() => {
  return actionType.value === 'approved' ? 'Yes, Approve Property' : 'Yes, Reject Property';
});

const modalVariant = computed(() => {
  return actionType.value === 'approved' ? 'success' : 'danger';
});

const modalIcon = computed(() => {
  return actionType.value === 'approved' ? '✓' : '⚠️';
});

function askHouseApproval(house, action) {
  activeHouse.value = house;
  actionType.value = action;
  showConfirmModal.value = true;
}

function executeHouseAction() {
  if (!activeHouse.value || !actionType.value) return;
  if (actionType.value === 'approved') {
    router.patch(`/admin/houses/${activeHouse.value.id}/approve`, {}, {
      onSuccess: () => {
        showConfirmModal.value = false;
        activeHouse.value = null;
      },
    });
  } else {
    router.patch(`/admin/houses/${activeHouse.value.id}/reject`, {}, {
      onSuccess: () => {
        showConfirmModal.value = false;
        activeHouse.value = null;
      },
    });
  }
}
</script>
