<template>
  <AppLayout page-title="House Details">
    <!-- Back to explore header -->
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

    <!-- Main Header Card -->
    <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-xs mb-8">
      <!-- Cover Photo with Overlay Details -->
      <div class="relative h-64 sm:h-96 bg-slate-900 overflow-hidden">
        <img
          :src="house.thumbnail_url ?? 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=1200&q=80'"
          :alt="house.name"
          class="w-full h-full object-cover opacity-90"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-900/30 to-transparent flex items-end p-6 sm:p-10">
          <div class="text-white max-w-2xl">
            <div class="flex items-center gap-2 mb-2">
              <span class="px-2.5 py-0.5 rounded-lg bg-white/20 backdrop-blur-md text-xs font-extrabold text-amber-300 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 fill-amber-300" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                {{ house.rating ?? '4.8' }} Rating
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
            <span class="text-slate-700 font-semibold text-xs">{{ house.contact_number || house.owner?.phone || '0912 345 6789' }}</span>
          </div>
          <div class="hidden sm:block w-px h-8 bg-slate-200"></div>
          <div>
            <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Available Rooms</span>
            <span class="text-emerald-600 font-extrabold text-xs">{{ availableRoomsCount }} Vacant Units</span>
          </div>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
          <!-- Clickable Google Maps Link -->
          <a
            :href="house.map_url || `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(house.name + ' ' + house.address + ' Kidapawan City')}`"
            target="_blank"
            class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-800 font-bold text-xs border border-slate-200 shadow-2xs transition flex items-center gap-1.5"
          >
            <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
            Open in Google Maps ↗
          </a>

          <button
            @click="showAppointmentModal = true"
            class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition flex items-center gap-1.5 cursor-pointer"
          >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            Free Viewing Visit
          </button>
        </div>
      </div>

      <!-- Description & Amenities & Map Location -->
      <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2 space-y-6">
          <div>
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-2.5">About This Property</h3>
            <p class="text-xs text-slate-600 leading-relaxed font-normal">
              {{ house.description ?? 'A comfortable, clean, and secure boarding house accommodation conveniently located near schools, markets, and transportation terminals in Kidapawan City.' }}
            </p>
          </div>

          <div>
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-3">House Facilities & Inclusions</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
              <div v-for="item in ['High-speed Wi-Fi', '24/7 Security CCTV', '24/7 Clean Water', 'Cooking Area Allowed', 'Laundry Washing Space', 'Motor Parking Area']" :key="item" class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/70 text-slate-700 text-xs font-semibold flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span>{{ item }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Google Maps Card Section -->
        <div class="bg-indigo-50/40 rounded-2xl p-5 border border-indigo-100 flex flex-col justify-between">
          <div>
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-2 flex items-center gap-1.5">
              <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
              Location & Directions
            </h3>
            <p class="text-xs text-slate-600 mb-4 leading-relaxed font-medium">
              {{ house.address }}, {{ house.barangay }}, Kidapawan City
            </p>
          </div>

          <div class="p-4 bg-white rounded-xl border border-slate-200/80 shadow-2xs text-center space-y-2.5">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
            </div>
            <p class="text-xs font-bold text-slate-800">Pinpoint on Google Maps</p>
            <p class="text-[11px] text-slate-500">Get live GPS turn-by-turn navigation on your phone</p>
            <a
              :href="house.map_url || `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(house.name + ' ' + house.address + ' Kidapawan City')}`"
              target="_blank"
              rel="noopener noreferrer"
              referrerpolicy="no-referrer-when-downgrade"
              class="w-full inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
              View Location on Map ↗
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Available Rooms Selection Section -->
    <div class="mb-10">
      <div class="flex items-center justify-between mb-5">
        <div>
          <h2 class="text-xl font-black text-slate-900 tracking-tight">Available Rooms & Rates</h2>
          <p class="text-xs text-slate-500 font-medium mt-0.5">Select a room below to submit your online reservation and payment</p>
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
                :class="room.availability_status === 'available' ? 'bg-emerald-500 text-white' : room.availability_status === 'occupied' ? 'bg-rose-500 text-white' : 'bg-amber-500 text-white'"
                class="px-2.5 py-1 rounded-full text-[10px] font-bold shadow-xs capitalize"
              >
                ● {{ room.availability_status?.replace('_', ' ') }}
              </span>
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
                <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md flex items-center gap-1">
                  <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                  {{ room.capacity }} pax
                </span>
              </div>
              <p class="text-xs font-semibold text-indigo-600 mb-2">{{ room.room_type }}</p>
              <p class="text-xs text-slate-500 line-clamp-2">{{ room.description || 'Clean and comfortable student room with bed and desk.' }}</p>
            </div>

            <div class="pt-4 mt-3 border-t border-slate-100">
              <button
                v-if="room.availability_status === 'available'"
                @click="openReservationModal(room)"
                class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition text-center cursor-pointer"
              >
                Reserve This Room & Pay
              </button>
              <div v-else class="text-center py-2 text-xs font-bold text-slate-400 bg-slate-50 rounded-xl">
                Currently Occupied
              </div>
            </div>
          </div>
        </div>
      </div>

      <p v-else class="text-xs text-slate-500 bg-white p-8 rounded-2xl border border-slate-200 text-center font-medium">
        No rooms currently listed for this boarding house.
      </p>
    </div>

    <!-- Free Viewing Appointment Modal -->
    <div
      v-if="showAppointmentModal"
      role="dialog"
      aria-modal="true"
      aria-labelledby="appointment-modal-title"
      @keydown.esc="showAppointmentModal = false"
      tabindex="-1"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs"
    >
      <div class="bg-white rounded-3xl p-6 sm:p-7 max-w-md w-full border border-slate-200 shadow-2xl animate-in fade-in duration-150">
        <div class="mb-4">
          <div>
            <h3 id="appointment-modal-title" class="text-lg font-black text-slate-900">Schedule In-Person Viewing</h3>
            <p class="text-xs text-slate-500">Free inspection tour at {{ house.name }} (No payment needed)</p>
          </div>
        </div>

        <form @submit.prevent="submitAppointment" class="space-y-4">
          <div>
            <label for="appointment-date" class="block text-xs font-bold text-slate-700 mb-1">Preferred Date *</label>
            <input
              id="appointment-date"
              v-model="appointmentForm.scheduled_date"
              type="date"
              required
              aria-required="true"
              class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
            />
          </div>

          <div>
            <label for="appointment-time" class="block text-xs font-bold text-slate-700 mb-1">Time Slot *</label>
            <select
              id="appointment-time"
              v-model="appointmentForm.time_slot"
              class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
            >
              <option value="Morning (9:00 AM - 11:30 AM)">Morning (9:00 AM - 11:30 AM)</option>
              <option value="Afternoon (1:30 PM - 4:30 PM)">Afternoon (1:30 PM - 4:30 PM)</option>
              <option value="Evening (5:00 PM - 6:30 PM)">Evening (5:00 PM - 6:30 PM)</option>
            </select>
          </div>

          <div>
            <label for="appointment-notes" class="block text-xs font-bold text-slate-700 mb-1">Notes / Questions to Landlord</label>
            <textarea
              id="appointment-notes"
              v-model="appointmentForm.notes"
              rows="2"
              placeholder="e.g. Inquiring about gate curfew, parking, or companion inspection..."
              class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
            ></textarea>
          </div>

          <div class="flex items-center justify-end gap-2.5 pt-2">
            <button
              type="button"
              @click="showAppointmentModal = false"
              class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 cursor-pointer"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-md shadow-indigo-600/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 cursor-pointer"
            >
              Submit Request
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Room Reservation & Payment Modal -->
    <div
      v-if="showReservationModal"
      role="dialog"
      aria-modal="true"
      aria-labelledby="reservation-modal-title"
      @keydown.esc="showReservationModal = false"
      tabindex="-1"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs"
    >
      <div class="bg-white rounded-3xl p-6 sm:p-7 max-w-lg w-full border border-slate-200 shadow-2xl animate-in fade-in duration-150 max-h-[90vh] overflow-y-auto">
        <div class="mb-4">
          <div>
            <h3 id="reservation-modal-title" class="text-lg font-black text-slate-900">Reserve Room {{ selectedRoom?.room_number }}</h3>
            <p class="text-xs text-slate-500">{{ selectedRoom?.room_type }} • ₱{{ formatNumber(selectedRoom?.monthly_rent) }}/month</p>
          </div>
        </div>

        <form @submit.prevent="submitReservation" class="space-y-4">
          <div>
            <label for="reservation-date" class="block text-xs font-bold text-slate-700 mb-1">Intended Move-in Date *</label>
            <input
              id="reservation-date"
              v-model="reservationForm.intended_move_in_date"
              type="date"
              required
              aria-required="true"
              class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
            />
          </div>

          <!-- Payment Method Selection -->
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

          <!-- Digital Payment Info Box (GCash / Maya) -->
          <div v-if="reservationForm.payment_method === 'GCash' || reservationForm.payment_method === 'Maya'" class="p-3.5 rounded-2xl bg-indigo-50 border border-indigo-100 text-xs text-indigo-900 space-y-2">
            <div class="flex items-center justify-between font-bold">
              <span>Send Deposit via {{ reservationForm.payment_method }}</span>
              <span class="text-indigo-700 font-mono">0912 345 6789</span>
            </div>
            <p class="text-[11px] text-indigo-700 leading-relaxed">
              Transfer your reservation downpayment or 1-month advance, then enter your transaction reference number and upload the screenshot receipt below.
            </p>
          </div>

          <!-- Payment Reference Field (for digital transfers) -->
          <div v-if="reservationForm.payment_method !== 'Cash Upon Move-in'">
            <label for="reservation-ref" class="block text-xs font-bold text-slate-700 mb-1">Reference Number / Txn ID</label>
            <input
              id="reservation-ref"
              v-model="reservationForm.payment_reference"
              type="text"
              placeholder="e.g. 100234891234"
              class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
            />
          </div>

          <!-- Upload Receipt File from Device -->
          <div v-if="reservationForm.payment_method !== 'Cash Upon Move-in'">
            <label class="block text-xs font-bold text-slate-700 mb-1">Proof of Payment (Upload Receipt from Phone / Laptop)</label>
            <label class="flex flex-col items-center justify-center border-2 border-dashed border-slate-300 hover:border-indigo-500 rounded-2xl p-3 bg-slate-50/50 hover:bg-indigo-50/20 cursor-pointer transition focus-within:ring-2 focus-within:ring-indigo-500">
              <svg class="w-6 h-6 text-indigo-500 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <span class="text-xs font-bold text-slate-700">{{ receiptFile ? receiptFile.name : 'Select receipt screenshot' }}</span>
              <input type="file" accept="image/*" class="hidden" @change="handleReceiptFileChange" />
            </label>
          </div>

          <div>
            <label for="reservation-remarks" class="block text-xs font-bold text-slate-700 mb-1">Remarks or Note to Landlord</label>
            <textarea
              id="reservation-remarks"
              v-model="reservationForm.remarks"
              rows="2"
              placeholder="e.g. Requesting quiet study corner, bringing laptop..."
              class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
            ></textarea>
          </div>

          <!-- Explicit Consent Checkbox -->
          <div class="pt-2 border-t border-slate-100">
            <label for="reservation-consent" class="flex items-start gap-2.5 cursor-pointer text-xs text-slate-600 leading-snug select-none">
              <input
                id="reservation-consent"
                type="checkbox"
                v-model="reservationForm.agree_policy"
                required
                aria-required="true"
                class="mt-0.5 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 transition shrink-0 cursor-pointer"
              />
              <span>
                I agree to the <a href="/refund-policy" target="_blank" class="text-indigo-600 font-bold hover:underline">Refund &amp; Cancellation Policy</a>, acknowledge the house rules, and verify that my submitted details are accurate.
              </span>
            </label>
          </div>

          <div class="flex items-center justify-end gap-2.5 pt-2">
            <button
              type="button"
              @click="showReservationModal = false"
              class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 cursor-pointer"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-md shadow-emerald-600/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 cursor-pointer"
            >
              Submit Reservation & Payment
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ house: Object });

const showAppointmentModal = ref(false);
const showReservationModal = ref(false);
const selectedRoom = ref(null);
const receiptFile = ref(null);

const availableRoomsCount = computed(() => {
  return props.house.rooms?.filter(r => r.availability_status === 'available').length ?? 0;
});

const appointmentForm = reactive({
  scheduled_date: '',
  time_slot: 'Morning (9:00 AM - 11:30 AM)',
  notes: '',
});

const reservationForm = reactive({
  intended_move_in_date: '',
  payment_method: 'GCash',
  deposit_amount: 0,
  payment_reference: '',
  remarks: '',
  agree_policy: false,
});

function defaultRoomImage(type) {
  if (type === 'Studio Suite') return 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=600&q=80';
  if (type === 'Duo Room') return 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=600&q=80';
  return 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=600&q=80';
}

function formatNumber(val) {
  return Number(val || 0).toLocaleString();
}

function handleReceiptFileChange(e) {
  const file = e.target.files[0];
  if (file) {
    receiptFile.value = file;
  }
}

function submitAppointment() {
  router.post(`/student/houses/${props.house.id}/appointment`, appointmentForm, {
    onSuccess: () => (showAppointmentModal.value = false),
  });
}

function openReservationModal(room) {
  selectedRoom.value = room;
  reservationForm.deposit_amount = room.monthly_rent;
  reservationForm.agree_policy = false;
  receiptFile.value = null;
  showReservationModal.value = true;
}

function submitReservation() {
  if (!selectedRoom.value) return;

  const formData = new FormData();
  formData.append('intended_move_in_date', reservationForm.intended_move_in_date);
  formData.append('payment_method', reservationForm.payment_method);
  formData.append('deposit_amount', reservationForm.deposit_amount);
  formData.append('payment_reference', reservationForm.payment_reference || '');
  formData.append('remarks', reservationForm.remarks || '');
  formData.append('agree_policy', reservationForm.agree_policy ? '1' : '');

  if (receiptFile.value) {
    formData.append('receipt_file', receiptFile.value);
  }

  router.post(`/student/rooms/${selectedRoom.value.id}/reserve`, formData, {
    onSuccess: () => (showReservationModal.value = false),
  });
}
</script>
