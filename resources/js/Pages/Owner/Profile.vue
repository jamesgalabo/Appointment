<template>
  <AppLayout page-title="Property & House Profile">
    <div class="mb-6">
      <h1 class="text-2xl font-black text-slate-900 tracking-tight">Property Profile & Location</h1>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Upload photos from your gallery, set your exact address, and link your Google Maps pin</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Form -->
      <div class="lg:col-span-2 bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/80 shadow-xs">
        <form @submit.prevent="submit" class="space-y-5">
          <!-- House Details Section -->
          <div>
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
              <span>🏠</span> Boarding House Information
            </h3>

            <div class="space-y-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Boarding House Name *</label>
                <input
                  v-model="form.house_name"
                  type="text"
                  required
                  placeholder="e.g. Kost Oemah Kita Residence"
                  class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                />
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Description & House Rules</label>
                <textarea
                  v-model="form.house_description"
                  rows="3"
                  placeholder="Describe the environment, study hours, gate curfew, water supply, security..."
                  class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                ></textarea>
              </div>

              <!-- Property Amenities & Security Checklist -->
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Amenities & Security Features (Check all that apply)</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                  <label
                    v-for="amenity in availableAmenities"
                    :key="amenity.name"
                    class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200/80 hover:border-indigo-300 cursor-pointer transition text-xs font-medium text-slate-800"
                  >
                    <input
                      type="checkbox"
                      :value="amenity.name"
                      v-model="form.amenities"
                      class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer"
                    />
                    <span class="truncate">{{ amenity.icon }} {{ amenity.name }}</span>
                  </label>
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Barangay (Kidapawan City) *</label>
                  <select
                    v-model="form.barangay"
                    class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                  >
                    <option value="Poblacion">Poblacion</option>
                    <option value="Sudapin">Sudapin</option>
                    <option value="Manubuan">Manubuan</option>
                    <option value="Singao">Singao</option>
                    <option value="Balindog">Balindog</option>
                    <option value="Paco">Paco</option>
                    <option value="Mateo">Mateo</option>
                    <option value="Lanao">Lanao</option>
                    <option value="Amas">Amas</option>
                  </select>
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">House Contact Phone *</label>
                  <input
                    v-model="form.house_contact"
                    type="tel"
                    placeholder="0912 345 6789"
                    class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                  />
                </div>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Exact Street Address & Landmark *</label>
                <input
                  v-model="form.house_address"
                  type="text"
                  required
                  placeholder="e.g. Purok 4, Quezon Boulevard, near Kidapawan City Hall and USM"
                  class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                />
              </div>
            </div>
          </div>

          <!-- Cover Photo Upload (Device File Picker) -->
          <div class="pt-2">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
              <span>📷</span> Facade / Cover Photo
            </h3>

            <div class="space-y-3">
              <label class="flex flex-col items-center justify-center border-2 border-dashed border-slate-300 hover:border-indigo-500 rounded-2xl p-5 bg-slate-50/50 hover:bg-indigo-50/20 cursor-pointer transition">
                <svg class="w-8 h-8 text-indigo-500 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-xs font-bold text-slate-700">Choose cover photo from phone / laptop gallery</span>
                <span class="text-[10px] text-slate-400 mt-0.5">PNG, JPG up to 10MB</span>
                <input type="file" accept="image/*" class="hidden" @change="handleThumbnailChange" />
              </label>

              <!-- Image Preview -->
              <div v-if="previewThumbnail || form.thumbnail_url" class="relative h-44 w-full rounded-2xl overflow-hidden bg-slate-100 border border-slate-200">
                <img :src="previewThumbnail || form.thumbnail_url" class="w-full h-full object-cover" />
                <button
                  type="button"
                  @click="clearThumbnail"
                  class="absolute top-2 right-2 px-2.5 py-1 rounded-lg bg-slate-900/70 text-white text-[10px] font-bold hover:bg-rose-600 transition"
                >
                  Remove Photo
                </button>
              </div>
            </div>
          </div>

          <!-- Google Maps Location Section -->
          <div class="pt-2">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
              <span>📍</span> Google Maps Location Link
            </h3>

            <div class="space-y-3">
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Google Maps Link / URL</label>
                <div class="flex gap-2">
                  <input
                    v-model="form.map_url"
                    type="url"
                    placeholder="https://maps.app.goo.gl/... or https://maps.google.com/?q=..."
                    class="flex-1 px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                  />
                  <a
                    v-if="form.map_url"
                    :href="form.map_url"
                    target="_blank"
                    class="px-3.5 py-2.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center gap-1 shrink-0 transition"
                  >
                    <span>↗</span> Test Link
                  </a>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">
                  💡 How to get: Open Google Maps app, search your boarding house, tap <strong>Share</strong>, and paste the link here.
                </p>
              </div>
            </div>
          </div>

          <!-- Owner Personal Information -->
          <div class="pt-2">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
              <span>👤</span> Owner Personal Details
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Owner Name *</label>
                <input
                  v-model="form.name"
                  type="text"
                  required
                  class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Owner Mobile Phone</label>
                <input
                  v-model="form.phone"
                  type="tel"
                  placeholder="09XX XXX XXXX"
                  class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                />
              </div>
            </div>
          </div>

          <div class="pt-4 border-t border-slate-100">
            <button
              type="submit"
              :disabled="loading"
              class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition disabled:opacity-60 cursor-pointer"
            >
              {{ loading ? 'Saving Profile Changes...' : 'Save Profile & Location' }}
            </button>
          </div>
        </form>
      </div>

      <!-- Right Column: Live Listing Preview -->
      <div class="space-y-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
          <h3 class="font-extrabold text-slate-900 text-sm mb-3">Live Listing Preview</h3>
          <div class="rounded-xl overflow-hidden border border-slate-200 bg-slate-50">
            <div class="h-36 bg-slate-200 relative">
              <img
                :src="previewThumbnail || form.thumbnail_url || 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=600&q=80'"
                class="w-full h-full object-cover"
              />
              <span class="absolute top-2 right-2 bg-emerald-500 text-white px-2 py-0.5 rounded-full text-[10px] font-bold shadow-xs">
                ⭐ 4.8
              </span>
            </div>
            <div class="p-4">
              <h4 class="font-black text-slate-900 text-sm">{{ form.house_name || 'Your House Name' }}</h4>
              <p class="text-[11px] text-slate-500 mt-0.5">📍 {{ form.barangay || 'Poblacion' }}, Kidapawan City</p>
              <div v-if="form.map_url" class="mt-3">
                <a
                  :href="form.map_url"
                  target="_blank"
                  class="inline-flex items-center gap-1 text-[11px] font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-100 transition"
                >
                  <span>🗺️</span> Open Google Maps ↗
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
          <h3 class="font-extrabold text-slate-900 text-sm mb-2">Listing Verification</h3>
          <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs">
            <p class="font-bold">✓ Verified Property Listing</p>
            <p class="text-[11px] text-emerald-700 mt-1">Students across Kidapawan City can find your boarding house, inspect rooms, and book viewing appointments.</p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ user: Object, house: Object });
const loading = ref(false);
const previewThumbnail = ref(null);

const availableAmenities = [
  { name: '24/7 Security & CCTV', icon: '🛡️' },
  { name: 'Vendo Wi-Fi', icon: '📶' },
  { name: 'Air Conditioning', icon: '❄️' },
  { name: 'Water Refill Station', icon: '🚰' },
  { name: 'Shared Kitchen / Cooking Allowed', icon: '🍳' },
  { name: 'Gated Entrance & Curfew', icon: '🚪' },
  { name: 'Laundry & Drying Area', icon: '🧺' },
  { name: 'Backup Generator', icon: '⚡' },
  { name: 'Parking Space', icon: '🚗' },
  { name: 'Private Bathroom / En-Suite', icon: '🛁' },
  { name: 'No Curfew', icon: '⏰' },
];

const form = reactive({
  name: props.user?.name ?? '',
  phone: props.user?.phone ?? '',
  house_name: props.house?.name ?? '',
  house_description: props.house?.description ?? '',
  amenities: Array.isArray(props.house?.amenities) ? [...props.house.amenities] : [],
  house_address: props.house?.address ?? '',
  house_contact: props.house?.contact_number ?? '',
  barangay: props.house?.barangay ?? 'Poblacion',
  map_url: props.house?.map_url ?? '',
  thumbnail_url: props.house?.thumbnail_url ?? '',
  thumbnail_file: null,
});

function handleThumbnailChange(e) {
  const file = e.target.files[0];
  if (file) {
    form.thumbnail_file = file;
    previewThumbnail.value = URL.createObjectURL(file);
  }
}

function clearThumbnail() {
  form.thumbnail_file = null;
  form.thumbnail_url = '';
  previewThumbnail.value = null;
}

function submit() {
  loading.value = true;
  const formData = new FormData();
  formData.append('name', form.name);
  formData.append('phone', form.phone || '');
  formData.append('house_name', form.house_name);
  formData.append('house_description', form.house_description || '');
  formData.append('house_address', form.house_address);
  formData.append('house_contact', form.house_contact || '');
  formData.append('barangay', form.barangay);
  formData.append('map_url', form.map_url || '');

  (form.amenities || []).forEach(item => {
    formData.append('amenities[]', item);
  });

  if (form.thumbnail_file) {
    formData.append('thumbnail_file', form.thumbnail_file);
  } else if (form.thumbnail_url) {
    formData.append('thumbnail_url', form.thumbnail_url);
  }

  router.post('/owner/profile', formData, {
    onFinish: () => (loading.value = false),
  });
}
</script>
