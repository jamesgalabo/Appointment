<template>
  <AppLayout page-title="My Profile">
    <div class="mb-6">
      <h1 class="text-2xl font-black text-slate-900 tracking-tight">Student Profile Settings</h1>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Manage your student profile, contact numbers, and login credentials</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Profile Form -->
      <div class="lg:col-span-2 bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/80 shadow-xs">
        <form @submit.prevent="submit" class="space-y-4">
          <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Personal Information
          </h3>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Full Name *</label>
            <input
              v-model="form.name"
              type="text"
              required
              class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
            <input
              :value="user.email"
              type="email"
              disabled
              class="w-full px-4 py-2.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-500 text-xs cursor-not-allowed"
            />
            <p class="text-[11px] text-slate-400 mt-1">Email is tied to your student account login.</p>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Mobile Contact Number *</label>
            <input
              v-model="form.phone"
              type="tel"
              placeholder="09XX XXX XXXX"
              class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
            />
            <p class="text-[11px] text-slate-400 mt-1">Boarding house owners will use this phone number to confirm your viewings.</p>
          </div>

          <div class="pt-3 border-t border-slate-100">
            <button
              type="submit"
              :disabled="loading"
              class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition disabled:opacity-60"
            >
              {{ loading ? 'Saving Profile...' : 'Save Profile Changes' }}
            </button>
          </div>
        </form>
      </div>

      <!-- Account Details Card -->
      <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs h-fit space-y-4">
        <h3 class="font-extrabold text-slate-900 text-sm">Account Status</h3>
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-3 text-xs">
          <div class="flex items-center justify-between">
            <span class="text-slate-500 font-medium">Account Role</span>
            <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold border border-emerald-200 capitalize">
              {{ user.role }}
            </span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-slate-500 font-medium">Account ID</span>
            <span class="font-mono font-bold text-slate-900">#{{ user.id }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-slate-500 font-medium">Kidapawan City Portal</span>
            <span class="font-bold text-emerald-600">Active</span>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ user: Object });
const loading = ref(false);

const form = reactive({
  name: props.user?.name ?? '',
  phone: props.user?.phone ?? '',
});

function submit() {
  loading.value = true;
  router.patch('/student/profile', form, {
    onFinish: () => (loading.value = false),
  });
}
</script>
