<template>
  <AppLayout page-title="Manage Rooms">
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Manage Rooms & Units</h1>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Add room photos directly from your phone/laptop, set rental rates, and update vacancies</p>
      </div>

      <div class="flex items-center gap-2">
        <!-- Grid / List Switcher -->
        <div class="flex items-center p-1 bg-white border border-slate-200/80 rounded-xl shadow-2xs">
          <button
            @click="viewMode = 'grid'"
            :class="viewMode === 'grid' ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-500 hover:text-slate-800'"
            class="px-2.5 py-1.5 rounded-lg text-xs transition cursor-pointer flex items-center gap-1"
          >
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
            Grid
          </button>
          <button
            @click="viewMode = 'table'"
            :class="viewMode === 'table' ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-500 hover:text-slate-800'"
            class="px-2.5 py-1.5 rounded-lg text-xs transition cursor-pointer flex items-center gap-1"
          >
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
            Table
          </button>
        </div>

        <button
          @click="openModal()"
          class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition cursor-pointer"
        >
          <span>+</span> Add New Room
        </button>
      </div>
    </div>

    <!-- Grid View Mode -->
    <div v-if="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
      <div
        v-for="room in roomsList"
        :key="room.id"
        class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition flex flex-col justify-between group"
      >
        <!-- Room Photo -->
        <div class="relative h-48 w-full bg-slate-100 overflow-hidden">
          <img
            :src="room.photo_url || defaultRoomImage(room.room_type)"
            :alt="'Room ' + room.room_number"
            class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
            onerror="this.src='https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=600&q=80'"
          />
          <!-- Status Pill -->
          <div class="absolute top-3 left-3">
            <span
              :class="room.availability_status === 'available' ? 'bg-emerald-500 text-white' : room.availability_status === 'occupied' ? 'bg-rose-500 text-white' : 'bg-amber-500 text-white'"
              class="px-2.5 py-1 rounded-full text-[10px] font-bold shadow-xs capitalize"
            >
              ● {{ room.availability_status?.replace('_', ' ') }}
            </span>
          </div>

          <!-- Price Tag -->
          <div class="absolute bottom-3 right-3 bg-white/95 backdrop-blur-md px-3 py-1 rounded-xl shadow-xs border border-slate-200/50">
            <span class="text-xs font-black text-indigo-700">₱{{ formatNumber(room.monthly_rent) }}</span>
            <span class="text-[10px] text-slate-500">/mo</span>
          </div>
        </div>

        <!-- Room Details -->
        <div class="p-4 flex-1 flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-1">
              <h3 class="text-base font-extrabold text-slate-900">Room {{ room.room_number }}</h3>
              <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                {{ room.capacity }} pax
              </span>
            </div>
            <p class="text-xs font-semibold text-indigo-600 mb-2">{{ room.room_type }}</p>
            <p class="text-xs text-slate-500 line-clamp-2">{{ room.description || 'Standard room with bed and essential facilities.' }}</p>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center justify-between gap-2 pt-4 mt-3 border-t border-slate-100">
            <button
              @click="openModal(room)"
              class="flex-1 px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs transition text-center cursor-pointer flex items-center justify-center gap-1"
            >
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
              Edit Room
            </button>
            <button
              @click="confirmDelete(room)"
              class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs transition cursor-pointer"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Add New Card Placeholder -->
      <div
        @click="openModal()"
        class="border-2 border-dashed border-slate-200 hover:border-indigo-400 bg-slate-50/50 hover:bg-indigo-50/30 rounded-2xl p-8 flex flex-col items-center justify-center text-center cursor-pointer transition min-h-[280px]"
      >
        <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl font-bold mb-3 shadow-xs">
          +
        </div>
        <h4 class="font-extrabold text-slate-800 text-sm">Add Another Room</h4>
        <p class="text-xs text-slate-500 mt-1 max-w-[200px]">Upload a photo from your gallery and set monthly rent</p>
      </div>
    </div>

    <!-- Table View Mode -->
    <div v-else class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs mb-8">
      <div class="overflow-x-auto">
        <table class="w-full text-xs">
          <thead>
            <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider">
              <th class="px-5 py-3.5 text-left font-bold">Room</th>
              <th class="px-5 py-3.5 text-left font-bold">Type</th>
              <th class="px-5 py-3.5 text-center font-bold">Capacity</th>
              <th class="px-5 py-3.5 text-left font-bold">Monthly Rent</th>
              <th class="px-5 py-3.5 text-left font-bold">Status</th>
              <th class="px-5 py-3.5 text-right font-bold">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="room in roomsList" :key="room.id" class="hover:bg-slate-50/80 transition">
              <td class="px-5 py-3">
                <div class="flex items-center gap-3">
                  <img
                    :src="room.photo_url || defaultRoomImage(room.room_type)"
                    class="w-10 h-10 rounded-lg object-cover bg-slate-100 border border-slate-200/60"
                    onerror="this.src='https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=600&q=80'"
                  />
                  <div>
                    <span class="font-bold text-slate-900">Room {{ room.room_number }}</span>
                  </div>
                </div>
              </td>
              <td class="px-5 py-3 text-slate-700 font-semibold">{{ room.room_type }}</td>
              <td class="px-5 py-3 text-center text-slate-600 font-semibold">{{ room.capacity }} pax</td>
              <td class="px-5 py-3 font-bold text-indigo-700">₱{{ formatNumber(room.monthly_rent) }}<span class="text-slate-400 font-normal">/mo</span></td>
              <td class="px-5 py-3">
                <span
                  :class="room.availability_status === 'available' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : room.availability_status === 'occupied' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-amber-50 text-amber-700 border-amber-200'"
                  class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize"
                >
                  ● {{ room.availability_status?.replace('_', ' ') }}
                </span>
              </td>
              <td class="px-5 py-3 text-right">
                <div class="flex items-center justify-end gap-2">
                  <button @click="openModal(room)" class="px-3 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold transition cursor-pointer">Edit</button>
                  <button @click="confirmDelete(room)" class="px-3 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold transition cursor-pointer">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Form for Add/Edit Room (with File Upload from Laptop/Phone) -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
      <div class="bg-white rounded-3xl p-6 sm:p-7 max-w-lg w-full border border-slate-200 shadow-2xl animate-in fade-in duration-200 max-h-[90vh] overflow-y-auto">
        <div class="mb-4">
          <div>
            <h3 class="text-lg font-black text-slate-900">{{ editingRoom ? 'Edit Room ' + editingRoom.room_number : 'Add New Room' }}</h3>
            <p class="text-xs text-slate-500">Provide room photo from your device, capacity, and pricing</p>
          </div>
        </div>

        <form @submit.prevent="saveRoom" class="space-y-4">
          <!-- Room Photo Upload from Device (Laptop / Phone Gallery) -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Room Photo (Upload from Phone / Laptop)</label>
            <div class="flex flex-col gap-2">
              <label class="flex flex-col items-center justify-center border-2 border-dashed border-slate-300 hover:border-indigo-500 rounded-2xl p-4 bg-slate-50/50 hover:bg-indigo-50/20 cursor-pointer transition">
                <svg class="w-8 h-8 text-indigo-500 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-xs font-bold text-slate-700">Choose from gallery or browse files</span>
                <span class="text-[10px] text-slate-400 mt-0.5">PNG, JPG, WEBP up to 10MB</span>
                <input type="file" accept="image/*" class="hidden" @change="handleFileChange" />
              </label>

              <!-- Image Preview -->
              <div v-if="previewUrl || form.photo_url" class="relative h-36 w-full rounded-2xl overflow-hidden bg-slate-100 border border-slate-200">
                <img :src="previewUrl || form.photo_url" class="w-full h-full object-cover" />
                <button
                  type="button"
                  @click="clearImage"
                  class="absolute top-2 right-2 px-2 py-1 rounded-lg bg-slate-900/70 text-white text-[10px] font-bold hover:bg-rose-600 transition"
                >
                  Remove Photo
                </button>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Room Number *</label>
              <input v-model="form.room_number" type="text" required placeholder="e.g. 101, 2A" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Room Type *</label>
              <select v-model="form.room_type" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <option value="Single Room">Single Room</option>
                <option value="Duo Room">Duo Room</option>
                <option value="Bedspace">Bedspace</option>
                <option value="Studio Suite">Studio Suite</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Capacity (Pax) *</label>
              <input v-model="form.capacity" type="number" min="1" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Monthly Rent (₱) *</label>
              <input v-model="form.monthly_rent" type="number" step="50" min="0" required placeholder="2500" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Availability Status</label>
            <select v-model="form.availability_status" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
              <option value="available">Available (Open for booking)</option>
              <option value="occupied">Occupied</option>
              <option value="under_maintenance">Under Maintenance</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Description / Amenities Included</label>
            <textarea v-model="form.description" rows="2" placeholder="e.g. Free Wi-Fi, aircon, study table, private bathroom..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
          </div>

          <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
            <button type="button" @click="showModal = false" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">Cancel</button>
            <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-md shadow-indigo-600/20 cursor-pointer">Save Room</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Confirm Delete Modal (Yes / Cancel) -->
    <ConfirmModal
      :show="showDeleteModal"
      title="Delete Room"
      :message="`Are you sure you want to delete Room ${roomToDelete?.room_number}? This action cannot be undone.`"
      confirm-text="Yes, Delete Room"
      cancel-text="Cancel"
      variant="danger"
      icon-type="danger"
      @confirm="performDelete"
      @cancel="showDeleteModal = false"
      @update:show="showDeleteModal = $event"
    />
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';

const props = defineProps({ house: Object, rooms: Object });
const roomsList = computed(() => props.rooms?.data ?? props.rooms ?? []);

const viewMode = ref('grid');
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingRoom = ref(null);
const roomToDelete = ref(null);
const previewUrl = ref(null);

const form = reactive({
  room_number: '',
  room_type: 'Single Room',
  capacity: 1,
  monthly_rent: 2500,
  description: '',
  photo_url: '',
  photo_file: null,
  availability_status: 'available',
});

function defaultRoomImage(type) {
  if (type === 'Studio Suite') return 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=600&q=80';
  if (type === 'Duo Room') return 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=600&q=80';
  return 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=600&q=80';
}

function formatNumber(val) {
  return Number(val || 0).toLocaleString();
}

function handleFileChange(e) {
  const file = e.target.files[0];
  if (file) {
    form.photo_file = file;
    previewUrl.value = URL.createObjectURL(file);
  }
}

function clearImage() {
  form.photo_file = null;
  form.photo_url = '';
  previewUrl.value = null;
}

function openModal(room = null) {
  editingRoom.value = room;
  previewUrl.value = null;
  if (room) {
    form.room_number = room.room_number;
    form.room_type = room.room_type;
    form.capacity = room.capacity;
    form.monthly_rent = room.monthly_rent;
    form.description = room.description ?? '';
    form.photo_url = room.photo_url ?? '';
    form.photo_file = null;
    form.availability_status = room.availability_status;
  } else {
    form.room_number = '';
    form.room_type = 'Single Room';
    form.capacity = 1;
    form.monthly_rent = 2500;
    form.description = '';
    form.photo_url = '';
    form.photo_file = null;
    form.availability_status = 'available';
  }
  showModal.value = true;
}

function saveRoom() {
  const formData = new FormData();
  formData.append('room_number', form.room_number);
  formData.append('room_type', form.room_type);
  formData.append('capacity', form.capacity);
  formData.append('monthly_rent', form.monthly_rent);
  formData.append('description', form.description || '');
  formData.append('availability_status', form.availability_status);

  if (form.photo_file) {
    formData.append('photo_file', form.photo_file);
  } else if (form.photo_url) {
    formData.append('photo_url', form.photo_url);
  }

  if (editingRoom.value) {
    formData.append('_method', 'PATCH');
    router.post(`/owner/rooms/${editingRoom.value.id}`, formData, {
      onSuccess: () => (showModal.value = false),
    });
  } else {
    router.post('/owner/rooms', formData, {
      onSuccess: () => (showModal.value = false),
    });
  }
}

function confirmDelete(room) {
  roomToDelete.value = room;
  showDeleteModal.value = true;
}

function performDelete() {
  if (!roomToDelete.value) return;
  router.delete(`/owner/rooms/${roomToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
      roomToDelete.value = null;
    },
  });
}
</script>
