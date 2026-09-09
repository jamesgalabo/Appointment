<template>
  <AppLayout page-title="Messages & Communication">
    <div class="h-[calc(100vh-10rem)] min-h-[500px] bg-white rounded-3xl border border-slate-200/80 shadow-xs flex overflow-hidden">
      <!-- Left: Conversations List (1/3 width on desktop) -->
      <div class="w-full md:w-80 lg:w-96 border-r border-slate-200/80 flex flex-col shrink-0 bg-slate-50/50">
        <!-- Header -->
        <div class="p-4 bg-white border-b border-slate-100 flex items-center justify-between">
          <div>
            <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Conversations</h2>
            <p class="text-xs text-slate-500 font-medium">Real-time chats with {{ role === 'owner' ? 'students' : 'boarding house owners' }}</p>
          </div>
          <span
            v-if="totalUnread > 0"
            class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-indigo-100 text-indigo-700"
          >
            {{ totalUnread }} unread
          </span>
        </div>

        <!-- Search -->
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

        <!-- List -->
        <div class="flex-1 overflow-y-auto divide-y divide-slate-100">
          <div v-if="loadingConversations" class="p-8 text-center text-xs text-slate-400 flex items-center justify-center gap-2">
            <svg class="w-4 h-4 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Loading chats...
          </div>

          <div v-else-if="filteredConversations.length === 0" class="p-8 text-center">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center mx-auto mb-2">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
              </svg>
            </div>
            <p class="text-xs font-bold text-slate-800">No conversations</p>
            <p class="text-[11px] text-slate-400 mt-1">Inquiries from listings or bookings will appear here.</p>
          </div>

          <button
            v-else
            v-for="conv in filteredConversations"
            :key="conv.id"
            type="button"
            @click="selectConversation(conv.id)"
            class="w-full p-4 flex items-start gap-3 text-left transition duration-150 cursor-pointer"
            :class="selectedConvId === conv.id ? 'bg-indigo-50/70 border-r-2 border-indigo-600' : 'hover:bg-white'"
          >
            <div class="relative shrink-0">
              <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold text-sm shadow-xs">
                {{ conv.other_user?.name ? conv.other_user.name.charAt(0).toUpperCase() : '?' }}
              </div>
              <span
                v-if="conv.unread_count > 0"
                class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-rose-500 border-2 border-white"
              />
            </div>

            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between gap-1">
                <span class="text-xs font-bold text-slate-900 truncate">
                  {{ conv.other_user?.name || 'User' }}
                </span>
                <span class="text-[10px] text-slate-400 shrink-0">
                  {{ conv.latest_message?.formatted_time || '' }}
                </span>
              </div>

              <p v-if="conv.boarding_house?.name" class="text-[11px] font-semibold text-indigo-600 truncate mt-0.5">
                🏠 {{ conv.boarding_house.name }}
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

      <!-- Right: Active Chat Area (2/3 width) -->
      <div class="hidden md:flex flex-1 flex-col min-w-0 bg-slate-50">
        <!-- Selected conversation thread -->
        <template v-if="activeConversation">
          <!-- Top bar -->
          <div class="px-6 py-4 bg-white border-b border-slate-100 flex items-center justify-between shrink-0 shadow-2xs">
            <div class="flex items-center gap-3 min-w-0">
              <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white flex items-center justify-center font-bold text-sm shadow-xs shrink-0">
                {{ activeConversation.other_user?.name?.charAt(0).toUpperCase() || '?' }}
              </div>
              <div class="min-w-0">
                <div class="flex items-center gap-2">
                  <h3 class="text-sm font-bold text-slate-900 truncate">{{ activeConversation.other_user?.name }}</h3>
                  <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-indigo-50 text-indigo-700">
                    {{ activeConversation.other_user?.role }}
                  </span>
                </div>
                <p v-if="activeConversation.boarding_house" class="text-xs text-indigo-600 font-semibold truncate mt-0.5">
                  Regarding: {{ activeConversation.boarding_house.name }} ({{ activeConversation.boarding_house.address }})
                </p>
              </div>
            </div>

            <div class="flex items-center gap-1.5 text-xs text-emerald-600 font-semibold bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200/60">
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
              <span>Reverb WebSockets Active</span>
            </div>
          </div>

          <!-- Messages Container -->
          <div
            ref="messagesContainer"
            class="flex-1 overflow-y-auto p-6 space-y-4"
          >
            <div v-if="loadingMessages" class="flex items-center justify-center h-full text-xs text-slate-400">
              <svg class="w-4 h-4 animate-spin text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Loading messages...
            </div>

            <div v-else-if="messages.length === 0" class="flex flex-col items-center justify-center h-full text-center py-12">
              <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
              </div>
              <p class="text-sm font-bold text-slate-800">No messages yet in this conversation</p>
              <p class="text-xs text-slate-400 mt-1 max-w-sm">
                Say hello, ask about room vacancies, rent policies, or move-in requirements.
              </p>
            </div>

            <!-- Messages list -->
            <div
              v-for="msg in messages"
              :key="msg.id"
              class="flex flex-col"
              :class="msg.sender_id === authUserId ? 'items-end' : 'items-start'"
            >
              <div class="flex items-end gap-2 max-w-[70%]">
                <!-- Received -->
                <div
                  v-if="msg.sender_id !== authUserId"
                  class="bg-white text-slate-800 border border-slate-200/80 rounded-2xl rounded-bl-xs px-4 py-2.5 text-xs shadow-xs leading-relaxed"
                >
                  <p class="text-[10px] font-bold text-indigo-600 mb-0.5">{{ msg.sender_name }}</p>
                  <p class="whitespace-pre-wrap break-words text-slate-800">{{ msg.body }}</p>
                  <p class="text-[10px] text-slate-400 text-right mt-1.5">{{ msg.formatted_time }}</p>
                </div>

                <!-- Sent -->
                <div
                  v-else
                  class="bg-gradient-to-tr from-indigo-600 to-indigo-700 text-white rounded-2xl rounded-br-xs px-4 py-2.5 text-xs shadow-xs leading-relaxed"
                >
                  <p class="whitespace-pre-wrap break-words">{{ msg.body }}</p>
                  <div class="flex items-center justify-end gap-1 mt-1.5 text-indigo-200 text-[10px]">
                    <span>{{ msg.formatted_time }}</span>
                    <span v-if="msg.is_read" title="Seen">✓✓</span>
                    <span v-else title="Delivered">✓</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Typing indicator -->
            <div v-if="isOtherUserTyping" class="flex items-start gap-2 pt-1">
              <div class="flex items-center gap-1.5 px-3.5 py-2 bg-white border border-slate-200 rounded-2xl rounded-bl-xs shadow-xs">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-bounce" style="animation-delay:0ms"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-bounce" style="animation-delay:150ms"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-bounce" style="animation-delay:300ms"></span>
              </div>
              <span class="text-[10px] text-slate-400 self-end mb-1">{{ activeConversation?.other_user?.name }} is typing...</span>
            </div>
          </div>

          <!-- Bottom input bar -->
          <form @submit.prevent="sendMessage" class="p-4 bg-white border-t border-slate-200/80 flex items-end gap-3 shrink-0">
            <textarea
              v-model="newMessageText"
              @input="handleTyping"
              @keydown.enter.exact.prevent="sendMessage"
              placeholder="Type your message... (Press Enter to send)"
              rows="1"
              class="flex-1 max-h-32 resize-none px-4 py-3 rounded-2xl bg-slate-100 border border-transparent focus:border-indigo-500 focus:bg-white text-xs text-slate-800 placeholder-slate-400 transition outline-none"
            />
            <button
              type="submit"
              :disabled="!newMessageText.trim() || sending"
              class="h-11 px-5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-sm shadow-indigo-600/20 transition cursor-pointer shrink-0"
            >
              <svg v-if="sending" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span v-else class="flex items-center gap-1.5">
                Send
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
              </span>
            </button>
          </form>
        </template>

        <!-- No conversation selected state -->
        <div v-else class="flex-1 flex flex-col items-center justify-center p-8 text-center">
          <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4 shadow-xs">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
          </div>
          <h3 class="text-base font-extrabold text-slate-800">Select a conversation</h3>
          <p class="text-xs text-slate-500 mt-1 max-w-sm">
            Choose a contact from the left pane to view message history or start chatting in real time.
          </p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

const page = usePage();
const authUser = computed(() => page.props.auth?.user ?? {});
const authUserId = computed(() => authUser.value?.id);
const role = computed(() => authUser.value?.role);

const conversations = ref([]);
const totalUnread = ref(0);
const searchQuery = ref('');
const loadingConversations = ref(false);

const selectedConvId = ref(null);
const activeConversation = ref(null);
const messages = ref([]);
const loadingMessages = ref(false);
const newMessageText = ref('');
const sending = ref(false);
const messagesContainer = ref(null);

const isOtherUserTyping = ref(false);
const typingTimeout = ref(null);
let currentPresenceChannel = null;
let lastWhisperedTime = 0;

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

async function fetchConversations() {
  loadingConversations.value = true;
  try {
    const res = await axios.get('/api/chat/conversations');
    conversations.value = res.data.conversations || [];
    totalUnread.value = res.data.total_unread || 0;

    // Auto-select first conversation if none selected
    if (!selectedConvId.value && conversations.value.length > 0) {
      selectConversation(conversations.value[0].id);
    }
  } catch (e) {
    console.error('Failed to load conversations:', e);
  } finally {
    loadingConversations.value = false;
  }
}

async function selectConversation(conversationId) {
  selectedConvId.value = conversationId;
  loadingMessages.value = true;
  try {
    const res = await axios.get(`/api/chat/conversations/${conversationId}/messages`);
    activeConversation.value = res.data.conversation;
    messages.value = res.data.messages || [];

    // Decrement unread
    const conv = conversations.value.find(c => c.id === conversationId);
    if (conv && conv.unread_count > 0) {
      totalUnread.value = Math.max(0, totalUnread.value - conv.unread_count);
      conv.unread_count = 0;
    }

    scrollToBottom();
    listenToConversation(conversationId);
    joinPresenceChannel(conversationId);
  } catch (e) {
    console.error('Failed to load messages:', e);
  } finally {
    loadingMessages.value = false;
  }
}

async function sendMessage() {
  const text = newMessageText.value.trim();
  if (!text || !selectedConvId.value || sending.value) return;

  const convId = selectedConvId.value;
  sending.value = true;
  newMessageText.value = '';

  const tempId = 'temp-' + Date.now();
  messages.value.push({
    id: tempId,
    conversation_id: convId,
    sender_id: authUserId.value,
    sender_name: authUser.value.name,
    body: text,
    is_read: false,
    formatted_time: 'Just now',
  });
  scrollToBottom();

  try {
    const res = await axios.post(`/api/chat/conversations/${convId}/messages`, {
      body: text,
    });
    const index = messages.value.findIndex(m => m.id === tempId);
    if (index !== -1 && res.data.message) {
      messages.value[index] = res.data.message;
    }
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

let activeChannelId = null;
function listenToConversation(convId) {
  if (typeof window === 'undefined' || !window.Echo) return;

  if (activeChannelId && activeChannelId !== convId) {
    window.Echo.leaveChannel(`kidaboard-conversation.${activeChannelId}`);
  }

  activeChannelId = convId;
  window.Echo.channel(`kidaboard-conversation.${convId}`)
    .listen('.message.sent', (data) => {
      if (selectedConvId.value === convId && data.sender_id !== authUserId.value) {
        messages.value.push(data);
        scrollToBottom();
        axios.post(`/api/chat/conversations/${convId}/read`).catch(() => {});
      }
    });
}

let currentPresenceConvId = null;
function joinPresenceChannel(convId) {
  if (typeof window === 'undefined' || !window.Echo) return;

  if (currentPresenceConvId && currentPresenceConvId !== convId) {
    window.Echo.leave(`presence-kidaboard-conv.${currentPresenceConvId}`);
  }

  currentPresenceConvId = convId;
  isOtherUserTyping.value = false;

  currentPresenceChannel = window.Echo.join(`presence-kidaboard-conv.${convId}`)
    .leaving((user) => {
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
    });
}

onMounted(() => {
  fetchConversations();
});

onUnmounted(() => {
  if (typeof window !== 'undefined' && window.Echo) {
    if (activeChannelId) {
      window.Echo.leaveChannel(`kidaboard-conversation.${activeChannelId}`);
    }
    if (currentPresenceConvId) {
      window.Echo.leave(`presence-kidaboard-conv.${currentPresenceConvId}`);
    }
  }
});
</script>
