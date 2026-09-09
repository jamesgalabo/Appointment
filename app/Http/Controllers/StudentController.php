<?php

namespace App\Http\Controllers;

use App\Events\BookingStatusUpdated;
use App\Models\Appointment;
use App\Models\BoardingHouse;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $stats = [
            'total_appointments'   => Appointment::where('student_id', $user->id)->count(),
            'pending_appointments' => Appointment::where('student_id', $user->id)->where('status', 'pending')->count(),
            'total_reservations'   => Reservation::where('student_id', $user->id)->count(),
            'active_reservations'  => Reservation::where('student_id', $user->id)->whereIn('status', ['approved', 'reserved'])->count(),
        ];

        $appointments = Appointment::with('boardingHouse:id,name,address,thumbnail_url')
            ->where('student_id', $user->id)
            ->latest()->take(5)->get();

        $reservations = Reservation::with(['room:id,room_number,monthly_rent,boarding_house_id', 'room.boardingHouse:id,name'])
            ->where('student_id', $user->id)
            ->latest()->take(5)->get();

        return Inertia::render('Student/Dashboard', compact('stats', 'appointments', 'reservations'));
    }

    public function explore(Request $request)
    {
        $query = BoardingHouse::with(['rooms' => fn($q) => $q->where('availability_status', 'available')])
            ->where('status', 'approved');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $lowerSearch = strtolower($search);

            $query->where(function ($q) use ($search, $lowerSearch) {
                if (in_array($lowerSearch, ['kidapawan', 'kidapawan city', 'kidapawan area', 'city of kidapawan'])) {
                    return;
                }

                $remainder = trim(preg_replace('/\b(kidapawan|city|area)\b/i', '', $search));
                if (!empty($remainder)) {
                    $q->where('name', 'like', '%' . $remainder . '%')
                      ->orWhere('address', 'like', '%' . $remainder . '%')
                      ->orWhere('barangay', 'like', '%' . $remainder . '%')
                      ->orWhere('description', 'like', '%' . $remainder . '%');
                } else {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('address', 'like', '%' . $search . '%')
                      ->orWhere('barangay', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
                }
            });
        }

        $houses = $query->withCount(['rooms', 'rooms as available_rooms_count' => fn($q) => $q->where('availability_status', 'available')])
            ->orderByDesc('rating')->paginate(9);

        return Inertia::render('Student/Explore', [
            'houses'  => $houses,
            'filters' => $request->only('search'),
        ]);
    }

    public function showHouse(BoardingHouse $house)
    {
        // Refresh room availability statuses based on capacity and occupied slots
        $house->rooms->each(fn($r) => $r->refreshAvailabilityStatus());

        // Load house with owner, rooms, and real reviews (with reviewer names)
        $house->load([
            'owner:id,name,phone',
            'rooms',
            'reviews' => fn($q) => $q->with('student:id,name')->latest()->take(30),
        ]);

        $activeAppointment = null;
        $activeReservation = null;
        $myReview          = null;
        $canReview         = false;

        if (Auth::check() && Auth::user()->role === 'student') {
            $activeAppointment = Appointment::where('student_id', Auth::id())
                ->where('boarding_house_id', $house->id)
                ->whereIn('status', ['pending', 'approved', 'rescheduled'])
                ->first();

            $activeReservation = Reservation::where('student_id', Auth::id())
                ->whereHas('room', fn($q) => $q->where('boarding_house_id', $house->id))
                ->whereIn('status', ['pending', 'approved', 'reserved'])
                ->first();

            $myReview  = Review::where('student_id', Auth::id())
                ->where('boarding_house_id', $house->id)
                ->first();

            // Any registered student / customer can review and rate this boarding house!
            $canReview = true;
        }

        return Inertia::render('Student/HouseDetail', compact(
            'house', 'activeAppointment', 'activeReservation', 'myReview', 'canReview'
        ));
    }

    public function bookAppointment(Request $request, BoardingHouse $house)
    {
        if ($house->status !== 'approved') {
            return back()->withErrors(['error' => 'This boarding house is currently not accepting appointments.']);
        }

        $existing = Appointment::where('student_id', Auth::id())
            ->where('boarding_house_id', $house->id)
            ->whereIn('status', ['pending', 'approved', 'rescheduled'])
            ->first();

        if ($existing) {
            return back()->withErrors(['error' => 'You already have an active viewing appointment (Status: ' . ucfirst($existing->status) . ') for this boarding house.']);
        }

        $data = $request->validate([
            'scheduled_date' => 'required|date|after_or_equal:today',
            'time_slot'      => 'required|string',
            'notes'          => 'nullable|string|max:500',
        ]);

        $appointment = Appointment::create([
            'student_id'        => Auth::id(),
            'boarding_house_id' => $house->id,
            'scheduled_date'    => $data['scheduled_date'],
            'time_slot'         => $data['time_slot'],
            'notes'             => $data['notes'] ?? null,
            'status'            => 'pending',
        ]);

        try {
            event(new BookingStatusUpdated('appointment', $appointment->id, 'pending', $appointment->student_id, $house->id));
        } catch (\Throwable $e) {}

        return back()->with('success', 'Viewing appointment requested! The house owner will confirm your schedule.');
    }

    public function cancelAppointment(Request $request, Appointment $appointment)
    {
        if ((int)$appointment->student_id !== (int)Auth::id()) {
            abort(403);
        }

        $reason = $request->cancellation_reason ?? 'Cancelled by student';
        $appointment->update([
            'status'              => 'cancelled',
            'cancellation_reason' => $reason,
        ]);

        try {
            event(new BookingStatusUpdated('appointment', $appointment->id, 'cancelled', $appointment->student_id, $appointment->boarding_house_id, $reason));
        } catch (\Throwable $e) {}

        return back()->with('success', 'Viewing appointment cancelled successfully.');
    }

    public function reserveRoom(Request $request, Room $room)
    {
        if ($room->boardingHouse?->status !== 'approved') {
            return back()->withErrors(['error' => 'This boarding house is currently not accepting reservations.']);
        }

        if ($room->availability_status === 'under_maintenance') {
            return back()->withErrors(['error' => 'This room is currently under maintenance and cannot be reserved.']);
        }

        if ($room->remaining_slots <= 0) {
            return back()->withErrors(['error' => 'This room is currently fully occupied.']);
        }

        // Guard against duplicate pending reservation for the same room by the same student
        $existingPending = Reservation::where('student_id', Auth::id())
            ->where('room_id', $room->id)
            ->where('status', 'pending')
            ->first();

        if ($existingPending) {
            return back()->withErrors(['error' => 'You already have a pending reservation under review for Room ' . $room->room_number . '.']);
        }

        $data = $request->validate([
            'intended_move_in_date' => 'required|date|after_or_equal:today',
            'slots_reserved'        => 'required|integer|min:1|max:' . max(1, $room->remaining_slots),
            'companion_name'        => 'nullable|string|max:255',
            'payment_method'        => 'required|string|in:GCash,Maya,Cash,Cash Upon Move-in,Bank Transfer',
            'deposit_amount'        => 'nullable|numeric|min:0',
            'payment_reference'     => 'nullable|string|max:100',
            'receipt_file'          => 'nullable|image|max:10240',
            'remarks'               => 'nullable|string|max:500',
            'agree_policy'          => 'nullable|accepted',
        ]);

        if ($data['slots_reserved'] > $room->remaining_slots) {
            return back()->withErrors(['error' => "Only {$room->remaining_slots} slot(s) remaining in this room."]);
        }

        $receiptUrl = null;
        if ($request->hasFile('receipt_file')) {
            $path = $request->file('receipt_file')->store('receipts', 'public');
            $receiptUrl = '/storage/' . $path;
        }

        $reservation = Reservation::create([
            'student_id'            => Auth::id(),
            'room_id'               => $room->id,
            'reservation_date'      => now()->toDateString(),
            'intended_move_in_date' => $data['intended_move_in_date'],
            'slots_reserved'        => $data['slots_reserved'],
            'companion_name'        => $data['companion_name'] ?? null,
            'deposit_amount'        => $data['deposit_amount'] ?? 0.00,
            'payment_method'        => $data['payment_method'],
            'payment_reference'     => $data['payment_reference'] ?? null,
            'payment_receipt_url'   => $receiptUrl,
            'qr_reference'          => strtoupper(Str::random(10)),
            'remarks'               => $data['remarks'] ?? null,
            'status'                => 'pending',
        ]);

        try {
            event(new BookingStatusUpdated('reservation', $reservation->id, 'pending', $reservation->student_id, $room->boarding_house_id));
        } catch (\Throwable $e) {}

        return back()->with('success', 'Room reservation submitted! The owner will review your payment and approve your booking.');
    }

    public function cancelReservation(Request $request, Reservation $reservation)
    {
        if ((int)$reservation->student_id !== (int)Auth::id()) {
            abort(403);
        }

        $reason = $request->cancellation_reason ?? 'Cancelled by student';
        $reservation->update([
            'status'              => 'cancelled',
            'cancellation_reason' => $reason,
        ]);

        $reservation->room?->refreshAvailabilityStatus();

        Tenant::where('student_id', Auth::id())
            ->where('room_id', $reservation->room_id)
            ->update(['status' => 'ended']);

        try {
            event(new BookingStatusUpdated('reservation', $reservation->id, 'cancelled', $reservation->student_id, $reservation->room?->boarding_house_id, $reason));
        } catch (\Throwable $e) {}

        return back()->with('success', 'Reservation cancelled.');
    }

    // ─── Reviews ────────────────────────────────────────────────────────────────

    /**
     * Submit or update a review. Any registered student/customer can leave a rating and review.
     */
    public function submitReview(Request $request, BoardingHouse $house)
    {
        if (!Auth::check() || Auth::user()->role !== 'student') {
            return back()->withErrors(['error' => 'Please log in as a student to rate and review this boarding house.']);
        }

        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::updateOrCreate(
            ['student_id' => Auth::id(), 'boarding_house_id' => $house->id],
            ['rating' => $data['rating'], 'comment' => $data['comment'] ?? null]
        );

        // Recompute the stored average rating
        $house->refreshRating();

        return back()->with('success', 'Thank you for your rating and review!');
    }

    /**
     * Delete the student's own review.
     */
    public function deleteReview(BoardingHouse $house)
    {
        if (!Auth::check() || Auth::user()->role !== 'student') {
            abort(403);
        }

        Review::where('student_id', Auth::id())
            ->where('boarding_house_id', $house->id)
            ->delete();

        $house->refreshRating();

        return back()->with('success', 'Your review has been removed.');
    }

    // ─── Pages ──────────────────────────────────────────────────────────────────

    public function appointments()
    {
        $appointments = Appointment::with('boardingHouse:id,name,address,thumbnail_url,owner_id')
            ->where('student_id', Auth::id())
            ->latest()->paginate(10);
        return Inertia::render('Student/Appointments', compact('appointments'));
    }

    public function reservations()
    {
        $reservations = Reservation::with(['room.boardingHouse:id,name,address,owner_id'])
            ->where('student_id', Auth::id())
            ->latest()->paginate(10);
        return Inertia::render('Student/Reservations', compact('reservations'));
    }

    public function profile()
    {
        $user = Auth::user();
        return Inertia::render('Student/Profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);
        $user->update($data);
        return back()->with('success', 'Profile updated successfully.');
    }
}
