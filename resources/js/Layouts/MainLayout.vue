<template>
  <div class="min-h-screen bg-slate-50 text-slate-800 flex flex-col selection:bg-indigo-600 selection:text-white antialiased">
    <!-- Top Navigation Header -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-2xs">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-3">
        <a href="/" class="flex items-center gap-2.5 sm:gap-3 group shrink-0 min-w-0">
          <img src="/favicon.svg" alt="KidaBoard" class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-300 object-cover shrink-0">
          <div class="min-w-0">
            <div class="flex items-center gap-1.5 sm:gap-2">
              <span class="text-lg sm:text-xl font-extrabold tracking-tight text-slate-900 whitespace-nowrap">Kida<span class="text-indigo-600">Board</span></span>
              <span class="hidden sm:inline-block text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">Kidapawan</span>
            </div>
            <p class="hidden sm:block text-[11px] text-slate-500 leading-none truncate">Smart Student Accommodation Platform</p>
          </div>
        </a>

        <!-- Middle Search / Nav -->
        <div class="hidden md:flex items-center gap-6">
          <Link href="/" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition">Home</Link>
          <Link :href="user ? dashboardUrl : '/login'" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition">Search Houses</Link>
          <Link :href="user ? dashboardUrl : '/login'" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition">Appointments</Link>
          <Link :href="user ? dashboardUrl : '/login'" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition">Reservations</Link>
        </div>

        <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
          <template v-if="user">
            <Link :href="dashboardUrl" class="px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-bold text-indigo-600 hover:text-indigo-700 transition whitespace-nowrap">Dashboard</Link>
            <Link href="/logout" method="post" as="button" class="px-2.5 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold text-slate-700 hover:text-rose-600 transition whitespace-nowrap">Sign Out</Link>
          </template>
          <template v-else>
            <Link href="/login" class="px-2.5 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold text-slate-700 hover:text-indigo-600 transition whitespace-nowrap">Sign In</Link>
            <Link href="/register" class="px-3 py-1.5 sm:px-5 sm:py-2.5 text-xs sm:text-sm font-bold rounded-lg sm:rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm sm:shadow-md sm:shadow-indigo-500/20 transition whitespace-nowrap">Get Started</Link>
          </template>
        </div>
      </div>
    </header>

    <!-- Page Body -->
    <main class="flex-1 flex flex-col">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white py-10 mt-auto">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-6 border-b border-slate-100">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black text-xs shadow-sm">
              KB
            </div>
            <div>
              <p class="text-sm font-extrabold text-slate-900 leading-tight">Kida<span class="text-indigo-600">Board</span></p>
              <p class="text-[11px] text-slate-500">Kidapawan City Smart Student Accommodation Portal</p>
            </div>
          </div>

          <!-- Legal & Resource Links -->
          <nav aria-label="Legal & Information" class="flex flex-wrap items-center justify-center gap-y-2 gap-x-5 text-xs font-semibold text-slate-600">
            <a href="/privacy-policy" class="hover:text-indigo-600 transition">Privacy Policy</a>
            <span class="text-slate-300">•</span>
            <a href="/terms" class="hover:text-indigo-600 transition">Terms & Conditions</a>
            <span class="text-slate-300">•</span>
            <a href="/refund-policy" class="hover:text-indigo-600 transition">Refund Policy</a>
            <span class="text-slate-300">•</span>
            <a href="/cookie-policy" class="hover:text-indigo-600 transition">Cookie Policy</a>
          </nav>
        </div>

        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
          <div class="flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            <span>RA 10173 Compliant • Kidapawan City Housing Network</span>
          </div>
          <div>
            &copy; 2026 KidaBoard Student Housing Initiative. All rights reserved.
          </div>
        </div>
      </div>
    </footer>

    <!-- Cookie Consent Banner -->
    <CookieConsent />
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import CookieConsent from '@/Components/CookieConsent.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);

const dashboardUrl = computed(() => {
  if (!user.value) return '/login';
  switch (user.value.role) {
    case 'admin': return '/admin/dashboard';
    case 'owner': return '/owner/dashboard';
    case 'student': return '/student/dashboard';
    default: return '/';
  }
});
</script>
