<template>
  <AppLayout page-title="House Details">
    <!-- Back -->
    <div class="mb-5 flex items-center justify-between">
      <Link href="/student/explore" class="inline-flex items-center gap-2 text-xs font-bold text-indigo-600 hover:text-indigo-700 bg-white px-3 py-1.5 rounded-xl border border-slate-200/80 shadow-2xs transition">
        <span>←</span> Back to Search Houses
      </Link>
      <div class="flex items-center gap-2">
        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold flex items-center gap-1.5">
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
          Verified Boarding House
        </span>
      </div>
    </div>

    <!-- Active Booking Alert Banner -->
    <div v-if="activeAppointment || activeReservation" class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-semibold flex items-center justify-between gap-4 shadow-xs">
      <div class="flex items-start gap-3">
        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold shrink-0">📌</div>
        <div>
          <p class="font-extrabold text-amber-900">Your Current Booking on This Boarding House</p>
          <p class="text-[11px] text-amber-800 mt-0.5">
            <span v-if="activeAppointment" class="font-bold">Viewing Tour: {{ activeAppointment.status }} ({{ activeAppointment.scheduled_date }})</span>
            <span v-if="activeAppointment && activeReservation"> • </span>
            <span v-if="activeReservation" class="font-bold">Room Reservation: {{ activeReservation.status }} ({{ activeReservation.slots_reserved }} slot(s))</span>
          </p>
        </div>
      </div>
      <div class="flex items-center gap-2 shrink-0">
        <Link
          v-if="activeReservation"
          href="/student/reservations"
          class="px-3.5 py-1.5 rounded-xl bg-amber-200/80 hover:bg-amber-300 text-amber-900 font-bold text-xs transition"
        >
          My Reservations ↗
        </Link>
      </div>
    </div>

    <!-- Main Header Card -->
    <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-xs mb-8">
      <!-- Cover Photo -->
      <div class="relative h-64 sm:h-96 bg-slate-900 overflow-hidden">
        <img
          :src="house.thumbnail_url ?? 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=1200&q=80'"
          :alt="house.name"
          class="w-full h-full object-cover opacity-90"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-900/30 to-transparent flex items-end p-6 sm:p-10">
          <div class="text-white max-w-2xl">
            <div class="flex items-center gap-2 mb-2">
              <!-- Real star rating badge -->
              <span class="px-2.5 py-0.5 rounded-lg bg-white/20 backdrop-blur-md text-xs font-extrabold text-amber-300 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 fill-amber-300" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                {{ house.rating ? house.rating.toFixed(1) : 'No ratings yet' }}
                <span v-if="house.rating" class="font-normal opacity-80">({{ house.reviews?.length ?? 0 }} reviews)</span>
              </span>
              <span class="px-2.5 py-0.5 rounded-lg bg-indigo-600/80 backdrop-blur-md text-xs font-bold flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                {{ house.barangay }}, Kidapawan
              </span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-black tracking-tight leading-tight">{{ house.name }}</h1>
            <p class="text-xs sm:text-sm text-slate-200 mt-2 flex items-center gap-1.5">
              <svg class="w-4 h-4 text-indigo-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
              <span>{{ house.address }}</span>
            </p>
          </div>
        </div>
      </div>

      <!-- Quick Action Strip -->
      <div class="p-6 sm:p-7 flex flex-col md:flex-row md:items-center justify-between gap-5 border-b border-slate-100 bg-slate-50/50">
        <div class="flex items-center gap-6 flex-wrap">
          <div>
            <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Landlord / Owner</span>
            <span class="text-slate-900 font-extrabold text-sm">{{ house.owner?.name ?? 'Boarding House Owner' }}</span>
          </div>
          <div class="hidden sm:block w-px h-8 bg-slate-200"></div>
          <div>
            <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Contact Number</span>
            <span class="text-slate-700 font-semibold text-xs">{{ house.contact_number || house.owner?.phone || '—' }}</span>
          </div>
          <div class="hidden sm:block w-px h-8 bg-slate-200"></div>
          <div>
            <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Available Rooms</span>
            <span class="text-emerald-600 font-extrabold text-xs">{{ availableRoomsCount }} Vacant Units</span>
          </div>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
          <a
            :href="house.map_url || `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(house.name + ' ' + house.address + ' Kidapawan City')}`"
            target="_blank"
            class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-800 font-bold text-xs border border-slate-200 shadow-2xs transition flex items-center gap-1.5"
          >
            <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
            Open in Google Maps ↗
          </a>

          <button type="button" @click="openChatWithOwner" class="px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-bold text-xs shadow-md shadow-violet-600/20 transition flex items-center gap-1.5 cursor-pointer">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
            Chat with Owner
          </button>

          <div class="relative group">
            <button
              @click="activeAppointment ? null : (showAppointmentModal = true)"
              :disabled="!!activeAppointment"
              :class="activeAppointment ? 'bg-slate-300 text-slate-500 cursor-not-allowed opacity-70' : 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/20 cursor-pointer'"
              class="px-5 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-1.5"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
              Free Viewing Visit
            </button>
            <div v-if="activeAppointment" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-56 text-center px-3 py-2 rounded-xl bg-slate-900 text-white text-[10px] font-semibold shadow-xl pointer-events-none opacity-0 group-hover:opacity-100 transition z-10">
              You already have an active appointment ({{ activeAppointment.status }}) for this house.
              <div class="absolute top-full left-1/2 -translate-x-1/2 w-2 h-2 bg-slate-900 rotate-45"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Description & Amenities & Map -->
      <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2 space-y-6">
          <div>
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-2.5">About This Property</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
              {{ house.description ?? 'A comfortable, clean, and secure boarding house accommodation conveniently located near schools, markets, and transportation terminals in Kidapawan City.' }}
            </p>
          </div>
          <div>
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-3">House Facilities & Inclusions</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
              <div
                v-for="item in (house.amenities && house.amenities.length > 0 ? house.amenities : ['24/7 Security & CCTV', 'Free High-Speed Wi-Fi', 'Water Refill Station', 'Shared Kitchen / Cooking Allowed', 'Laundry & Drying Area', 'Parking Space'])"
                :key="item"
                class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/70 text-slate-700 text-xs font-semibold flex items-center gap-2"
              >
                <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span>{{ item }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Google Maps Card -->
        <div class="bg-indigo-50/40 rounded-2xl p-5 border border-indigo-100 flex flex-col justify-between">
          <div>
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-2 flex items-center gap-1.5">
              <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
              Location & Directions
            </h3>
            <p class="text-xs text-slate-600 mb-4 leading-relaxed font-medium">{{ house.address }}, {{ house.barangay }}, Kidapawan City</p>
          </div>
          <div class="p-4 bg-white rounded-xl border border-slate-200/80 shadow-2xs text-center space-y-2.5">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
            </div>
            <p class="text-xs font-bold text-slate-800">Pinpoint on Google Maps</p>
            <p class="text-[11px] text-slate-500">Get live GPS turn-by-turn navigation on your phone</p>
            <a
              :href="house.map_url || `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(house.name + ' ' + house.address + ' Kidapawan City')}`"
              target="_blank" rel="noopener noreferrer"
              class="w-full inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
              View Location on Map ↗
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════ Available Rooms Section ═══════════════ -->
    <div class="mb-10">
      <div class="flex items-center justify-between mb-5">
        <div>
          <h2 class="text-xl font-black text-slate-900 tracking-tight">Available Rooms & Bedspaces</h2>
          <p class="text-xs text-slate-500 font-medium mt-0.5">Select how many slots you need. Rooms support shared bedspace booking.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5" v-if="house.rooms?.length">
        <div
          v-for="room in house.rooms"
          :key="room.id"
          class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition flex flex-col justify-between group"
        >
          <!-- Room Photo -->
          <div class="relative h-44 bg-slate-100 overflow-hidden">
            <img
              :src="room.photo_url || defaultRoomImage(room.room_type)"
              :alt="'Room ' + room.room_number"
              class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
              onerror="this.src='https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=600&q=80'"
            />
            <div class="absolute top-3 left-3">
              <span
                :class="room.availability_status === 'under_maintenance' ? 'bg-amber-500 text-white' : (room.remaining_slots ?? 1) > 0 ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white'"
                class="px-2.5 py-1 rounded-full text-[10px] font-bold shadow-xs capitalize"
              >● {{ room.availability_status === 'under_maintenance' ? 'Under Maintenance' : (room.remaining_slots ?? 1) > 0 ? (room.capacity > 1 ? `${room.remaining_slots} of ${room.capacity} slots left` : 'Available') : 'Fully Occupied' }}</span>
            </div>
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
                <!-- Capacity badge -->
                <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md flex items-center gap-1">
                  <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                  {{ room.capacity }} pax
                </span>
              </div>
              <p class="text-xs font-semibold text-indigo-600 mb-1">{{ room.room_type }}</p>
              <p class="text-xs text-slate-500 line-clamp-2">{{ room.description || 'Clean and comfortable student room with bed and desk.' }}</p>

              <!-- Bedspace info strip -->
              <div v-if="room.capacity > 1" class="mt-2 px-3 py-2 rounded-xl bg-blue-50 border border-blue-100 text-[11px] text-blue-700 font-semibold flex items-center justify-between gap-1.5">
                <span class="flex items-center gap-1.5">
                  <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                  Shared Bedspace Room
                </span>
                <span class="font-extrabold text-blue-900 bg-white/80 px-2 py-0.5 rounded-md border border-blue-200/60">
                  {{ room.remaining_slots ?? room.capacity }} / {{ room.capacity }} slots open
                </span>
              </div>
            </div>

            <div class="pt-4 mt-3 border-t border-slate-100">
              <div v-if="room.availability_status !== 'under_maintenance' && (room.remaining_slots ?? 1) > 0">
                <button
                  @click="openReservationModal(room)"
                  class="w-full py-2.5 rounded-xl font-bold text-xs bg-emerald-600 hover:bg-emerald-700 text-white cursor-pointer shadow-xs transition text-center flex items-center justify-center gap-1.5"
                >
                  <span>Reserve Bedspace / Room</span>
                  <span v-if="room.capacity > 1" class="text-[10px] bg-emerald-700/80 px-2 py-0.5 rounded-full font-bold">
                    ({{ room.remaining_slots ?? room.capacity }} open)
                  </span>
                </button>
              </div>
              <div v-else-if="room.availability_status === 'under_maintenance'" class="text-center py-2 text-xs font-bold text-amber-700 bg-amber-50 rounded-xl border border-amber-200">
                Under Maintenance
              </div>
              <div v-else class="text-center py-2 text-xs font-bold text-slate-400 bg-slate-50 rounded-xl">
                Fully Occupied ({{ room.capacity }}/{{ room.capacity }} pax)
              </div>
            </div>
          </div>
        </div>
      </div>

      <p v-else class="text-xs text-slate-500 bg-white p-8 rounded-2xl border border-slate-200 text-center font-medium">
        No rooms currently listed for this boarding house.
      </p>
    </div>

    <!-- ═══════════════ Reviews Section ═══════════════ -->
    <div class="mb-10">
      <div class="flex items-center justify-between mb-5 flex-wrap gap-4">
        <div>
          <h2 class="text-xl font-black text-slate-900 tracking-tight">Boarder & Student Reviews</h2>
          <p class="text-xs text-slate-500 font-medium mt-0.5">Real ratings and reviews from students and customers.</p>
        </div>
        <!-- Overall rating summary -->
        <div v-if="house.rating" class="flex items-center gap-3 bg-white border border-slate-200 rounded-2xl px-5 py-3 shadow-xs">
          <span class="text-4xl font-black text-slate-900">{{ house.rating.toFixed(1) }}</span>
          <div>
            <div class="flex gap-0.5">
              <svg v-for="s in 5" :key="s" class="w-4 h-4" :class="s <= Math.round(house.rating) ? 'fill-amber-400 text-amber-400' : 'fill-slate-200 text-slate-200'" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            </div>
            <p class="text-[11px] text-slate-500 font-medium mt-0.5">{{ house.reviews?.length ?? 0 }} review{{ (house.reviews?.length ?? 0) !== 1 ? 's' : '' }}</p>
          </div>
        </div>
      </div>

      <!-- Write / Edit Review (for any registered student) -->
      <div v-if="canReview" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h3 class="text-sm font-extrabold text-slate-900">
              {{ myReview ? 'Update Your Rating & Review' : 'Rate & Review This Boarding House' }}
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Share your feedback to help fellow students find a safe, comfortable boarding house.</p>
          </div>
          <button
            v-if="myReview"
            @click="confirmDeleteReview"
            class="text-[11px] text-rose-600 hover:text-rose-700 font-bold transition cursor-pointer"
          >
            Remove my review
          </button>
        </div>

        <!-- Star picker with interactive hover & click -->
        <div class="flex items-center gap-1.5 mb-4 flex-wrap">
          <div class="flex items-center gap-1">
            <button
              v-for="s in 5"
              :key="s"
              type="button"
              @click="reviewForm.rating = s"
              class="cursor-pointer transition-transform hover:scale-125 focus:outline-none p-1"
              :aria-label="`Rate ${s} star${s !== 1 ? 's' : ''}`"
            >
              <svg class="w-8 h-8" :class="s <= reviewForm.rating ? 'fill-amber-400 text-amber-400 drop-shadow-xs' : 'fill-slate-200 text-slate-300'" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
            </button>
          </div>
          <span class="ml-2 text-xs font-black" :class="reviewForm.rating > 0 ? 'text-amber-600' : 'text-slate-400'">
            {{ ['', '⭐ 1 Star - Poor', '⭐⭐ 2 Stars - Fair', '⭐⭐⭐ 3 Stars - Good', '⭐⭐⭐⭐ 4 Stars - Very Good', '⭐⭐⭐⭐⭐ 5 Stars - Excellent!'][reviewForm.rating] || 'Click a star to rate' }}
          </span>
        </div>

        <textarea
          v-model="reviewForm.comment"
          rows="3"
          placeholder="Share your experience — cleanliness, security, study environment, Wi-Fi speed, landlord hospitality..."
          class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none mb-3"
        ></textarea>

        <div v-if="reviewError" class="flex items-start gap-2 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold mb-3">
          <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" /></svg>
          <span>{{ reviewError }}</span>
        </div>

        <button
          @click="submitReview"
          :disabled="reviewForm.rating === 0 || submittingReview"
          class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white text-xs font-bold transition shadow-md shadow-indigo-600/20 cursor-pointer flex items-center gap-2"
        >
          <svg v-if="submittingReview" class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
          <span>{{ submittingReview ? 'Saving…' : (myReview ? 'Update My Review' : 'Submit Rating & Review') }}</span>
        </button>
      </div>

      <!-- Guest Login Callout -->
      <div v-else-if="!$page.props.auth?.user" class="bg-indigo-50/70 border border-indigo-100 rounded-2xl p-5 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <p class="text-xs font-extrabold text-indigo-950">Have you visited or stayed at {{ house.name }}?</p>
          <p class="text-[11px] text-indigo-700 mt-0.5">Log in as a student to rate this boarding house and help other boarders.</p>
        </div>
        <Link href="/login" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 transition shrink-0 text-center">
          Log In to Review
        </Link>
      </div>

      <!-- Review Cards -->
      <div v-if="house.reviews?.length" class="space-y-4">
        <div
          v-for="review in house.reviews"
          :key="review.id"
          class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5"
        >
          <div class="flex items-start justify-between gap-3 mb-2">
            <div class="flex items-center gap-3">
              <!-- Avatar initials -->
              <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-extrabold text-sm shrink-0">
                {{ (review.student?.name ?? 'U').charAt(0).toUpperCase() }}
              </div>
              <div>
                <p class="text-xs font-extrabold text-slate-900">{{ review.student?.name ?? 'Tenant' }}</p>
                <p class="text-[11px] text-slate-400">{{ formatDate(review.created_at) }}</p>
              </div>
            </div>
            <!-- Stars -->
            <div class="flex gap-0.5 shrink-0">
              <svg v-for="s in 5" :key="s" class="w-3.5 h-3.5" :class="s <= review.rating ? 'fill-amber-400 text-amber-400' : 'fill-slate-200 text-slate-200'" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            </div>
          </div>
          <p v-if="review.comment" class="text-xs text-slate-600 leading-relaxed">{{ review.comment }}</p>
          <p v-else class="text-xs text-slate-400 italic">No comment left.</p>
        </div>
      </div>

      <div v-else class="bg-white rounded-2xl border border-slate-200 p-8 text-center">
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-400 flex items-center justify-center mx-auto mb-3">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
        </div>
        <p class="text-sm font-bold text-slate-700">No reviews yet</p>
        <p class="text-xs text-slate-400 mt-1">Be the first verified tenant to leave a review!</p>
      </div>
    </div>

    <!-- ═══════════════ Appointment Modal ═══════════════ -->
    <div
      v-if="showAppointmentModal"
      role="dialog" aria-modal="true" aria-labelledby="appointment-modal-title"
      @keydown.esc="showAppointmentModal = false" tabindex="-1"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs"
    >
      <div class="bg-white rounded-3xl p-6 sm:p-7 max-w-md w-full border border-slate-200 shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="mb-4">
          <h3 id="appointment-modal-title" class="text-lg font-black text-slate-900">Schedule In-Person Viewing</h3>
          <p class="text-xs text-slate-500">Free inspection tour at {{ house.name }} (No payment needed)</p>
        </div>

        <form @submit.prevent="submitAppointment" class="space-y-4">
          <div v-if="appointmentError" class="flex items-start gap-2.5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" /></svg>
            <span>{{ appointmentError }}</span>
          </div>

          <div>
            <label for="appointment-date" class="block text-xs font-bold text-slate-700 mb-1">Preferred Date *</label>
            <input id="appointment-date" v-model="appointmentForm.scheduled_date" type="date" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
          </div>

          <div>
            <label for="appointment-time" class="block text-xs font-bold text-slate-700 mb-1">Time Slot *</label>
            <select id="appointment-time" v-model="appointmentForm.time_slot" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
              <option value="Morning (9:00 AM - 11:30 AM)">Morning (9:00 AM - 11:30 AM)</option>
              <option value="Afternoon (1:30 PM - 4:30 PM)">Afternoon (1:30 PM - 4:30 PM)</option>
              <option value="Evening (5:00 PM - 6:30 PM)">Evening (5:00 PM - 6:30 PM)</option>
            </select>
          </div>

          <div>
            <label for="appointment-notes" class="block text-xs font-bold text-slate-700 mb-1">Notes / Questions to Landlord</label>
            <textarea id="appointment-notes" v-model="appointmentForm.notes" rows="2" placeholder="e.g. Inquiring about gate curfew, parking, or companion inspection…" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
          </div>

          <div class="flex items-center justify-end gap-2.5 pt-2">
            <button type="button" @click="showAppointmentModal = false; appointmentError = null" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">Cancel</button>
            <button type="submit" :disabled="submittingAppointment" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white text-xs font-bold transition shadow-md shadow-indigo-600/20 cursor-pointer">
              {{ submittingAppointment ? 'Submitting…' : 'Submit Request' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ═══════════════ Reservation Modal (with bedspace slots) ═══════════════ -->
    <div
      v-if="showReservationModal"
      role="dialog" aria-modal="true" aria-labelledby="reservation-modal-title"
      @keydown.esc="showReservationModal = false" tabindex="-1"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs"
    >
      <div class="bg-white rounded-3xl p-6 sm:p-7 max-w-lg w-full border border-slate-200 shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="mb-5">
          <h3 id="reservation-modal-title" class="text-lg font-black text-slate-900">Reserve Room {{ selectedRoom?.room_number }}</h3>
          <p class="text-xs text-slate-500">{{ selectedRoom?.room_type }} • ₱{{ formatNumber(selectedRoom?.monthly_rent) }}/month • {{ selectedRoom?.capacity }} pax capacity</p>
        </div>

        <form @submit.prevent="submitReservation" class="space-y-4">

          <!-- ── Solo Room (Fixed 1 Pax - No slot picker needed) ── -->
          <div v-if="selectedRoom?.capacity === 1" class="p-4 rounded-2xl bg-indigo-50/70 border border-indigo-100 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-black shrink-0">🏠</div>
              <div>
                <p class="text-xs font-black text-indigo-950">Solo Room (Private)</p>
                <p class="text-[11px] text-indigo-700 font-medium">1 person capacity • Entire private room for yourself, no roommates</p>
              </div>
            </div>
            <span class="text-xs font-black text-indigo-700 bg-white px-3 py-1.5 rounded-xl border border-indigo-200 shadow-2xs">
              1 Pax Solo
            </span>
          </div>

          <!-- ── Bedspace / Multi-slot Selector (Only when capacity > 1) ── -->
          <div v-else>
            <div class="flex items-center justify-between mb-2">
              <label for="slots-input" class="text-xs font-bold text-slate-700">How many slots do you need? *</label>
              <span class="text-[11px] text-indigo-600 font-bold">
                {{ parseInt(selectedRoom?.remaining_slots ?? selectedRoom?.capacity ?? 1) }} of {{ parseInt(selectedRoom?.capacity) }} slot(s) available
              </span>
            </div>
            <div class="flex items-center gap-3">
              <button
                type="button"
                @click="reservationForm.slots_reserved = Math.max(1, reservationForm.slots_reserved - 1); if (reservationForm.slots_reserved === 1) reservationForm.companion_name = ''"
                class="w-10 h-10 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold text-lg flex items-center justify-center transition cursor-pointer shrink-0"
                :disabled="reservationForm.slots_reserved <= 1"
              >−</button>
              <input
                id="slots-input"
                type="number"
                v-model.number="reservationForm.slots_reserved"
                :min="1"
                :max="parseInt(selectedRoom?.remaining_slots ?? selectedRoom?.capacity ?? 1)"
                @change="reservationForm.slots_reserved = Math.min(Math.max(1, reservationForm.slots_reserved), parseInt(selectedRoom?.remaining_slots ?? selectedRoom?.capacity ?? 1)); if (reservationForm.slots_reserved === 1) reservationForm.companion_name = ''"
                class="flex-1 text-center px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-sm font-black focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                placeholder="e.g. 2"
              />
              <button
                type="button"
                @click="reservationForm.slots_reserved = Math.min(parseInt(selectedRoom?.remaining_slots ?? selectedRoom?.capacity ?? 1), reservationForm.slots_reserved + 1)"
                class="w-10 h-10 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold text-lg flex items-center justify-center transition cursor-pointer shrink-0"
                :disabled="reservationForm.slots_reserved >= parseInt(selectedRoom?.remaining_slots ?? selectedRoom?.capacity ?? 1)"
              >+</button>
            </div>
            <div class="mt-2 px-3 py-2 rounded-xl text-[11px] font-semibold"
              :class="reservationForm.slots_reserved === parseInt(selectedRoom?.remaining_slots ?? selectedRoom?.capacity)
                ? 'bg-emerald-50 text-emerald-700 border border-emerald-100'
                : 'bg-indigo-50 text-indigo-700 border border-indigo-100'"
            >
              <span v-if="reservationForm.slots_reserved === selectedRoom?.capacity">🏠 You're reserving the <strong>entire room ({{ selectedRoom?.capacity }} pax)</strong> — no other boarders will be placed here.</span>
              <span v-else-if="reservationForm.slots_reserved === selectedRoom?.remaining_slots">🏠 You're reserving all remaining <strong>{{ selectedRoom?.remaining_slots }} slot(s)</strong> — this room will be fully booked.</span>
              <span v-else>👥 Reserving <strong>{{ reservationForm.slots_reserved }} slot(s)</strong>. {{ (selectedRoom?.remaining_slots ?? selectedRoom?.capacity) - reservationForm.slots_reserved }} slot(s) will remain available for other boarders.</span>
            </div>
          </div>

          <!-- Companions list (when >1 slot — textarea for listing all friends/siblings) -->
          <div v-if="reservationForm.slots_reserved > 1">
            <label for="companion-name" class="block text-xs font-bold text-slate-700 mb-1">
              Names of Your Group Members
              <span class="text-slate-400 font-normal ml-1">({{ reservationForm.slots_reserved - 1 }} companion{{ reservationForm.slots_reserved > 2 ? 's' : '' }})</span>
            </label>
            <textarea
              id="companion-name"
              v-model="reservationForm.companion_name"
              :rows="Math.max(2, reservationForm.slots_reserved - 1)"
              :placeholder="companionPlaceholder"
              class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"
            ></textarea>
            <p class="text-[11px] text-slate-400 mt-1">List one name per line. Optional but helps the owner prepare bedspaces properly.</p>
          </div>

          <!-- Move-in Date -->
          <div>
            <label for="reservation-date" class="block text-xs font-bold text-slate-700 mb-1">Intended Move-in Date *</label>
            <input id="reservation-date" v-model="reservationForm.intended_move_in_date" type="date" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
          </div>

          <!-- Payment Method -->
          <fieldset>
            <legend class="block text-xs font-bold text-slate-700 mb-1">Payment Method *</legend>
            <div class="grid grid-cols-2 gap-2">
              <label
                v-for="pm in ['GCash', 'Maya', 'Cash Upon Move-in', 'Bank Transfer']"
                :key="pm"
                :class="reservationForm.payment_method === pm ? 'border-indigo-600 bg-indigo-50/50 text-indigo-900 font-bold' : 'border-slate-200 bg-slate-50 text-slate-700'"
                class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer text-xs transition"
              >
                <input type="radio" v-model="reservationForm.payment_method" :value="pm" class="text-indigo-600 focus:ring-indigo-500" />
                <span>{{ pm }}</span>
              </label>
            </div>
          </fieldset>

          <!-- GCash/Maya info -->
          <div v-if="reservationForm.payment_method === 'GCash' || reservationForm.payment_method === 'Maya'" class="p-3.5 rounded-2xl bg-indigo-50 border border-indigo-100 text-xs text-indigo-900 space-y-2">
            <div class="flex items-center justify-between font-bold">
              <span>Send Deposit via {{ reservationForm.payment_method }}</span>
              <span class="text-indigo-700 font-mono">{{ house.contact_number || house.owner?.phone || '0912 345 6789' }}</span>
            </div>
            <p class="text-[11px] text-indigo-700 leading-relaxed">Transfer your reservation downpayment or 1-month advance, then enter your transaction reference number and upload the screenshot receipt below.</p>
          </div>

          <!-- Reference Number -->
          <div v-if="reservationForm.payment_method !== 'Cash Upon Move-in'">
            <label for="reservation-ref" class="block text-xs font-bold text-slate-700 mb-1">Reference Number / Txn ID</label>
            <input id="reservation-ref" v-model="reservationForm.payment_reference" type="text" placeholder="e.g. 100234891234" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
          </div>

          <!-- Receipt Upload -->
          <div v-if="reservationForm.payment_method !== 'Cash Upon Move-in'">
            <label class="block text-xs font-bold text-slate-700 mb-1">Proof of Payment (Upload Receipt)</label>
            <label class="flex flex-col items-center justify-center border-2 border-dashed border-slate-300 hover:border-indigo-500 rounded-2xl p-3 bg-slate-50/50 hover:bg-indigo-50/20 cursor-pointer transition focus-within:ring-2 focus-within:ring-indigo-500">
              <svg class="w-6 h-6 text-indigo-500 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
              <span class="text-xs font-bold text-slate-700">{{ receiptFile ? receiptFile.name : 'Select receipt screenshot' }}</span>
              <input type="file" accept="image/*" class="hidden" @change="handleReceiptFileChange" />
            </label>
          </div>

          <!-- Remarks -->
          <div>
            <label for="reservation-remarks" class="block text-xs font-bold text-slate-700 mb-1">Remarks or Note to Landlord</label>
            <textarea id="reservation-remarks" v-model="reservationForm.remarks" rows="2" placeholder="e.g. Requesting quiet study corner, bringing own mattress…" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
          </div>

          <!-- Consent -->
          <div class="pt-2 border-t border-slate-100">
            <label for="reservation-consent" class="flex items-start gap-2.5 cursor-pointer text-xs text-slate-600 leading-snug select-none">
              <input id="reservation-consent" type="checkbox" v-model="reservationForm.agree_policy" required class="mt-0.5 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 transition shrink-0 cursor-pointer" />
              <span>I agree to the <a href="/refund-policy" target="_blank" class="text-indigo-600 font-bold hover:underline">Refund & Cancellation Policy</a>, acknowledge the house rules, and verify that my submitted details are accurate.</span>
            </label>
          </div>

          <!-- Error -->
          <div v-if="reservationError" class="flex items-start gap-2.5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" /></svg>
            <span>{{ reservationError }}</span>
          </div>

          <div class="flex items-center justify-end gap-2.5 pt-2">
            <button type="button" @click="showReservationModal = false; reservationError = null" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">Cancel</button>
            <button type="submit" :disabled="submittingReservation" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 text-white text-xs font-bold transition shadow-md shadow-emerald-600/20 cursor-pointer">
              {{ submittingReservation ? 'Submitting…' : 'Submit Reservation & Payment' }}
            </button>
          </div>
        </form>
      </div>
    </div>

  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  house: Object,
  activeAppointment: Object,
  activeReservation: Object,
  myReview: Object,
  canReview: Boolean,
});

// ── Chat ──────────────────────────────────────────────────────────────────────
function openChatWithOwner() {
  if (props.house?.owner_id && typeof window !== 'undefined') {
    window.dispatchEvent(new CustomEvent('open-chat', {
      detail: {
        recipientId: props.house.owner_id,
        houseId: props.house.id,
        initialMessage: `Hi! I'm interested in inquiring about ${props.house.name}.`,
      },
    }));
  }
}

// ── Modal state ───────────────────────────────────────────────────────────────
const showAppointmentModal  = ref(false);
const showReservationModal  = ref(false);
const selectedRoom          = ref(null);
const receiptFile           = ref(null);

const appointmentError  = ref(null);
const reservationError  = ref(null);
const submittingAppointment = ref(false);
const submittingReservation = ref(false);

// ── Computed ──────────────────────────────────────────────────────────────────
const availableRoomsCount = computed(() =>
  props.house.rooms?.filter(r => r.availability_status === 'available').length ?? 0
);

// Generates a helpful placeholder based on how many companions needed
const companionPlaceholder = computed(() => {
  const count = (reservationForm.slots_reserved || 1) - 1;
  const examples = [
    'Juan dela Cruz (friend)',
    'Maria Santos (classmate)',
    'Pedro Reyes (blockmate)',
    'Ana Gomez (cousin)',
    'Carlo Bautista (friend)',
  ];
  return examples.slice(0, count).join('\n') || 'e.g. Juan dela Cruz (friend)';
});

// ── Appointment form ──────────────────────────────────────────────────────────
const appointmentForm = reactive({
  scheduled_date: '',
  time_slot: 'Morning (9:00 AM - 11:30 AM)',
  notes: '',
});

function submitAppointment() {
  appointmentError.value = null;
  submittingAppointment.value = true;
  router.post(`/student/houses/${props.house.id}/appointment`, appointmentForm, {
    onSuccess: () => { showAppointmentModal.value = false; submittingAppointment.value = false; },
    onError: (errors) => {
      submittingAppointment.value = false;
      appointmentError.value = Object.values(errors)[0] || 'Unable to submit. Please try again.';
    },
  });
}

// ── Reservation form ──────────────────────────────────────────────────────────
const reservationForm = reactive({
  intended_move_in_date: '',
  slots_reserved: 1,
  companion_name: '',
  payment_method: 'GCash',
  deposit_amount: 0,
  payment_reference: '',
  remarks: '',
  agree_policy: false,
});

function openReservationModal(room) {
  selectedRoom.value = room;
  reservationForm.intended_move_in_date = '';
  reservationForm.slots_reserved = 1;
  reservationForm.companion_name = '';
  reservationForm.deposit_amount = room.monthly_rent;
  reservationForm.payment_method = 'GCash';
  reservationForm.payment_reference = '';
  reservationForm.remarks = '';
  reservationForm.agree_policy = false;
  receiptFile.value = null;
  reservationError.value = null;
  showReservationModal.value = true;
}

function handleReceiptFileChange(e) {
  const file = e.target.files[0];
  if (file) receiptFile.value = file;
}

function submitReservation() {
  if (!selectedRoom.value) return;
  reservationError.value = null;
  submittingReservation.value = true;

  const formData = new FormData();
  formData.append('intended_move_in_date', reservationForm.intended_move_in_date);
  formData.append('slots_reserved', reservationForm.slots_reserved);
  formData.append('companion_name', reservationForm.companion_name || '');
  formData.append('payment_method', reservationForm.payment_method);
  formData.append('deposit_amount', reservationForm.deposit_amount);
  formData.append('payment_reference', reservationForm.payment_reference || '');
  formData.append('remarks', reservationForm.remarks || '');
  formData.append('agree_policy', reservationForm.agree_policy ? '1' : '');
  if (receiptFile.value) formData.append('receipt_file', receiptFile.value);

  router.post(`/student/rooms/${selectedRoom.value.id}/reserve`, formData, {
    onSuccess: () => { showReservationModal.value = false; submittingReservation.value = false; },
    onError: (errors) => {
      submittingReservation.value = false;
      reservationError.value = Object.values(errors)[0] || 'Unable to submit reservation. Please try again.';
    },
  });
}

// ── Reviews ───────────────────────────────────────────────────────────────────
const reviewError      = ref(null);
const submittingReview = ref(false);

const reviewForm = reactive({
  rating: props.myReview?.rating ?? 0,
  comment: props.myReview?.comment ?? '',
});

function submitReview() {
  if (reviewForm.rating === 0) {
    reviewError.value = 'Please select a star rating (1 to 5).';
    return;
  }
  reviewError.value = null;
  submittingReview.value = true;
  router.post(`/student/houses/${props.house.id}/review`, {
    rating: reviewForm.rating,
    comment: reviewForm.comment,
  }, {
    preserveScroll: true,
    onSuccess: () => { submittingReview.value = false; },
    onError: (errors) => {
      submittingReview.value = false;
      reviewError.value = Object.values(errors)[0] || 'Unable to submit review. Please try again.';
    },
  });
}

function confirmDeleteReview() {
  if (!confirm('Remove your review for this boarding house?')) return;
  router.delete(`/student/houses/${props.house.id}/review`, {
    preserveScroll: true,
    onSuccess: () => {
      reviewForm.rating = 0;
      reviewForm.comment = '';
    },
    onError: (errors) => {
      reviewError.value = Object.values(errors)[0] || 'Could not remove review.';
    },
  });
}

watch(() => props.myReview, (newReview) => {
  if (newReview) {
    reviewForm.rating = newReview.rating ?? 0;
    reviewForm.comment = newReview.comment ?? '';
  }
}, { immediate: true });

// ── Helpers ───────────────────────────────────────────────────────────────────
function defaultRoomImage(type) {
  if (type === 'Studio Suite') return 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=600&q=80';
  if (type === 'Duo Room')    return 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=600&q=80';
  return 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=600&q=80';
}

function formatNumber(val) {
  return Number(val || 0).toLocaleString();
}

function formatDate(dateStr) {
  if (!dateStr) return '';
  return new Date(dateStr).toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' });
}
</script>
