<template>
  <div class="min-h-screen bg-slate-50 text-slate-800 flex antialiased font-sans">
    <!-- Mobile sidebar overlay -->
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
      class="fixed inset-0 bg-slate-900/40 z-30 lg:hidden backdrop-blur-xs"
    />

    <!-- Sidebar � sticky, always full viewport height -->
    <aside
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
      class="fixed top-0 left-0 z-40 h-screen w-64 bg-white border-r border-slate-200/80 flex flex-col lg:sticky lg:translate-x-0 transition-transform duration-200 ease-in-out shadow-xs shrink-0 select-none"
    >
      <!-- Logo header -->
      <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 shrink-0">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 flex items-center justify-center font-extrabold text-white text-base shadow-md shadow-indigo-500/20 shrink-0">
          KB
        </div>
        <div class="min-w-0">
          <div class="flex items-center gap-1.5">
            <h2 class="text-base font-extrabold text-slate-900 tracking-tight leading-tight">Kida<span class="text-indigo-600">Board</span></h2>
            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-700 uppercase tracking-wider">v2</span>
          </div>
          <div class="flex items-center gap-1.5 mt-0.5">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <p class="text-[11px] text-slate-500 font-semibold capitalize">{{ role }} Portal</p>
          </div>
        </div>
      </div>

      <!-- Nav groups � scrollable -->
      <nav class="flex-1 overflow-y-auto py-3 px-3 space-y-4">
        <div v-for="group in effectiveNavGroups" :key="group.label" class="space-y-0.5">
          <p class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ group.label }}</p>
          <Link
            v-for="item in group.items"
            :key="item.href"
            :href="item.href"
            :class="isActive(item.href)
              ? 'bg-indigo-600 text-white font-semibold shadow-sm shadow-indigo-600/20'
              : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium'"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition duration-150 group"
          >
            <svg
              class="w-4 h-4 shrink-0"
              :class="isActive(item.href) ? 'text-white' : 'text-slate-400 group-hover:text-slate-700'"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="1.75"
            >
              <path stroke-linecap="round" stroke-linejoin="round" :d="NAV_ICONS[item.icon] || NAV_ICONS.grid" />
            </svg>
            <span class="truncate text-xs">{{ item.label }}</span>
            <span
              v-if="item.badge"
              :class="item.isDangerBadge
                ? 'bg-rose-500 text-white font-bold shadow-xs animate-pulse'
                : (isActive(item.href) ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800 border border-amber-200')"
              class="ml-auto px-2 py-0.5 rounded-full text-[10px] font-bold"
            >
              {{ item.badge }}
            </span>
          </Link>
        </div>
      </nav>

      <!-- User card + logout � pinned at bottom -->
      <div class="shrink-0 border-t border-slate-100 p-3">
        <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-slate-50 border border-slate-200/70 mb-2">
          <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-xs">
            {{ initials }}
          </div>
          <div class="min-w-0 flex-1">
            <p class="text-xs font-bold text-slate-900 truncate leading-tight">{{ user.name }}</p>
            <p class="text-[11px] text-slate-500 truncate">{{ user.email }}</p>
          </div>
        </div>
        <button
          type="button"
          @click="showLogoutModal = true"
          class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-white hover:bg-rose-50 text-rose-600 border border-slate-200 hover:border-rose-200 font-semibold text-xs transition duration-150 shadow-2xs cursor-pointer"
        >
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Sign Out
        </button>
      </div>
    </aside>

    <!-- Main content container -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen">
      <!-- Top navbar -->
      <header class="sticky top-0 z-20 bg-white/95 backdrop-blur-md border-b border-slate-200/80 h-14 flex items-center justify-between px-4 sm:px-6 shadow-2xs shrink-0">
        <div class="flex items-center gap-3">
          <!-- Mobile sidebar toggle -->
          <button
            @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden p-2 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
          >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

          <!-- Breadcrumbs -->
          <div class="flex items-center gap-2 text-xs text-slate-500">
            <span class="font-bold text-slate-800">KidaBoard</span>
            <span class="text-slate-300">/</span>
            <span class="font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">{{ pageTitle }}</span>
          </div>
        </div>

        <!-- Flash alert -->
        <transition name="fade">
          <div v-if="flash.success" class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold shadow-xs">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping shrink-0"></span>
            <span class="truncate max-w-[220px] sm:max-w-md">{{ flash.success }}</span>
          </div>
          <div v-else-if="flash.error" class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold shadow-xs">
            <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping shrink-0"></span>
            <span class="truncate max-w-[220px] sm:max-w-md">{{ flash.error }}</span>
          </div>
        </transition>

        <!-- Right action tools -->
        <div class="flex items-center gap-2.5">
          <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-100/80 border border-slate-200/80 text-xs font-medium text-slate-600">
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Kidapawan City, PH</span>
          </div>

          <!-- Quick link for students to explore -->
          <Link
            v-if="role === 'student'"
            href="/student/explore"
            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition"
          >
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Find Houses
          </Link>

          <!-- Quick link for owners to manage rooms -->
          <Link
            v-if="role === 'owner'"
            href="/owner/rooms"
            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition"
          >
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Manage Rooms
          </Link>
        </div>
      </header>

      <!-- Main body slot -->
      <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
        <slot />
      </main>

      <!-- App Bottom Legal Footer -->
      <footer class="mt-auto border-t border-slate-200/60 py-4 px-6 text-[11px] text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-2">
        <span>&copy; 2026 KidaBoard • Kidapawan City Student Housing</span>
        <div class="flex items-center gap-3 font-medium">
          <a href="/privacy-policy" target="_blank" class="hover:text-indigo-600 transition">Privacy Notice</a>
          <span>•</span>
          <a href="/terms" target="_blank" class="hover:text-indigo-600 transition">Terms</a>
          <span>•</span>
          <a href="/refund-policy" target="_blank" class="hover:text-indigo-600 transition">Refunds</a>
          <span>•</span>
          <a href="/cookie-policy" target="_blank" class="hover:text-indigo-600 transition">Cookies</a>
        </div>
      </footer>
    </div>

    <!-- Confirm Sign Out Modal -->
    <ConfirmModal
      :show="showLogoutModal"
      title="Sign Out of Account"
      message="Are you sure you want to sign out of your session? You will need to log back in to access your dashboard."
      confirm-text="Log Out"
      cancel-text="Stay"
      variant="danger"
      icon-type="logout"
      @confirm="performLogout"
      @cancel="showLogoutModal = false"
      @update:show="showLogoutModal = $event"
    />

    <!-- Cookie Consent Banner -->
    <CookieConsent />

    <!-- Live Real-Time Chat Drawer & Auto-Pop WebSockets Notification -->
    <ChatDrawer v-if="user?.id" />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import CookieConsent from '@/Components/CookieConsent.vue';
import ChatDrawer from '@/Components/ChatDrawer.vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import { getNavigationForRole, NAV_ICONS } from '@/Config/navigation.js';
import ConfirmModal from '@/Components/ConfirmModal.vue';

const props = defineProps({
  pageTitle: { type: String, default: 'Dashboard' },
});

const sidebarOpen = ref(false);
const showLogoutModal = ref(false);
const page = usePage();
const user = computed(() => page.props.auth?.user ?? {});
const role = computed(() => user.value?.role ?? 'student');
const flash = computed(() => page.props.flash ?? {});
const initials = computed(() => {
  if (!user.value?.name) return 'U';
  return user.value.name.split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase();
});

const pendingCounts = computed(() => page.props.pending_counts ?? { appointments: 0, reservations: 0 });

// Single Source of Truth: Navigation hierarchy with dynamic danger badge counters for owner
const effectiveNavGroups = computed(() => {
  const groups = getNavigationForRole(role.value);
  if (role.value === 'owner') {
    return groups.map(g => ({
      ...g,
      items: g.items.map(item => {
        let badge = item.badge;
        let isDangerBadge = false;
        if (item.href === '/owner/appointments' && pendingCounts.value.appointments > 0) {
          badge = pendingCounts.value.appointments;
          isDangerBadge = true;
        } else if (item.href === '/owner/reservations' && pendingCounts.value.reservations > 0) {
          badge = pendingCounts.value.reservations;
          isDangerBadge = true;
        }
        return { ...item, badge, isDangerBadge };
      }),
    }));
  }
  return groups;
});

function isActive(href) {
  if (!href) return false;
  const path = window.location.pathname;
  if (href.endsWith('/dashboard')) return path === href;
  return path === href || (href !== '/' && path.startsWith(href));
}

function performLogout() {
  router.post('/logout');
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
