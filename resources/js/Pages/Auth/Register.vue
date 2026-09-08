<template>
  <div class="min-h-screen bg-slate-50 flex items-center justify-center p-4 sm:p-6 relative overflow-hidden">
    <!-- Soft background shapes -->
    <div class="absolute top-1/4 left-1/3 w-96 h-96 bg-indigo-100/60 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-72 h-72 bg-blue-100/50 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-lg relative z-10">
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
        <h2 class="text-xl font-bold text-slate-900 mb-1">Create Account</h2>
        <p class="text-xs text-slate-500 mb-6">Join KidaBoard and find your perfect boarding house in Kidapawan City</p>

        <!-- Role Selector -->
        <div class="grid grid-cols-2 gap-2 bg-slate-100 p-1.5 rounded-2xl border border-slate-200 mb-6">
          <button
            v-for="r in ['student', 'owner']"
            :key="r"
            type="button"
            @click="form.role = r"
            :class="form.role === r ? 'bg-white text-indigo-700 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
            class="py-2.5 rounded-xl text-xs capitalize transition duration-150"
          >
            <span>{{ r === 'student' ? 'Student' : 'Property Owner' }}</span>
          </button>
        </div>

        <!-- Flash Error -->
        <div v-if="Object.keys(errors).length" class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium">
          <p v-for="(err, key) in errors" :key="key">{{ Array.isArray(err) ? err[0] : err }}</p>
        </div>

        <form @submit.prevent="submit" class="space-y-4" aria-label="Registration Form">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="register-name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Full Name *</label>
              <input
                id="register-name"
                v-model="form.name"
                type="text"
                required
                autocomplete="name"
                aria-required="true"
                placeholder="Juan Dela Cruz"
                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
              />
            </div>
            <div>
              <label for="register-phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Phone Number</label>
              <input
                id="register-phone"
                v-model="form.phone"
                type="tel"
                autocomplete="tel"
                placeholder="09XX XXX XXXX"
                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
              />
            </div>
          </div>

          <div>
            <label for="register-email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Address *</label>
            <input
              id="register-email"
              v-model="form.email"
              type="email"
              required
              autocomplete="email"
              aria-required="true"
              placeholder="student@kidapawan.edu.ph"
              class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
            />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="register-password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password *</label>
              <input
                id="register-password"
                v-model="form.password"
                type="password"
                required
                autocomplete="new-password"
                aria-required="true"
                placeholder="Min. 8 characters"
                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
              />
            </div>
            <div>
              <label for="register-password-confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Confirm Password *</label>
              <input
                id="register-password-confirmation"
                v-model="form.password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                aria-required="true"
                placeholder="Repeat password"
                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
              />
            </div>
          </div>

          <!-- Explicit Consent Checkbox -->
          <div class="pt-1">
            <label for="register-terms" class="flex items-start gap-2.5 cursor-pointer text-xs text-slate-600 leading-snug select-none">
              <input
                id="register-terms"
                type="checkbox"
                v-model="form.terms"
                required
                aria-required="true"
                class="mt-0.5 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 transition shrink-0"
              />
              <span>
                I agree to the <a href="/terms" target="_blank" class="text-indigo-600 font-bold hover:underline">Terms &amp; Conditions</a> and acknowledge the <a href="/privacy-policy" target="_blank" class="text-indigo-600 font-bold hover:underline">Privacy Policy</a> (RA 10173).
              </span>
            </label>
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-500/25 transition duration-200 disabled:opacity-60 mt-2 flex items-center justify-center gap-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 cursor-pointer"
          >
            <span>{{ loading ? 'Creating Account...' : 'Create My Account' }}</span>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </button>
        </form>

        <p class="text-center text-xs text-slate-600 mt-5 font-medium">
          Already have an account?
          <a href="/login" class="text-indigo-600 hover:text-indigo-700 font-bold transition">Sign In</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({ errors: { type: Object, default: () => ({}) } });
const loading = ref(false);

const form = reactive({
  name: '',
  email: '',
  phone: '',
  role: 'student',
  password: '',
  password_confirmation: '',
  terms: false,
});

function submit() {
  loading.value = true;
  router.post('/register', form, {
    onFinish: () => (loading.value = false),
  });
}
</script>
