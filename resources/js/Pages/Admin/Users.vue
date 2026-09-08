<template>
  <AppLayout page-title="Platform Users">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Platform Users</h1>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Manage registered students, property owners, and administrators</p>
      </div>

      <div class="flex items-center gap-1.5 p-1 bg-white border border-slate-200/80 rounded-xl shadow-2xs">
        <button
          v-for="r in ['all', 'student', 'owner', 'admin']"
          :key="r"
          @click="filterRole = r"
          :class="filterRole === r ? 'bg-indigo-600 text-white font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
          class="px-3 py-1.5 rounded-lg text-xs capitalize transition"
        >
          {{ r }}
        </button>
      </div>
    </div>

    <!-- Users Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs">
      <div class="overflow-x-auto">
        <table class="w-full text-xs">
          <thead>
            <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider">
              <th class="px-5 py-3.5 text-left font-bold">User</th>
              <th class="px-5 py-3.5 text-left font-bold">Email</th>
              <th class="px-5 py-3.5 text-left font-bold">Mobile Phone</th>
              <th class="px-5 py-3.5 text-left font-bold">Account Role</th>
              <th class="px-5 py-3.5 text-right font-bold">Joined Date</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-slate-50/80 transition">
              <td class="px-5 py-3.5">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-2xs">
                    {{ user.name.charAt(0).toUpperCase() }}
                  </div>
                  <div>
                    <span class="font-bold text-slate-900">{{ user.name }}</span>
                    <span class="text-[10px] text-slate-400 block font-mono">ID #{{ user.id }}</span>
                  </div>
                </div>
              </td>
              <td class="px-5 py-3.5 text-slate-600 font-medium">{{ user.email }}</td>
              <td class="px-5 py-3.5 text-slate-500">{{ user.phone ?? '—' }}</td>
              <td class="px-5 py-3.5">
                <span
                  :class="roleColors[user.role]"
                  class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize"
                >
                  {{ user.role }}
                </span>
              </td>
              <td class="px-5 py-3.5 text-right text-slate-500 font-medium">
                {{ new Date(user.created_at).toLocaleDateString() }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ users: Object });
const userList = computed(() => props.users?.data ?? props.users ?? []);
const filterRole = ref('all');

const filteredUsers = computed(() => {
  if (filterRole.value === 'all') return userList.value;
  return userList.value.filter(u => u.role === filterRole.value);
});

const roleColors = {
  admin: 'bg-rose-50 text-rose-700 border-rose-200',
  owner: 'bg-indigo-50 text-indigo-700 border-indigo-200',
  student: 'bg-emerald-50 text-emerald-700 border-emerald-200',
};
</script>
