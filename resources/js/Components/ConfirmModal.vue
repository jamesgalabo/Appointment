<template>
  <transition name="modal-fade">
    <div
      v-if="show"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm select-none"
      @click.self="onCancel"
    >
      <div
        class="bg-white rounded-2xl p-6 sm:p-7 max-w-sm w-full border border-slate-100 shadow-2xl shadow-slate-950/20 transform transition-all animate-in fade-in zoom-in-95 duration-200"
      >
        <!-- Icon Header -->
        <div class="mb-4">
          <div
            :class="iconBgClass"
            class="w-12 h-12 rounded-xl flex items-center justify-center transition-transform hover:scale-105"
          >
            <!-- SVG Icon depending on type/variant -->
            <svg v-if="iconType === 'logout'" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <svg v-else-if="iconType === 'danger' || variant === 'danger'" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <svg v-else-if="iconType === 'success' || variant === 'success'" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <svg v-else-if="iconType === 'warning' || variant === 'warning'" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <svg v-else class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>

        <!-- Title & Message -->
        <h3 class="text-base font-bold text-slate-900 tracking-tight mb-1.5">{{ title }}</h3>
        <p class="text-xs text-slate-500 leading-relaxed mb-6">{{ message }}</p>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-2.5">
          <button
            type="button"
            @click="onCancel"
            class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition cursor-pointer"
          >
            {{ cancelText }}
          </button>
          <button
            type="button"
            @click="onConfirm"
            :class="confirmBtnClass"
            class="px-5 py-2.5 rounded-xl text-white text-xs font-semibold transition shadow-sm hover:shadow active:scale-98 cursor-pointer flex items-center gap-1.5"
          >
            {{ confirmText }}
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  show: { type: Boolean, default: false },
  title: { type: String, default: 'Confirm Action' },
  message: { type: String, default: 'Are you sure you want to proceed with this action?' },
  confirmText: { type: String, default: 'Yes, Proceed' },
  cancelText: { type: String, default: 'Cancel' },
  variant: { type: String, default: 'primary' }, // 'primary', 'danger', 'success', 'warning'
  iconType: { type: String, default: 'info' }, // 'logout', 'danger', 'warning', 'success', 'info'
  icon: { type: String, default: '' }, // backwards compatibility fallback
});

const emit = defineEmits(['confirm', 'cancel', 'update:show']);

const iconBgClass = computed(() => {
  if (props.iconType === 'logout' || props.variant === 'danger') return 'bg-rose-50 text-rose-600 border border-rose-100';
  if (props.variant === 'success' || props.iconType === 'success') return 'bg-emerald-50 text-emerald-600 border border-emerald-100';
  if (props.variant === 'warning' || props.iconType === 'warning') return 'bg-amber-50 text-amber-600 border border-amber-100';
  return 'bg-indigo-50 text-indigo-600 border border-indigo-100';
});

const confirmBtnClass = computed(() => {
  if (props.iconType === 'logout' || props.variant === 'danger') return 'bg-rose-600 hover:bg-rose-700 shadow-rose-600/20';
  if (props.variant === 'success' || props.iconType === 'success') return 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/20';
  if (props.variant === 'warning' || props.iconType === 'warning') return 'bg-amber-600 hover:bg-amber-700 shadow-amber-600/20';
  return 'bg-slate-900 hover:bg-slate-800 shadow-slate-900/20';
});

function onConfirm() {
  emit('confirm');
  emit('update:show', false);
}

function onCancel() {
  emit('cancel');
  emit('update:show', false);
}
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
</style>

