<template>
  <AppLayout page-title="Explore Boarding Houses">
    <!-- Hero Search Header (Image 2 & 3 inspired) -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 rounded-3xl p-6 sm:p-10 text-white shadow-md mb-8 relative overflow-hidden">
      <!-- Decorative background glow -->
      <div class="absolute top-0 right-0 -mt-8 -mr-8 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
      <div class="relative z-10 max-w-2xl">
        <span class="text-xs font-extrabold uppercase tracking-wider text-indigo-300 bg-white/10 backdrop-blur-md px-3 py-1 rounded-full inline-block mb-3">
          Kidapawan Student Living
        </span>
        <h1 class="text-2xl sm:text-4xl font-black tracking-tight leading-tight">
          Find your dream place to stay in Kidapawan City
        </h1>
        <p class="text-xs sm:text-sm text-indigo-100/80 mt-2 font-normal">
          Browse verified boarding houses, check real photos, compare monthly rates, and view exact Google Maps locations.
        </p>

        <!-- Search input form -->
        <form @submit.prevent="search" class="mt-6 flex flex-col sm:flex-row gap-2.5">
          <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <input
              v-model="searchForm.search"
              type="text"
              placeholder="Search by house name, street, or barangay (e.g. Poblacion, Sudapin)..."
              class="w-full pl-10 pr-4 py-3 rounded-2xl bg-white text-slate-900 text-xs sm:text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
            />
          </div>
          <button
            type="submit"
            class="px-6 py-3 rounded-2xl bg-indigo-500 hover:bg-indigo-400 text-white font-bold text-xs sm:text-sm shadow-md transition shrink-0"
          >
            Search Places
          </button>
        </form>
      </div>
    </div>

    <!-- Filter Quick Chips -->
    <div class="flex items-center justify-between gap-4 mb-6 flex-wrap">
      <div class="flex items-center gap-2 overflow-x-auto pb-1">
        <button
          v-for="b in ['All Kidapawan Areas', 'Poblacion', 'Sudapin', 'Manubuan', 'Singao', 'Balindog']"
          :key="b"
          @click="filterBarangay(b)"
          :class="activeFilter === b ? 'bg-indigo-600 text-white font-bold shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-50 border border-slate-200/80 font-medium'"
          class="px-3.5 py-1.5 rounded-xl text-xs transition shrink-0"
        >
          {{ b }}
        </button>
      </div>

      <span class="text-xs font-bold text-slate-500">
        {{ houseList.length }} Verified Houses Found
      </span>
    </div>

    <!-- House Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" v-if="houseList.length">
      <div
        v-for="house in houseList"
        :key="house.id"
        class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-lg transition-all duration-300 flex flex-col group"
      >
        <!-- House Photo Cover -->
        <div class="relative h-52 bg-slate-100 overflow-hidden">
          <img
            :src="house.thumbnail_url ?? 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&q=80&w=800'"
            :alt="house.name"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
          />
          <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-white/95 backdrop-blur-md text-xs font-bold text-amber-600 flex items-center gap-1 border border-slate-200 shadow-2xs">
            <svg class="w-3.5 h-3.5 fill-amber-500" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            {{ house.rating ?? '4.8' }}
          </div>
          <div class="absolute bottom-3 left-3 px-2.5 py-1 rounded-lg bg-emerald-600 text-[10px] font-bold text-white uppercase tracking-wider shadow-2xs">
            {{ house.available_rooms_count ?? house.rooms_count ?? 0 }} Rooms Available
          </div>
        </div>

        <!-- House Info -->
        <div class="p-5 flex-1 flex flex-col justify-between">
          <div>
            <div class="flex items-start justify-between gap-2 mb-1">
              <h3 class="font-extrabold text-base text-slate-900 group-hover:text-indigo-600 transition">{{ house.name }}</h3>
            </div>
            <p class="text-xs text-slate-500 mb-3 flex items-center gap-1.5">
              <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
              <span>{{ house.barangay }}, {{ house.city ?? 'Kidapawan City' }}</span>
            </p>
            <p class="text-xs text-slate-600 line-clamp-2 mb-4 leading-relaxed font-normal">
              {{ house.description || 'Clean, comfortable, and safe boarding house accommodation near universities and central downtown.' }}
            </p>
          </div>

          <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
            <div>
              <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Monthly Rates</span>
              <span class="text-indigo-600 font-black text-sm">From ₱2,000<span class="text-xs text-slate-400 font-normal">/mo</span></span>
            </div>
            <Link
              :href="`/student/houses/${house.id}`"
              class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition"
            >
              View House →
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-xs">
      <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4 shadow-2xs">
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
      </div>
      <h3 class="text-base font-extrabold text-slate-900 mb-1">No Boarding Houses Found</h3>
      <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4 font-medium">
        Try searching with a different keyword or reset your filter to view all verified boarding houses.
      </p>
      <button
        @click="resetSearch"
        class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold text-xs shadow-xs"
      >
        Clear Filters
      </button>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ houses: Object, filters: Object });
const houseList = computed(() => props.houses?.data ?? props.houses ?? []);

const activeFilter = ref('All Kidapawan Areas');
const searchForm = reactive({
  search: props.filters?.search ?? '',
});

function search() {
  router.get('/student/explore', searchForm, { preserveState: true });
}

function filterBarangay(b) {
  activeFilter.value = b;
  if (b === 'All Kidapawan Areas') {
    searchForm.search = '';
  } else {
    searchForm.search = b;
  }
  search();
}

function resetSearch() {
  activeFilter.value = 'All Kidapawan Areas';
  searchForm.search = '';
  search();
}
</script>
