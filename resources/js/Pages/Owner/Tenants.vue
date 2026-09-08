<template>
  <AppLayout page-title="Current Tenants">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Current Occupants & Tenants</h1>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Manage enrolled student tenants staying at {{ house?.name ?? 'your boarding house' }}</p>
      </div>

      <div class="flex items-center gap-2">
        <span class="px-3 py-1.5 rounded-xl bg-white border border-slate-200/80 text-xs font-bold text-slate-700 shadow-2xs">
          {{ tenantList.length }} Active Occupants
        </span>
        <button
          @click="showAddModal = true"
          class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition cursor-pointer"
        >
          <span>+</span> Register Walk-in Tenant
        </button>
      </div>
    </div>

    <!-- Tenants Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs">
      <div class="overflow-x-auto">
        <table class="w-full text-xs">
          <thead>
            <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider">
              <th class="px-5 py-3.5 text-left font-bold">Tenant Name</th>
              <th class="px-5 py-3.5 text-left font-bold">Contact Info</th>
              <th class="px-5 py-3.5 text-left font-bold">Assigned Room</th>
              <th class="px-5 py-3.5 text-left font-bold">Monthly Rent</th>
              <th class="px-5 py-3.5 text-left font-bold">Move-in Date</th>
              <th class="px-5 py-3.5 text-left font-bold">Status</th>
              <th class="px-5 py-3.5 text-right font-bold">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="tenant in tenantList" :key="tenant.id" class="hover:bg-slate-50/80 transition">
              <td class="px-5 py-3.5">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-2xs">
                    {{ (tenant.tenant_name || tenant.student?.name || 'T').charAt(0).toUpperCase() }}
                  </div>
                  <div>
                    <div class="font-bold text-slate-900">{{ tenant.tenant_name || tenant.student?.name || 'Student Tenant' }}</div>
                    <div class="text-[10px] text-slate-400 font-mono">Tenant #{{ tenant.id }}</div>
                  </div>
                </div>
              </td>
              <td class="px-5 py-3.5">
                <div class="text-slate-800 font-medium">{{ tenant.tenant_email || tenant.student?.email || '—' }}</div>
                <div class="text-slate-500 text-[11px]">{{ tenant.tenant_phone || tenant.student?.phone || '—' }}</div>
              </td>
              <td class="px-5 py-3.5 font-bold text-slate-900">
                Room {{ tenant.room?.room_number ?? '—' }}
                <span class="text-[11px] text-slate-400 font-normal block">{{ tenant.room?.room_type }}</span>
              </td>
              <td class="px-5 py-3.5 font-bold text-indigo-700">
                ₱{{ formatNumber(tenant.monthly_rate || tenant.room?.monthly_rent) }}<span class="text-[10px] text-slate-400 font-normal">/mo</span>
              </td>
              <td class="px-5 py-3.5 text-slate-600 font-medium">{{ tenant.start_date ?? '—' }}</td>
              <td class="px-5 py-3.5">
                <span
                  :class="tenant.status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200'"
                  class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize"
                >
                  ● {{ tenant.status }}
                </span>
              </td>
              <td class="px-5 py-3.5 text-right">
                <button
                  @click="confirmRemoveTenant(tenant)"
                  class="px-3 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold border border-rose-200 transition cursor-pointer"
                >
                  End Tenancy
                </button>
              </td>
            </tr>
            <tr v-if="!tenantList.length">
              <td colspan="7" class="px-5 py-14 text-center text-slate-500 font-medium">
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center mx-auto mb-2">
                  <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <p class="font-bold text-slate-800">No active tenants yet</p>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                  When you approve online student reservations, or register a walk-in tenant, they will appear here.
                </p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Add Walk-in Tenant Modal -->
    <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
      <div class="bg-white rounded-3xl p-6 sm:p-7 max-w-md w-full border border-slate-200 shadow-2xl animate-in fade-in duration-150">
        <div class="mb-4">
          <div>
            <h3 class="text-lg font-black text-slate-900">Register Walk-in Tenant</h3>
            <p class="text-xs text-slate-500">Add a student occupant to your boarding house</p>
          </div>
        </div>

        <form @submit.prevent="saveTenant" class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Select Room *</label>
            <select
              v-model="addForm.room_id"
              required
              class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
            >
              <option value="" disabled>Choose a room...</option>
              <option v-for="r in rooms" :key="r.id" :value="r.id">
                Room {{ r.room_number }} ({{ r.room_type }}) — ₱{{ formatNumber(r.monthly_rent) }}/mo
              </option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Tenant Full Name *</label>
            <input
              v-model="addForm.tenant_name"
              type="text"
              required
              placeholder="e.g. Maria Santos"
              class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
            />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Mobile Phone *</label>
              <input
                v-model="addForm.tenant_phone"
                type="tel"
                required
                placeholder="0912 345 6789"
                class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
              <input
                v-model="addForm.tenant_email"
                type="email"
                placeholder="student@example.com"
                class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
              />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Move-in Date *</label>
              <input
                v-model="addForm.start_date"
                type="date"
                required
                class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Monthly Rate (₱) *</label>
              <input
                v-model="addForm.monthly_rate"
                type="number"
                required
                placeholder="2500"
                class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
              />
            </div>
          </div>

          <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
            <button type="button" @click="showAddModal = false" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">Cancel</button>
            <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-md shadow-indigo-600/20 cursor-pointer">Register Tenant</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Confirm End Tenancy Modal (Yes / Cancel) -->
    <ConfirmModal
      :show="showRemoveModal"
      title="End Tenancy Confirmation"
      :message="`Are you sure you want to end the tenancy for ${tenantToRemove?.tenant_name || tenantToRemove?.student?.name || 'this tenant'}? The room will be set to Available.`"
      confirm-text="Yes, End Tenancy"
      cancel-text="Cancel"
      variant="danger"
      icon-type="danger"
      @confirm="performRemoveTenant"
      @cancel="showRemoveModal = false"
      @update:show="showRemoveModal = $event"
    />
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';

const props = defineProps({
  house: Object,
  tenants: Object,
  rooms: Array,
});

const tenantList = computed(() => props.tenants?.data ?? props.tenants ?? []);

const showAddModal = ref(false);
const showRemoveModal = ref(false);
const tenantToRemove = ref(null);

const addForm = reactive({
  room_id: '',
  tenant_name: '',
  tenant_phone: '',
  tenant_email: '',
  start_date: new Date().toISOString().split('T')[0],
  monthly_rate: 2500,
});

watch(() => addForm.room_id, (newRoomId) => {
  if (newRoomId && props.rooms) {
    const selected = props.rooms.find(r => r.id === newRoomId);
    if (selected) {
      addForm.monthly_rate = selected.monthly_rent;
    }
  }
});

function formatNumber(val) {
  return Number(val || 0).toLocaleString();
}

function saveTenant() {
  router.post('/owner/tenants', addForm, {
    onSuccess: () => {
      showAddModal.value = false;
      addForm.room_id = '';
      addForm.tenant_name = '';
      addForm.tenant_phone = '';
      addForm.tenant_email = '';
    },
  });
}

function confirmRemoveTenant(tenant) {
  tenantToRemove.value = tenant;
  showRemoveModal.value = true;
}

function performRemoveTenant() {
  if (!tenantToRemove.value) return;
  router.delete(`/owner/tenants/${tenantToRemove.value.id}`, {
    onSuccess: () => {
      showRemoveModal.value = false;
      tenantToRemove.value = null;
    },
  });
}
</script>
