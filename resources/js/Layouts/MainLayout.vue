<template>
  <div class="min-h-screen bg-slate-50 text-slate-800 flex flex-col selection:bg-indigo-600 selection:text-white antialiased">
    <!-- Top Navigation Header -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-2xs">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <a href="/" class="flex items-center gap-3 group">
          <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black text-sm shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-300">
            KB
          </div>
          <div>
            <div class="flex items-center gap-2">
              <span class="text-xl font-extrabold tracking-tight text-slate-900">Kida<span class="text-indigo-600">Board</span></span>
              <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">Kidapawan</span>
            </div>
            <p class="text-[11px] text-slate-500 leading-none">Smart Student Accommodation Platform</p>
          </div>
        </a>

        <!-- Middle Search / Nav -->
        <div class="hidden md:flex items-center gap-6">
          <Link href="/" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition">Home</Link>
          <Link :href="user ? dashboardUrl : '/login'" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition">Search Houses</Link>
          <Link :href="user ? dashboardUrl : '/login'" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition">Appointments</Link>
          <Link :href="user ? dashboardUrl : '/login'" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition">Reservations</Link>
        </div>

        <div class="flex items-center gap-3">
          <template v-if="user">
            <Link :href="dashboardUrl" class="px-4 py-2 text-sm font-bold text-indigo-600 hover:text-indigo-700 transition">Dashboard</Link>
            <Link href="/logout" method="post" as="button" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-rose-600 transition">Sign Out</Link>
          </template>
          <template v-else>
            <Link href="/login" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-indigo-600 transition">Sign In</Link>
            <Link href="/register" class="px-5 py-2.5 text-sm font-bold rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-500/20 transition">Get Started</Link>
          </template>
        </div>
      </div>
    </header>

    <!-- Page Body -->
    <main class="flex-1 flex flex-col">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white py-8 mt-auto">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
        <div class="flex items-center gap-2">
          <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
          <span class="font-medium">KidaBoard • Smart Web-Based Boarding House Search & Reservation System</span>
        </div>
        <div>
          &copy; 2026 Kidapawan City Student Housing Initiative. All rights reserved.
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

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
