<template>
  <MainLayout>
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-white py-16 sm:py-24 border-b border-slate-200">
      <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[400px] bg-indigo-100/50 rounded-full blur-[100px] pointer-events-none"></div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-10">
          <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-bold uppercase tracking-wider mb-6 shadow-2xs">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Kidapawan City Student Accommodation Portal
          </div>
          <h1 class="text-4xl sm:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight mb-6">
            Find & Reserve Your Ideal <span class="text-indigo-600">Boarding House</span> in Kidapawan
          </h1>
          <p class="text-base sm:text-lg text-slate-600 leading-relaxed font-normal">
            The smart, centralized platform for students to explore verified dormitories, schedule viewing appointments, reserve rooms online, and connect with property owners.
          </p>
        </div>

        <!-- Search Card -->
        <div class="max-w-4xl mx-auto bg-white rounded-2xl p-4 sm:p-6 shadow-xl shadow-slate-200/60 border border-slate-200">
          <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="space-y-1 sm:border-r border-slate-200 sm:pr-4">
              <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Barangay / Location</label>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="e.g. Poblacion, Kidapawan"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
              />
            </div>
            <div class="space-y-1 sm:border-r border-slate-200 sm:pr-4">
              <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Room Type</label>
              <select
                v-model="selectedType"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
              >
                <option value="">All Room Types</option>
                <option value="Single">Single Room</option>
                <option value="Duo">Duo / Double</option>
                <option value="Studio">Studio Suite</option>
              </select>
            </div>
            <div class="space-y-1 sm:border-r border-slate-200 sm:pr-4">
              <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Budget Max</label>
              <select
                v-model="selectedPrice"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
              >
                <option value="999999">Any Budget</option>
                <option value="2000">Up to ₱2,000 / mo</option>
                <option value="3000">Up to ₱3,000 / mo</option>
                <option value="4000">Up to ₱4,000 / mo</option>
              </select>
            </div>
            <div class="flex items-end">
              <a
                href="/login"
                class="w-full py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-500/20 flex items-center justify-center gap-2 transition"
              >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span>Search Places</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Process Flow Visualization Section -->
    <div class="bg-slate-50 py-16 border-b border-slate-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">System Process Flow</h2>
          <p class="text-sm text-slate-600 mt-2">How KidaBoard simplifies boarding house booking for students & owners</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
          <div v-for="(step, idx) in processSteps" :key="idx" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs hover:shadow-md transition">
            <div class="flex items-center gap-3 mb-3">
              <span class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-700 font-extrabold text-xs flex items-center justify-center">
                0{{ idx + 1 }}
              </span>
              <h3 class="font-bold text-sm text-slate-900">{{ step.title }}</h3>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed">{{ step.desc }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Featured Listings Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8">
        <div>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Featured Boarding Houses</h2>
          <p class="text-sm text-slate-600 mt-1">Verified student accommodations in Kidapawan City</p>
        </div>
        <a href="/login" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-700 mt-4 sm:mt-0">
          <span>View All Listings</span>
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </a>
      </div>

      <!-- Listing Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="house in filteredHouses"
          :key="house.id"
          class="rounded-2xl overflow-hidden bg-white border border-slate-200 group hover:border-indigo-300 hover:shadow-lg transition-all duration-300 flex flex-col"
        >
          <div class="relative h-52 overflow-hidden bg-slate-100">
            <img :src="house.image" :alt="house.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-white/90 backdrop-blur-md text-xs font-bold text-amber-600 flex items-center gap-1 border border-slate-200 shadow-2xs">
              <span>★</span> {{ house.rating }}
            </div>
            <div class="absolute bottom-3 left-3 px-2.5 py-1 rounded-lg bg-emerald-600 text-[11px] font-bold text-white uppercase tracking-wider shadow-2xs">
              {{ house.badge }}
            </div>
          </div>
          <div class="p-5 flex-1 flex flex-col justify-between">
            <div>
              <div class="flex items-center justify-between mb-1">
                <h3 class="font-bold text-base text-slate-900 group-hover:text-indigo-600 transition truncate">{{ house.name }}</h3>
                <span class="text-indigo-600 font-extrabold text-sm shrink-0 ml-2">₱{{ house.price.toLocaleString() }}<span class="text-xs text-slate-400 font-normal">/mo</span></span>
              </div>
              <p class="text-xs text-slate-500 mb-3 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                </svg>
                <span class="truncate">{{ house.location }}</span>
              </p>
              <div class="flex items-center gap-1.5 text-[11px] text-slate-600 py-3 border-y border-slate-100 mb-4 flex-wrap">
                <span v-for="amenity in house.amenities" :key="amenity" class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-medium">
                  {{ amenity }}
                </span>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <a
                href="/login"
                class="flex-1 text-center py-2.5 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-xs"
              >
                Book Viewing
              </a>
              <a
                href="/login"
                class="py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition"
              >
                Reserve
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';

const searchQuery = ref('');
const selectedType = ref('');
const selectedPrice = ref('999999');

const processSteps = [
  { title: 'Register & Login', desc: 'Students create an account to access the search portal and booking system.' },
  { title: 'Search & Filter', desc: 'Filter houses by location, price, amenities, room type, and live availability.' },
  { title: 'View House Details', desc: 'Examine room photos, house rules, amenities, and Google Maps directions.' },
  { title: 'Book Appointment', desc: 'Select preferred date and time to request an in-person room inspection.' },
  { title: 'Owner Approval', desc: 'Owner reviews and approves, rejects, or reschedules the appointment request.' },
  { title: 'In-Person Visit', desc: 'Student visits the boarding house on the approved schedule to check facilities.' },
  { title: 'Reserve Room', desc: 'Student selects a specific room and submits an online reservation request.' },
  { title: 'Occupancy Update', desc: 'Upon move-in, owner approves reservation and marks room status as Occupied.' },
];

const houses = ref([
  {
    id: 1,
    name: 'Kost Oemah Kita',
    price: 2200,
    type: 'Single',
    location: 'Poblacion, Kidapawan City (Near NVC)',
    rating: 4.8,
    badge: 'Available Now',
    image: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&q=80&w=800',
    amenities: ['High-speed Wi-Fi', 'Air Conditioning', 'CCTV 24/7'],
  },
  {
    id: 2,
    name: 'Kost Putra Residence',
    price: 1800,
    type: 'Duo',
    location: 'Sudapin, Kidapawan City (Near USM)',
    rating: 4.5,
    badge: '2 Rooms Left',
    image: 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&q=80&w=800',
    amenities: ['Water Supply', 'Kitchen Access', 'Study Area'],
  },
  {
    id: 3,
    name: 'Skyline Central Suites',
    price: 3500,
    type: 'Studio',
    location: 'Apo Sandawa Homes, Kidapawan City',
    rating: 4.9,
    badge: 'Available Now',
    image: 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&q=80&w=800',
    amenities: ['Backup Power', 'Laundry Area', 'Parking Space'],
  },
]);

const filteredHouses = computed(() => {
  return houses.value.filter((h) => {
    const q = searchQuery.value.toLowerCase().trim();
    const matchesSearch = !q ||
      q.includes('kidapawan') ||
      h.name.toLowerCase().includes(q) ||
      h.location.toLowerCase().includes(q);
    const matchesType = !selectedType.value || h.type === selectedType.value;
    const matchesPrice = h.price <= Number(selectedPrice.value);
    return matchesSearch && matchesType && matchesPrice;
  });
});
</script>
