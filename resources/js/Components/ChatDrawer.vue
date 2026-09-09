<template>
  <div>
    <!-- ======================================================== -->
    <!-- Booking Status Toast (approve/decline notifications)      -->
    <!-- ======================================================== -->
    <transition
      enter-active-class="transform ease-out duration-300 transition"
      enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
      enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
      leave-active-class="transition ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="bookingToast.show"
        class="fixed top-5 right-5 z-[60] max-w-sm w-full rounded-2xl shadow-2xl border p-4 flex items-start gap-3 pointer-events-auto cursor-pointer hover:brightness-[1.03] transition"
        :class="bookingToast.status === 'approved' || bookingToast.status === 'confirmed'
          ? 'bg-emerald-50 border-emerald-200'
          : bookingToast.status === 'cancelled' || bookingToast.status === 'declined' || bookingToast.status === 'rejected'
            ? 'bg-rose-50 border-rose-200'
            : 'bg-amber-50 border-amber-200'"
        @click="bookingToast.show = false"
      >
        <!-- Icon -->
        <div
          class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm shrink-0 shadow-md"
          :class="bookingToast.status === 'approved' || bookingToast.status === 'confirmed'
            ? 'bg-emerald-100 text-emerald-700 shadow-emerald-200'
            : bookingToast.status === 'cancelled' || bookingToast.status === 'declined' || bookingToast.status === 'rejected'
              ? 'bg-rose-100 text-rose-700 shadow-rose-200'
              : 'bg-amber-100 text-amber-700 shadow-amber-200'"
        >
          <svg v-if="bookingToast.status === 'approved' || bookingToast.status === 'confirmed'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <svg v-else-if="bookingToast.status === 'cancelled' || bookingToast.status === 'declined' || bookingToast.status === 'rejected'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <svg v-else class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>

        <div class="flex-1 min-w-0">
          <p class="text-xs font-extrabold truncate"
            :class="bookingToast.status === 'approved' || bookingToast.status === 'confirmed' ? 'text-emerald-800'
              : bookingToast.status === 'cancelled' || bookingToast.status === 'declined' || bookingToast.status === 'rejected' ? 'text-rose-800'
              : 'text-amber-800'">
            {{ bookingToast.title }}
          </p>
          <p class="text-[11px] text-slate-600 mt-0.5">{{ bookingToast.body }}</p>
          <span class="text-[10px] text-slate-400">Just now</span>
        </div>

        <button type="button" @click.stop="bookingToast.show = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg shrink-0">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>
    </transition>

    <!-- ======================================================== -->
    <!-- Floating Auto-Pop Message Toast (incoming messages)       -->
    <!-- ======================================================== -->
    <transition
      enter-active-class="transform ease-out duration-300 transition"
      enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
      enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
      leave-active-class="transition ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="autoPopToast.show"
        class="fixed top-20 right-5 z-50 max-w-sm w-full bg-white rounded-2xl shadow-2xl border border-indigo-100 p-4 flex items-start gap-3 pointer-events-auto cursor-pointer hover:border-indigo-300 transition"
        @click="openFromToast(autoPopToast.conversationId)"
      >
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-md shadow-indigo-500/20">
          {{ autoPopToast.senderName.charAt(0) }}
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center justify-between gap-1">
            <p class="text-xs font-bold text-slate-900 truncate">{{ autoPopToast.senderName }}</p>
            <span class="text-[10px] text-slate-400">Just now</span>
          </div>
          <p v-if="autoPopToast.houseName" class="text-[10px] font-semibold text-indigo-600 truncate">
            {{ autoPopToast.houseName }}
          </p>
          <p class="text-xs text-slate-600 line-clamp-2 mt-0.5">
            {{ autoPopToast.body }}
          </p>
          <div class="mt-2 flex items-center gap-2">
            <span class="text-[11px] font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
              Reply now &rarr;
            </span>
          </div>
        </div>
        <button
          type="button"
          @click.stop="autoPopToast.show = false"
          class="text-slate-400 hover:text-slate-600 p-1 rounded-lg"
        >
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </transition>

    <!-- ======================================================== -->
    <!-- Floating Chat Trigger Button (Bottom Right)              -->
    <!-- ======================================================== -->
    <!-- Hide the button when drawer is open on mobile (it would overlap the input bar) -->
    <div class="fixed bottom-6 right-6 z-40" :class="isOpen ? 'hidden sm:block' : ''">
      <button
        type="button"
        @click="toggleDrawer"
        class="group relative flex items-center gap-2.5 px-4 py-3 rounded-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold text-sm shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-105 active:scale-95 transition-all duration-200 cursor-pointer"
        aria-label="Open Chat"
      >
        <span class="relative flex items-center justify-center">
          <svg class="w-5 h-5 transition-transform group-hover:rotate-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
          </svg>
          <span
            v-if="totalUnread > 0"
            class="absolute -top-3 -right-3 min-w-5 h-5 px-1.5 rounded-full bg-rose-500 text-white font-extrabold text-[10px] flex items-center justify-center border-2 border-white shadow-xs animate-bounce"
          >
            {{ totalUnread > 99 ? '99+' : totalUnread }}
          </span>
        </span>
        <span class="hidden sm:inline-block tracking-tight text-xs font-bold">
          {{ isOpen ? 'Close Messages' : 'Live Messages' }}
        </span>
      </button>
    </div>

    <!-- Backdrop (Mobile only) -->
    <transition enter-active-class="transition-opacity duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition-opacity duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
      <div
        v-if="isOpen"
        @click="isOpen = false"
        class="fixed inset-0 bg-slate-900/30 backdrop-blur-2xs z-40 sm:hidden"
      />
    </transition>

    <!-- ======================================================== -->
    <!-- Slide-Out Chat Drawer Window                             -->
    <!-- ======================================================== -->
    <transition
      enter-active-class="transform transition ease-in-out duration-300"
      enter-from-class="translate-y-full sm:translate-y-0 sm:translate-x-full"
      enter-to-class="translate-y-0 sm:translate-x-0"
      leave-active-class="transform transition ease-in-out duration-300"
      leave-from-class="translate-y-0 sm:translate-x-0"
      leave-to-class="translate-y-full sm:translate-y-0 sm:translate-x-full"
    >
      <div
        v-if="isOpen"
        class="fixed bottom-0 right-0 sm:bottom-20 sm:right-6 z-50 w-full sm:w-[420px] h-[85vh] sm:h-[620px] max-h-[90vh] bg-white rounded-t-3xl sm:rounded-3xl shadow-2xl border border-slate-200/80 flex flex-col overflow-hidden text-slate-800"
      >
        <!-- Header -->
        <div class="px-4 py-3.5 bg-gradient-to-r from-indigo-600 via-indigo-700 to-violet-700 text-white flex items-center justify-between shrink-0 shadow-sm">
          <div class="flex items-center gap-2.5 min-w-0">
            <button
              v-if="activeConversation"
              type="button"
              @click="leaveConversation"
              class="p-1 rounded-lg hover:bg-white/10 transition text-white"
              title="Back to inbox"
            >
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <div class="min-w-0">
              <h3 class="text-sm font-bold truncate flex items-center gap-1.5 leading-tight">
                <span>{{ activeConversation ? (activeConversation.other_user?.name || 'Chat') : 'KidaBoard Messages' }}</span>
                <span v-if="activeConversation?.other_user?.role" class="px-1.5 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wide bg-white/20">
                  {{ activeConversation.other_user.role }}
                </span>
              </h3>
              <!-- Online Presence Indicator (only in active conversation) -->
              <p v-if="activeConversation" class="text-[11px] text-indigo-100 truncate mt-0.5 flex items-center gap-1.5">
                <span
                  class="w-2 h-2 rounded-full shrink-0 transition-colors duration-500"
                  :class="isOtherUserOnline ? 'bg-emerald-400 shadow-[0_0_6px_1px_rgba(52,211,153,0.7)] animate-pulse' : 'bg-slate-500'"
                ></span>
                <span>{{ isOtherUserOnline ? 'Online now' : 'Offline' }}</span>
              </p>
              <p v-else class="text-[11px] text-indigo-100 truncate mt-0.5 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Real-Time WebSocket Enabled</span>
              </p>
            </div>
          </div>

          <div class="flex items-center gap-1">
            <button
              type="button"
              @click="isOpen = false"
              class="p-1.5 rounded-lg hover:bg-white/10 text-white transition cursor-pointer"
              title="Close chat"
            >
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- ================================================== -->
        <!-- Body: Conversations List View                      -->
        <!-- ================================================== -->
        <div v-if="!activeConversation" class="flex-1 flex flex-col min-h-0 bg-slate-50">
          <!-- Search box -->
          <div class="p-3 bg-white border-b border-slate-100">
            <div class="relative">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search conversations..."
                class="w-full pl-9 pr-3 py-2 rounded-xl bg-slate-100 border-none text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition outline-none"
              />
              <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>

          <!-- Loading state -->
          <div v-if="loadingConversations" class="flex-1 flex items-center justify-center p-6 text-slate-400 text-xs">
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>Loading messages...</span>
            </div>
          </div>

          <!-- Empty state -->
          <div v-else-if="filteredConversations.length === 0" class="flex-1 flex flex-col items-center justify-center p-8 text-center">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center mb-3">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
              </svg>
            </div>
            <h4 class="text-sm font-bold text-slate-800">No conversations yet</h4>
            <p class="text-xs text-slate-500 mt-1 max-w-xs">
              Direct inquiries and messages with {{ role === 'owner' ? 'students' : 'boarding house owners' }} will appear here in real time.
            </p>
          </div>

          <!-- Conversations list -->
          <div v-else class="flex-1 overflow-y-auto divide-y divide-slate-100">
            <button
              v-for="conv in filteredConversations"
              :key="conv.id"
              type="button"
              @click="selectConversation(conv.id)"
              class="w-full p-3.5 flex items-start gap-3 text-left hover:bg-white transition duration-150 cursor-pointer group"
              :class="conv.unread_count > 0 ? 'bg-indigo-50/40' : ''"
            >
              <div class="relative shrink-0">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                  {{ conv.other_user?.name ? conv.other_user.name.charAt(0).toUpperCase() : '?' }}
                </div>
                <span
                  v-if="conv.unread_count > 0"
                  class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-rose-500 border-2 border-white"
                />
              </div>

              <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-1">
                  <span class="text-xs font-bold text-slate-900 truncate group-hover:text-indigo-600 transition">
                    {{ conv.other_user?.name || 'User' }}
                  </span>
                  <span class="text-[10px] text-slate-400 shrink-0">
                    {{ conv.latest_message?.formatted_time || '' }}
                  </span>
                </div>

                <p v-if="conv.boarding_house?.name" class="text-[10px] font-semibold text-indigo-600 truncate mt-0.5">
                  {{ conv.boarding_house.name }}
                </p>

                <p
                  class="text-xs truncate mt-1"
                  :class="conv.unread_count > 0 ? 'font-bold text-slate-900' : 'text-slate-500'"
                >
                  <span v-if="conv.latest_message?.sender_id === authUserId" class="text-slate-400 font-normal">You: </span>
                  {{ conv.latest_message?.body || 'Started a conversation' }}
                </p>
              </div>

              <span
                v-if="conv.unread_count > 0"
                class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-600 text-white shrink-0 self-center"
              >
                {{ conv.unread_count }}
              </span>
            </button>
          </div>
        </div>

        <!-- ================================================== -->
        <!-- Body: Active Chat Conversation Thread              -->
        <!-- ================================================== -->
        <div v-else class="flex-1 flex flex-col min-h-0 bg-slate-50">
          <!-- House Banner info strip -->
          <div
            v-if="activeConversation.boarding_house"
            class="px-3.5 py-2 bg-indigo-50/70 border-b border-indigo-100/70 flex items-center justify-between text-[11px]"
          >
            <div class="flex items-center gap-1.5 text-indigo-900 font-medium truncate">
              <span class="text-indigo-600 font-bold">Regarding:</span>
              <span class="truncate font-semibold">{{ activeConversation.boarding_house.name }}</span>
            </div>
            <span class="text-[10px] text-slate-500 shrink-0">{{ activeConversation.boarding_house.address }}</span>
          </div>

          <!-- Messages Scrollable Container -->
          <div
            ref="messagesContainer"
            class="flex-1 overflow-y-auto p-4 space-y-3"
          >
            <!-- Loading messages -->
            <div v-if="loadingMessages" class="flex items-center justify-center p-8 text-xs text-slate-400">
              <svg class="w-4 h-4 animate-spin text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>Loading messages...</span>
            </div>

            <!-- Empty thread -->
            <div v-else-if="messages.length === 0" class="flex flex-col items-center justify-center h-full text-center py-8">
              <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mb-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
              </div>
              <p class="text-xs font-bold text-slate-800">Send the first message</p>
              <p class="text-[11px] text-slate-400 mt-0.5">Ask questions about rooms, move-in requirements, or schedules.</p>
            </div>

            <!-- Message Bubbles -->
            <div
              v-for="msg in messages"
              :key="msg.id"
              class="flex flex-col"
              :class="msg.sender_id === authUserId ? 'items-end' : 'items-start'"
            >
              <div class="flex items-end gap-1.5 max-w-[82%]">
                <!-- Received Bubble -->
                <div
                  v-if="msg.sender_id !== authUserId"
                  class="bg-white text-slate-800 border border-slate-200/80 rounded-2xl rounded-bl-xs px-3.5 py-2 text-xs shadow-xs leading-relaxed"
                >
                  <p class="text-[10px] font-bold text-indigo-600 mb-0.5">{{ msg.sender_name }}</p>
                  <p class="whitespace-pre-wrap break-words">{{ msg.body }}</p>
                  <p class="text-[9px] text-slate-400 text-right mt-1">{{ msg.formatted_time }}</p>
                </div>

                <!-- Sent Bubble -->
                <div
                  v-else
                  class="bg-gradient-to-tr from-indigo-600 to-indigo-700 text-white rounded-2xl rounded-br-xs px-3.5 py-2 text-xs shadow-xs leading-relaxed"
                >
                  <p class="whitespace-pre-wrap break-words">{{ msg.body }}</p>
                  <div class="flex items-center justify-end gap-1 mt-1 text-indigo-200 text-[9px]">
                    <span>{{ msg.formatted_time }}</span>
                    <span v-if="msg.is_read" title="Seen">✓✓</span>
                    <span v-else title="Delivered">✓</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Typing indicator -->
            <div v-if="isOtherUserTyping" class="flex items-start gap-2">
              <div class="flex items-center gap-1.5 px-3.5 py-2 bg-white border border-slate-200 rounded-2xl rounded-bl-xs shadow-xs">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-bounce" style="animation-delay:0ms"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-bounce" style="animation-delay:150ms"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-bounce" style="animation-delay:300ms"></span>
              </div>
              <span class="text-[10px] text-slate-400 self-end mb-1">{{ activeConversation.other_user?.name }} is typing...</span>
            </div>
          </div>

          <!-- Message Input Bar -->
          <form @submit.prevent="sendMessage" class="p-3 bg-white border-t border-slate-200/80 flex items-end gap-2 shrink-0">
            <textarea
              v-model="newMessageText"
              @input="handleTyping"
              @keydown.enter.exact.prevent="sendMessage"
              placeholder="Type your message... (Enter to send)"
              rows="1"
              class="flex-1 max-h-24 resize-none px-3.5 py-2 rounded-xl bg-slate-100 border border-transparent focus:border-indigo-500 focus:bg-white focus:ring-0 text-xs text-slate-800 placeholder-slate-400 transition outline-none"
            />
            <button
              type="submit"
              :disabled="!newMessageText.trim() || sending"
              class="h-9 px-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-bold text-xs flex items-center justify-center gap-1 shadow-xs transition cursor-pointer shrink-0"
            >
              <svg v-if="sending" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
              </svg>
            </button>
          </form>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

const page = usePage();
const authUser = computed(() => page.props.auth?.user ?? {});
const authUserId = computed(() => authUser.value?.id);
const role = computed(() => authUser.value?.role);

const isOpen = ref(false);
const conversations = ref([]);
const totalUnread = ref(0);
const searchQuery = ref('');
const loadingConversations = ref(false);

const activeConversation = ref(null);
const messages = ref([]);
const loadingMessages = ref(false);
const newMessageText = ref('');
const sending = ref(false);
const messagesContainer = ref(null);

// Online presence & typing tracking
const onlineUsersInConv = ref([]);
const isOtherUserTyping = ref(false);
const typingTimeout = ref(null);
let currentPresenceChannel = null;
let lastWhisperedTime = 0;

const isOtherUserOnline = computed(() => {
  if (!activeConversation.value?.other_user?.id) return false;
  return onlineUsersInConv.value.some(u => u.id === activeConversation.value.other_user.id);
});

function handleTyping() {
  const now = Date.now();
  if (now - lastWhisperedTime > 1500 && currentPresenceChannel && activeConversation.value) {
    lastWhisperedTime = now;
    try {
      currentPresenceChannel.whisper('typing', {
        userId: authUserId.value,
      });
    } catch (e) {}
  }
}

// Auto-Pop Message Toast Banner state
const autoPopToast = ref({
  show: false,
  conversationId: null,
  senderName: '',
  houseName: '',
  body: '',
  timer: null,
});

// Booking Status Toast (approve/decline notifications)
const bookingToast = ref({
  show: false,
  title: '',
  body: '',
  status: '',
  timer: null,
});

const filteredConversations = computed(() => {
  if (!searchQuery.value.trim()) return conversations.value;
  const q = searchQuery.value.toLowerCase();
  return conversations.value.filter(c => {
    const nameMatch = c.other_user?.name?.toLowerCase().includes(q);
    const houseMatch = c.boarding_house?.name?.toLowerCase().includes(q);
    const msgMatch = c.latest_message?.body?.toLowerCase().includes(q);
    return nameMatch || houseMatch || msgMatch;
  });
});

// ============================================================
// Drawer Controls
// ============================================================

function toggleDrawer() {
  isOpen.value = !isOpen.value;
  if (isOpen.value) {
    fetchConversations();
  }
}

// ============================================================
// Conversations & Messages API
// ============================================================

async function fetchConversations() {
  if (!authUserId.value) return;
  loadingConversations.value = true;
  try {
    const res = await axios.get('/api/chat/conversations');
    conversations.value = res.data.conversations || [];
    totalUnread.value = res.data.total_unread || 0;
  } catch (e) {
    console.error('Error loading conversations:', e);
  } finally {
    loadingConversations.value = false;
  }
}

async function selectConversation(conversationId) {
  loadingMessages.value = true;
  try {
    const res = await axios.get(`/api/chat/conversations/${conversationId}/messages`);
    activeConversation.value = res.data.conversation;
    messages.value = res.data.messages || [];

    // Decrement unread count locally
    const conv = conversations.value.find(c => c.id === conversationId);
    if (conv && conv.unread_count > 0) {
      totalUnread.value = Math.max(0, totalUnread.value - conv.unread_count);
      conv.unread_count = 0;
    }

    scrollToBottom();
    listenToConversation(conversationId);
    joinPresenceChannel(conversationId);
  } catch (e) {
    console.error('Error loading messages:', e);
  } finally {
    loadingMessages.value = false;
  }
}

function leaveConversation() {
  if (activeConversation.value?.id) {
    leavePresenceChannel(activeConversation.value.id);
  }
  activeConversation.value = null;
  messages.value = [];
  onlineUsersInConv.value = [];
}

async function sendMessage() {
  const text = newMessageText.value.trim();
  if (!text || !activeConversation.value || sending.value) return;

  const convId = activeConversation.value.id;
  sending.value = true;
  newMessageText.value = '';

  // Optimistic UI update: instantly show the bubble
  const tempId = 'temp-' + Date.now();
  const optimisticMsg = {
    id: tempId,
    conversation_id: convId,
    sender_id: authUserId.value,
    sender_name: authUser.value.name,
    body: text,
    is_read: false,
    formatted_time: 'Just now',
  };
  messages.value.push(optimisticMsg);
  scrollToBottom();

  try {
    const res = await axios.post(`/api/chat/conversations/${convId}/messages`, {
      body: text,
    });
    // Replace temp message with server response
    const index = messages.value.findIndex(m => m.id === tempId);
    if (index !== -1 && res.data.message) {
      messages.value[index] = res.data.message;
    }
    // Update latest message snippet in conversations list
    const conv = conversations.value.find(c => c.id === convId);
    if (conv) {
      conv.latest_message = {
        body: text,
        sender_id: authUserId.value,
        formatted_time: 'Just now',
      };
    }
  } catch (e) {
    console.error('Failed to send message:', e);
  } finally {
    sending.value = false;
    scrollToBottom();
  }
}

function scrollToBottom() {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
  });
}

// ============================================================
// Audio & Toast Notifications
// ============================================================

function playNotificationChime() {
  try {
    const AudioContext = window.AudioContext || window.webkitAudioContext;
    if (!AudioContext) return;
    const ctx = new AudioContext();
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();

    osc.type = 'sine';
    osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
    osc.frequency.setValueAtTime(880, ctx.currentTime + 0.1); // A5
    gain.gain.setValueAtTime(0.15, ctx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.35);

    osc.connect(gain);
    gain.connect(ctx.destination);
    osc.start();
    osc.stop(ctx.currentTime + 0.35);
  } catch (err) {
    // Ignore audio permission errors
  }
}

function showToastNotification(msg) {
  playNotificationChime();
  if (autoPopToast.value.timer) clearTimeout(autoPopToast.value.timer);

  autoPopToast.value = {
    show: true,
    conversationId: msg.conversation_id,
    senderName: msg.sender_name,
    houseName: msg.boarding_house_name,
    body: msg.body,
    timer: setTimeout(() => {
      autoPopToast.value.show = false;
    }, 6000),
  };
}

function showBookingToast(data) {
  playNotificationChime();
  if (bookingToast.value.timer) clearTimeout(bookingToast.value.timer);

  const type = data.type === 'appointment' ? 'Appointment' : 'Reservation';
  const statusLabels = {
    approved: 'Approved ✓',
    confirmed: 'Confirmed ✓',
    pending: 'Pending Review',
    cancelled: 'Cancelled',
    declined: 'Declined',
    rejected: 'Rejected',
  };
  const statusLabel = statusLabels[data.status] || data.status;

  const isPositive = data.status === 'approved' || data.status === 'confirmed';
  const isNegative = data.status === 'cancelled' || data.status === 'declined' || data.status === 'rejected';

  const reasonText = data.reason ? ` Reason: "${data.reason}"` : '';

  bookingToast.value = {
    show: true,
    title: `${type} ${statusLabel}`,
    body: isPositive
      ? `Your ${type.toLowerCase()} has been ${data.status}! Check your ${type.toLowerCase()}s for details.`
      : isNegative
        ? `Your ${type.toLowerCase()} was ${data.status}.${reasonText}`
        : `Your ${type.toLowerCase()} status has been updated to "${data.status}".${reasonText}`,
    status: data.status,
    timer: setTimeout(() => {
      bookingToast.value.show = false;
    }, 10000),
  };
}

function openFromToast(convId) {
  autoPopToast.value.show = false;
  isOpen.value = true;
  if (convId) {
    selectConversation(convId);
  }
}

// ============================================================
// Reverb WebSocket Channel Subscriptions
// ============================================================

// Listen to individual conversation channel (for real-time message delivery)
let currentConvChannel = null;
function listenToConversation(convId) {
  if (typeof window === 'undefined' || !window.Echo) return;

  if (currentConvChannel && currentConvChannel !== convId) {
    window.Echo.leaveChannel(`kidaboard-conversation.${currentConvChannel}`);
  }

  currentConvChannel = convId;
  window.Echo.channel(`kidaboard-conversation.${convId}`)
    .listen('.message.sent', (data) => {
      // If we are currently looking at this active conversation
      if (activeConversation.value?.id === convId) {
        if (data.sender_id !== authUserId.value) {
          messages.value.push(data);
          scrollToBottom();
          // Mark as read immediately on backend
          axios.post(`/api/chat/conversations/${convId}/read`).catch(() => {});
        }
      }
    });
}

// Presence channel: subscribe to track who is online & typing in a conversation
let currentPresenceConvId = null;
function joinPresenceChannel(convId) {
  if (typeof window === 'undefined' || !window.Echo) return;

  // Leave old presence channel if different conversation
  if (currentPresenceConvId && currentPresenceConvId !== convId) {
    window.Echo.leave(`presence-kidaboard-conv.${currentPresenceConvId}`);
  }

  currentPresenceConvId = convId;
  onlineUsersInConv.value = [];
  isOtherUserTyping.value = false;

  currentPresenceChannel = window.Echo.join(`presence-kidaboard-conv.${convId}`)
    .here((users) => {
      // Called immediately with list of currently online users
      onlineUsersInConv.value = users || [];
    })
    .joining((user) => {
      // Called when someone joins
      if (!onlineUsersInConv.value.find(u => u.id === user.id)) {
        onlineUsersInConv.value.push(user);
      }
    })
    .leaving((user) => {
      // Called when someone leaves
      onlineUsersInConv.value = onlineUsersInConv.value.filter(u => u.id !== user.id);
      if (activeConversation.value?.other_user?.id === user.id) {
        isOtherUserTyping.value = false;
      }
    })
    .listenForWhisper('typing', (e) => {
      if (activeConversation.value?.other_user?.id === e.userId) {
        isOtherUserTyping.value = true;
        if (typingTimeout.value) clearTimeout(typingTimeout.value);
        typingTimeout.value = setTimeout(() => {
          isOtherUserTyping.value = false;
        }, 3000);
      }
    })
    .error((error) => {
      console.warn('Presence channel error:', error);
    });
}

function leavePresenceChannel(convId) {
  if (typeof window === 'undefined' || !window.Echo) return;
  window.Echo.leave(`presence-kidaboard-conv.${convId}`);
  currentPresenceConvId = null;
  currentPresenceChannel = null;
  onlineUsersInConv.value = [];
  isOtherUserTyping.value = false;
  if (typingTimeout.value) clearTimeout(typingTimeout.value);
}

// Global user channel listener for real-time message toasts & booking status updates
function setupGlobalUserEcho() {
  if (typeof window === 'undefined' || !window.Echo || !authUserId.value) return;

  window.Echo.private(`kidaboard-user.${authUserId.value}`)
    .listen('.message.sent', (data) => {
      // If the chat drawer is closed OR active conversation is not this one:
      if (!isOpen.value || activeConversation.value?.id !== data.conversation_id) {
        totalUnread.value++;
        showToastNotification(data);
        // Refresh conversations list in background
        fetchConversations();
      }
    })
    .listen('.booking.updated', (data) => {
      // Real-time booking status notification for students
      showBookingToast(data);
    });
}

// Global window event listener to start chat from other pages (e.g. HouseDetail, Reservations, Tenants)
async function handleOpenChatEvent(event) {
  const detail = event.detail || {};
  isOpen.value = true;

  if (detail.conversationId) {
    await selectConversation(detail.conversationId);
    return;
  }

  if (detail.recipientId) {
    loadingConversations.value = true;
    try {
      const res = await axios.post('/api/chat/start', {
        recipient_id: detail.recipientId,
        boarding_house_id: detail.houseId || null,
        initial_message: detail.initialMessage || null,
      });

      await fetchConversations();
      await selectConversation(res.data.conversation_id);
    } catch (e) {
      console.error('Error starting conversation:', e);
    } finally {
      loadingConversations.value = false;
    }
  }
}

onMounted(() => {
  fetchConversations();
  setupGlobalUserEcho();
  if (typeof window !== 'undefined') {
    window.addEventListener('open-chat', handleOpenChatEvent);
  }
});

onUnmounted(() => {
  if (typeof window !== 'undefined') {
    window.removeEventListener('open-chat', handleOpenChatEvent);
    if (window.Echo && authUserId.value) {
      window.Echo.leaveChannel(`kidaboard-user.${authUserId.value}`);
      if (currentConvChannel) {
        window.Echo.leaveChannel(`kidaboard-conversation.${currentConvChannel}`);
      }
      if (currentPresenceConvId) {
        window.Echo.leave(`presence-kidaboard-conv.${currentPresenceConvId}`);
      }
    }
  }
});
</script>
