<template>
  <div class="min-h-screen bg-slate-50 flex items-center justify-center p-4 sm:p-6 relative overflow-hidden">
    <!-- Soft background shapes -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-100/60 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-80 h-80 bg-blue-100/50 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
      <!-- Logo -->
      <div class="flex items-center justify-center gap-3 mb-6">
        <a href="/" class="flex items-center gap-3 group">
          <div class="w-11 h-11 rounded-2xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-300">
            <span class="text-xl font-black text-white">KB</span>
          </div>
          <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kida<span class="text-indigo-600">Board</span></h1>
            <p class="text-xs text-slate-500 font-medium">Smart Student Accommodation</p>
          </div>
        </a>
      </div>

      <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-xl shadow-slate-200/50">
        <!-- Icon -->
        <div class="text-center mb-6">
          <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 mb-3">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
          <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Check Your Email</h2>
          <p class="text-sm text-slate-500 mt-1">
            We sent a 6-digit code to<br>
            <span class="font-semibold text-slate-700">{{ email }}</span>
          </p>
        </div>

        <!-- Success resent banner -->
        <div v-if="resent" class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium text-center">
          A new code has been sent to your email.
        </div>

        <!-- Error -->
        <div v-if="errors.otp" class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium">
          {{ errors.otp }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
          <!-- OTP input -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 text-center">Enter Verification Code</label>
            <div class="flex gap-2 justify-center">
              <input
                v-for="(_, i) in 6"
                :key="i"
                :ref="el => { if (el) inputs[i] = el }"
                v-model="digits[i]"
                type="text"
                inputmode="numeric"
                maxlength="1"
                @input="onInput(i)"
                @keydown="onKeydown(i, $event)"
                @paste="onPaste($event)"
                class="w-12 h-14 text-center text-xl font-bold rounded-xl border-2 bg-slate-50 text-slate-900 focus:outline-none focus:border-indigo-500 focus:bg-white transition"
                :class="digits[i] ? 'border-indigo-400 bg-indigo-50' : 'border-slate-200'"
              />
            </div>
            <!-- Hidden full OTP for form submit -->
            <input type="hidden" name="email" :value="email" />
          </div>

          <button
            type="submit"
            :disabled="loading || otp.length < 6"
            class="w-full py-3.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-500/25 transition duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg v-if="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span>{{ loading ? 'Verifying...' : 'Verify & Create Account' }}</span>
          </button>
        </form>

        <!-- Resend -->
        <div class="text-center mt-5">
          <p class="text-xs text-slate-500">
            Didn't receive the code?
            <button
              type="button"
              @click="resend"
              :disabled="resendCooldown > 0 || resendLoading"
              class="text-indigo-600 font-bold hover:text-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed ml-1"
            >
              {{ resendCooldown > 0 ? `Resend in ${resendCooldown}s` : 'Resend Code' }}
            </button>
          </p>
        </div>

        <p class="text-center text-xs text-slate-500 mt-4">
          Wrong email?
          <a href="/register" class="text-indigo-600 font-bold hover:text-indigo-700 transition">Go back to Register</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const props = defineProps({
  email: { type: String, default: '' },
  errors: { type: Object, default: () => ({}) },
});

const page = usePage();
const resent = computed(() => page.props.flash?.resent);

const digits = ref(['', '', '', '', '', '']);
const inputs = ref([]);
const loading = ref(false);
const resendLoading = ref(false);
const resendCooldown = ref(0);
let cooldownTimer = null;

const otp = computed(() => digits.value.join(''));

onMounted(() => {
  // Focus first input
  setTimeout(() => inputs.value[0]?.focus(), 100);
});

onUnmounted(() => {
  if (cooldownTimer) clearInterval(cooldownTimer);
});

function onInput(index) {
  const val = digits.value[index];
  // Only allow digits
  digits.value[index] = val.replace(/\D/g, '').slice(-1);

  if (digits.value[index] && index < 5) {
    inputs.value[index + 1]?.focus();
  }
}

function onKeydown(index, e) {
  if (e.key === 'Backspace' && !digits.value[index] && index > 0) {
    inputs.value[index - 1]?.focus();
  }
}

function onPaste(e) {
  e.preventDefault();
  const pasted = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
  pasted.split('').forEach((char, i) => {
    digits.value[i] = char;
  });
  const next = Math.min(pasted.length, 5);
  inputs.value[next]?.focus();
}

function submit() {
  if (otp.value.length < 6) return;
  loading.value = true;
  router.post('/verify-otp', {
    email: props.email,
    otp: otp.value,
  }, {
    onFinish: () => (loading.value = false),
  });
}

function resend() {
  resendLoading.value = true;
  router.post('/resend-otp', { email: props.email }, {
    onFinish: () => {
      resendLoading.value = false;
      // Start cooldown
      resendCooldown.value = 60;
      cooldownTimer = setInterval(() => {
        resendCooldown.value--;
        if (resendCooldown.value <= 0) clearInterval(cooldownTimer);
      }, 1000);
    },
  });
}
</script>
