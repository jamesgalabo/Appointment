<template>
  <transition
    enter-active-class="transition duration-300 ease-out"
    enter-from-class="transform translate-y-full opacity-0"
    enter-to-class="transform translate-y-0 opacity-100"
    leave-active-class="transition duration-200 ease-in"
    leave-from-class="transform translate-y-0 opacity-100"
    leave-to-class="transform translate-y-full opacity-0"
  >
    <div
      v-if="showBanner"
      role="region"
      aria-label="Cookie and Privacy Preferences"
      class="fixed bottom-0 inset-x-0 z-50 p-4 sm:p-6 pointer-events-none"
    >
      <div class="max-w-4xl mx-auto bg-white/95 backdrop-blur-md rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-2xl p-4 sm:p-6 pointer-events-auto ring-1 ring-black/5">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
          
          <!-- Text Info -->
          <div class="flex items-start gap-3.5 flex-1">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 shadow-2xs">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </div>
            <div class="space-y-1">
              <div class="flex items-center gap-2">
                <h3 class="text-sm font-black text-slate-900">Privacy & Cookie Settings</h3>
                <span class="text-[10px] uppercase font-extrabold px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-200">Kidapawan</span>
              </div>
              <p class="text-xs text-slate-600 leading-relaxed max-w-2xl">
                We use strictly necessary cookies to keep you safely logged in and protect against CSRF attacks. With your consent, we also gather anonymous site performance metrics to improve our room reservation experience.
              </p>
              <div class="flex items-center gap-3 pt-0.5">
                <a
                  href="/cookie-policy"
                  class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 underline transition"
                >
                  Read Cookie Policy ↗
                </a>
                <span class="text-slate-300">•</span>
                <a
                  href="/privacy-policy"
                  class="text-[11px] font-bold text-slate-500 hover:text-slate-800 transition"
                >
                  Privacy Notice (RA 10173)
                </a>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full md:w-auto shrink-0">
            <button
              type="button"
              @click="acceptEssentialOnly"
              class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 cursor-pointer"
            >
              Essential Only
            </button>
            <button
              type="button"
              @click="acceptAll"
              class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 cursor-pointer"
            >
              Accept All
            </button>
          </div>

        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const STORAGE_KEY = 'kidaboard_cookie_consent';
const showBanner = ref(false);

onMounted(() => {
  const stored = localStorage.getItem(STORAGE_KEY);
  if (!stored) {
    // Show banner after brief delay to avoid blocking initial paint
    setTimeout(() => {
      showBanner.value = true;
    }, 600);
  }

  window.addEventListener('kidaboard:reset-cookies', handleReset);
});

onUnmounted(() => {
  window.removeEventListener('kidaboard:reset-cookies', handleReset);
});

function handleReset() {
  showBanner.value = true;
}

function saveConsent(analyticsEnabled) {
  const consent = {
    essential: true,
    analytics: analyticsEnabled,
    timestamp: new Date().toISOString(),
  };
  localStorage.setItem(STORAGE_KEY, JSON.stringify(consent));
  showBanner.value = false;

  // Dispatch custom event for any analytics scripts
  window.dispatchEvent(
    new CustomEvent('kidaboard:consent-updated', {
      detail: consent,
    })
  );
}

function acceptEssentialOnly() {
  saveConsent(false);
}

function acceptAll() {
  saveConsent(true);
}
</script>
