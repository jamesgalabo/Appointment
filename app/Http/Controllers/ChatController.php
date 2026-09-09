<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\BoardingHouse;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    /**
     * Display the full messages page.
     */
    public function index(Request $request)
    {
        return Inertia::render('Messages/Index');
    }

    /**
     * Fetch user's conversations with latest message & unread count.
     */
    public function getConversations(Request $request): JsonResponse
    {
        $userId = auth()->id();

        $conversations = Conversation::query()
            ->where(function ($query) use ($userId) {
                $query->where('student_id', $userId)
                    ->orWhere('owner_id', $userId);
            })
            ->with([
                'student:id,name,email,role',
                'owner:id,name,email,role',
                'boardingHouse:id,name,address,city,thumbnail_url',
                'latestMessage.sender:id,name',
            ])
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get()
            ->map(function ($conversation) use ($userId) {
                $otherUser = $conversation->getOtherUser($userId);
                $unreadCount = Message::where('conversation_id', $conversation->id)
                    ->where('sender_id', '!=', $userId)
                    ->where('is_read', false)
                    ->count();

                return [
                    'id'                  => $conversation->id,
                    'boarding_house'      => $conversation->boardingHouse,
                    'other_user'          => $otherUser ? [
                        'id'   => $otherUser->id,
                        'name' => $otherUser->name,
                        'role' => $otherUser->role,
                    ] : null,
                    'latest_message'      => $conversation->latestMessage ? [
                        'id'             => $conversation->latestMessage->id,
                        'body'           => $conversation->latestMessage->body,
                        'sender_id'      => $conversation->latestMessage->sender_id,
                        'sender_name'    => $conversation->latestMessage->sender?->name,
                        'created_at'     => $conversation->latestMessage->created_at->toIso8601String(),
                        'formatted_time' => $conversation->latestMessage->created_at->diffForHumans(null, true, true),
                    ] : null,
                    'unread_count'        => $unreadCount,
                    'last_message_at'     => $conversation->last_message_at?->toIso8601String(),
                ];
            });

        return response()->json([
            'conversations' => $conversations,
            'total_unread'  => $conversations->sum('unread_count'),
        ]);
    }

    /**
     * Start a new conversation or retrieve an existing one.
     */
    public function startOrGetConversation(Request $request): JsonResponse
    {
        $data = $request->validate([
            'recipient_id'      => ['required', 'integer', 'exists:users,id'],
            'boarding_house_id' => ['nullable', 'integer', 'exists:boarding_houses,id'],
            'initial_message'   => ['nullable', 'string', 'max:2000'],
        ]);

        $auth = auth()->user();
        $recipient = User::findOrFail($data['recipient_id']);

        // Determine student and owner roles
        if ($auth->role === 'student' || ($recipient->role === 'owner' && $auth->role !== 'owner')) {
            $studentId = $auth->id;
            $ownerId = $recipient->id;
        } else {
            $studentId = $recipient->id;
            $ownerId = $auth->id;
        }

        $houseId = $data['boarding_house_id'] ?? null;
        if (! $houseId) {
            // Check if recipient has a boarding house
            $house = BoardingHouse::where('owner_id', $ownerId)->first();
            $houseId = $house?->id;
        }

        // Find or create conversation
        $conversation = Conversation::firstOrCreate(
            [
                'student_id'        => $studentId,
                'owner_id'          => $ownerId,
                'boarding_house_id' => $houseId,
            ],
            [
                'last_message_at'   => now(),
            ]
        );

        // If an initial message was passed (e.g. from HouseDetail inquiry button)
        if (! empty($data['initial_message'])) {
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id'       => $auth->id,
                'body'            => trim($data['initial_message']),
                'is_read'         => false,
            ]);

            $conversation->update(['last_message_at' => now()]);
            event(new MessageSent($message, $recipient->id));
        }

        return response()->json([
            'conversation_id' => $conversation->id,
            'conversation'    => [
                'id'             => $conversation->id,
                'boarding_house' => $conversation->boardingHouse,
                'other_user'     => [
                    'id'   => $recipient->id,
                    'name' => $recipient->name,
                    'role' => $recipient->role,
                ],
            ],
        ]);
    }

    /**
     * Get all messages for a conversation and mark incoming as read.
     */
    public function getMessages(Conversation $conversation): JsonResponse
    {
        $userId = (int) auth()->id();

        if ((int) $conversation->student_id !== $userId && (int) $conversation->owner_id !== $userId && auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized conversation access.'], 403);
        }

        // Mark unread messages from other user as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        $conversation->loadMissing(['boardingHouse', 'student:id,name,role', 'owner:id,name,role']);
        $otherUser = $conversation->getOtherUser($userId);

        $messages = Message::where('conversation_id', $conversation->id)
            ->with('sender:id,name,role')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id'             => $message->id,
                    'conversation_id'=> $message->conversation_id,
                    'sender_id'      => (int) $message->sender_id,
                    'sender_name'    => $message->sender?->name,
                    'sender_role'    => $message->sender?->role,
                    'body'           => $message->body,
                    'is_read'        => (bool) $message->is_read,
                    'created_at'     => $message->created_at->toIso8601String(),
                    'formatted_time' => $message->created_at->format('h:i A'),
                ];
            });

        return response()->json([
            'conversation' => [
                'id'             => $conversation->id,
                'boarding_house' => $conversation->boardingHouse ? [
                    'id'      => $conversation->boardingHouse->id,
                    'name'    => $conversation->boardingHouse->name,
                    'address' => $conversation->boardingHouse->address,
                ] : null,
                'other_user'     => $otherUser ? [
                    'id'   => $otherUser->id,
                    'name' => $otherUser->name,
                    'role' => $otherUser->role,
                ] : null,
            ],
            'messages'     => $messages,
        ]);
    }

    /**
     * Send a new message in an existing conversation.
     */
    public function sendMessage(Request $request, Conversation $conversation): JsonResponse
    {
        $userId = (int) auth()->id();

        if ((int) $conversation->student_id !== $userId && (int) $conversation->owner_id !== $userId && auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized conversation access.'], 403);
        }

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $userId,
            'body'            => trim($data['body']),
            'is_read'         => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        $recipientId = ((int) $conversation->student_id === $userId)
            ? (int) $conversation->owner_id
            : (int) $conversation->student_id;

        // Broadcast immediately over Reverb WebSockets
        event(new MessageSent($message, $recipientId));

        return response()->json([
            'message' => [
                'id'             => $message->id,
                'conversation_id'=> $message->conversation_id,
                'sender_id'      => (int) $message->sender_id,
                'sender_name'    => auth()->user()->name,
                'sender_role'    => auth()->user()->role,
                'body'           => $message->body,
                'is_read'        => (bool) $message->is_read,
                'created_at'     => $message->created_at->toIso8601String(),
                'formatted_time' => $message->created_at->format('h:i A'),
            ],
        ]);
    }

    /**
     * Mark a conversation as read.
     */
    public function markAsRead(Conversation $conversation): JsonResponse
    {
        $userId = (int) auth()->id();

        if ((int) $conversation->student_id !== $userId && (int) $conversation->owner_id !== $userId && auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized conversation access.'], 403);
        }

        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }
}
