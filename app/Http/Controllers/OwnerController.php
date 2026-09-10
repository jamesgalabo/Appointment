<?php

namespace App\Http\Controllers;

use App\Events\BookingStatusUpdated;
use App\Models\Appointment;
use App\Models\BoardingHouse;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class OwnerController extends Controller
{
    protected function house()
    {
        return Auth::user()->boardingHouses()->first();
    }

    protected function authorizeHouseOwner($houseId): void
    {
        $house = $this->house();
        if (!$house || (int)$house->id !== (int)$houseId) {
            abort(403, 'Unauthorized action on this boarding house resource.');
        }
    }

    public function dashboard()
    {
        $user = Auth::user();
        $house = $user->boardingHouses()->withCount(['rooms', 'appointments'])->first();

        $stats = [
            'total_rooms'          => $house ? $house->rooms()->count() : 0,
            'available_rooms'      => $house ? $house->rooms()->where('availability_status', 'available')->count() : 0,
            'occupied_rooms'       => $house ? $house->rooms()->where('availability_status', 'occupied')->count() : 0,
            'pending_appointments' => $house ? $house->appointments()->where('status', 'pending')->count() : 0,
            'total_reservations'   => $house ? Reservation::whereHas('room', fn($q) => $q->where('boarding_house_id', $house->id))->count() : 0,
            'active_tenants'       => $house ? Tenant::whereHas('room', fn($q) => $q->where('boarding_house_id', $house->id))->where('status', 'active')->count() : 0,
        ];

        $recentAppointments = $house
            ? Appointment::with('student:id,name,email,phone')
                ->where('boarding_house_id', $house->id)
                ->latest()->take(6)->get()
            : collect();

        $rooms = $house ? $house->rooms()->get() : collect();

        return Inertia::render('Owner/Dashboard', compact('house', 'stats', 'recentAppointments', 'rooms'));
    }

    public function rooms()
    {
        $house = $this->house();
        $rooms = $house ? $house->rooms()->latest()->paginate(15) : collect();
        return Inertia::render('Owner/Rooms', compact('house', 'rooms'));
    }

    public function storeRoom(Request $request)
    {
        $house = $this->house();
        if (!$house) {
            return back()->withErrors(['error' => 'Please set up your boarding house profile first.']);
        }

        $data = $request->validate([
            'room_number'         => [
                'required',
                'string',
                'max:20',
                Rule::unique('rooms', 'room_number')->where(fn($q) => $q->where('boarding_house_id', $house->id)),
            ],
            'room_type'           => 'required|string|max:50',
            'capacity'            => 'required|integer|min:1',
            'monthly_rent'        => 'required|numeric|min:0',
            'description'         => 'nullable|string',
            'photo_url'           => 'nullable|string|max:500',
            'photo_file'          => 'nullable|image|max:10240', // Upload from device (up to 10MB)
            'availability_status' => 'in:available,occupied,under_maintenance',
        ], [
            'room_number.unique'  => 'Room ' . $request->room_number . ' already exists in your boarding house.',
        ]);

        if (in_array($data['room_type'], ['Solo Room', 'Single Room'])) {
            $data['capacity'] = 1;
        } elseif ($data['room_type'] === 'Duo Room') {
            $data['capacity'] = 2;
        }

        if ($request->hasFile('photo_file')) {
            $path = $request->file('photo_file')->store('rooms', 'public');
            $data['photo_url'] = '/storage/' . $path;
        }

        unset($data['photo_file']);
        $newRoom = $house->rooms()->create($data);
        $newRoom->refreshAvailabilityStatus();

        return back()->with('success', 'Room added successfully with photo.');
    }

    public function updateRoom(Request $request, Room $room)
    {
        $this->authorizeHouseOwner($room->boarding_house_id);

        $data = $request->validate([
            'room_number'         => [
                'required',
                'string',
                'max:20',
                Rule::unique('rooms', 'room_number')
                    ->where(fn($q) => $q->where('boarding_house_id', $room->boarding_house_id))
                    ->ignore($room->id),
            ],
            'room_type'           => 'required|string|max:50',
            'capacity'            => 'required|integer|min:1',
            'monthly_rent'        => 'required|numeric|min:0',
            'description'         => 'nullable|string',
            'photo_url'           => 'nullable|string|max:500',
            'photo_file'          => 'nullable|image|max:10240',
            'availability_status' => 'in:available,occupied,under_maintenance',
        ], [
            'room_number.unique'  => 'Room ' . $request->room_number . ' already exists in your boarding house.',
        ]);

        if (in_array($data['room_type'], ['Solo Room', 'Single Room'])) {
            $data['capacity'] = 1;
        } elseif ($data['room_type'] === 'Duo Room') {
            $data['capacity'] = 2;
        }

        if ($request->hasFile('photo_file')) {
            $path = $request->file('photo_file')->store('rooms', 'public');
            $data['photo_url'] = '/storage/' . $path;
        }

        unset($data['photo_file']);
        $room->update($data);
        $room->refreshAvailabilityStatus();

        return back()->with('success', 'Room updated successfully.');
    }

    public function deleteRoom(Room $room)
    {
        $this->authorizeHouseOwner($room->boarding_house_id);

        if ($room->tenants()->where('status', 'active')->exists()) {
            return back()->withErrors(['error' => 'Cannot delete room with active tenants. Please end their tenancy first.']);
        }
        if ($room->reservations()->whereIn('status', ['pending', 'approved', 'reserved'])->exists()) {
            return back()->withErrors(['error' => 'Cannot delete room with active or pending reservations.']);
        }

        try {
            $room->delete();
            return back()->with('success', 'Room deleted.');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withErrors(['error' => 'This room has past reservation or tenant records and cannot be permanently deleted. You can set its status to "Under Maintenance" instead.']);
        }
    }

    public function appointments()
    {
        $house = $this->house();
        $appointments = $house
            ? Appointment::with('student:id,name,email,phone')
                ->where('boarding_house_id', $house->id)
                ->latest()->paginate(15)
            : collect();
        return Inertia::render('Owner/Appointments', compact('house', 'appointments'));
    }

    public function updateAppointment(Request $request, Appointment $appointment)
    {
        $this->authorizeHouseOwner($appointment->boarding_house_id);

        $request->validate([
            'status'              => 'required|in:approved,rejected,rescheduled,completed',
            'cancellation_reason' => 'nullable|string|max:500',
        ]);
        
        $updateData = ['status' => $request->status];
        if ($request->filled('cancellation_reason')) {
            $updateData['cancellation_reason'] = $request->cancellation_reason;
        }

        $appointment->update($updateData);

        try {
            event(new BookingStatusUpdated(
                'appointment',
                $appointment->id,
                $request->status,
                $appointment->student_id,
                $appointment->boarding_house_id,
                $request->cancellation_reason
            ));
        } catch (\Throwable $e) {}

        return back()->with('success', 'Appointment marked as ' . $request->status . '.');
    }

    public function reservations()
    {
        $house = $this->house();
        $reservations = $house
            ? Reservation::with(['student:id,name,email,phone', 'room:id,room_number,room_type,monthly_rent,photo_url'])
                ->whereHas('room', fn($q) => $q->where('boarding_house_id', $house->id))
                ->latest()->paginate(15)
            : collect();
        return Inertia::render('Owner/Reservations', compact('house', 'reservations'));
    }

    public function updateReservation(Request $request, Reservation $reservation)
    {
        $this->authorizeHouseOwner($reservation->room?->boarding_house_id);

        $request->validate([
            'status'              => 'required|in:approved,rejected,completed',
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        $updateData = ['status' => $request->status];
        if ($request->filled('cancellation_reason')) {
            $updateData['cancellation_reason'] = $request->cancellation_reason;
        }

        $reservation->update($updateData);
        
        // If approved, refresh room availability & create active Tenant record
        if ($request->status === 'approved') {
            $reservation->room?->refreshAvailabilityStatus();

            // Auto-enroll in tenants directory
            Tenant::updateOrCreate(
                [
                    'student_id' => $reservation->student_id,
                    'room_id'    => $reservation->room_id,
                ],
                [
                    'tenant_name'  => $reservation->student?->name ?? 'Student',
                    'tenant_phone' => $reservation->student?->phone,
                    'tenant_email' => $reservation->student?->email,
                    'start_date'   => $reservation->intended_move_in_date ?? now()->toDateString(),
                    'monthly_rate' => $reservation->room?->monthly_rent ?? 0,
                    'status'       => 'active',
                ]
            );
        } elseif ($request->status === 'rejected' || $request->status === 'completed') {
            // End tenant status if previously approved
            Tenant::where('student_id', $reservation->student_id)
                ->where('room_id', $reservation->room_id)
                ->update(['status' => 'ended']);

            $reservation->room?->refreshAvailabilityStatus();
        }

        try {
            event(new BookingStatusUpdated(
                'reservation',
                $reservation->id,
                $request->status,
                $reservation->student_id,
                $reservation->room?->boarding_house_id,
                $request->cancellation_reason
            ));
        } catch (\Throwable $e) {}

        return back()->with('success', 'Reservation status updated to ' . $request->status . '.');
    }

    public function tenants()
    {
        $house = $this->house();
        $rooms = $house ? $house->rooms()->get() : collect();
        $tenants = $house
            ? Tenant::with(['student:id,name,email,phone', 'room:id,room_number,room_type,monthly_rent'])
                ->whereHas('room', fn($q) => $q->where('boarding_house_id', $house->id))
                ->where('status', 'active')
                ->latest()->paginate(15)
            : collect();
        return Inertia::render('Owner/Tenants', compact('house', 'tenants', 'rooms'));
    }

    public function storeTenant(Request $request)
    {
        $request->validate([
            'room_id'      => 'required|exists:rooms,id',
            'tenant_name'  => 'required|string|max:255',
            'tenant_phone' => 'nullable|string|max:30',
            'tenant_email' => 'nullable|email|max:255',
            'start_date'   => 'required|date',
            'monthly_rate' => 'required|numeric|min:0',
        ]);

        $room = Room::findOrFail($request->room_id);
        $this->authorizeHouseOwner($room->boarding_house_id);

        $tenant = Tenant::create([
            'room_id'      => $request->room_id,
            'tenant_name'  => $request->tenant_name,
            'tenant_phone' => $request->tenant_phone,
            'tenant_email' => $request->tenant_email,
            'start_date'   => $request->start_date,
            'monthly_rate' => $request->monthly_rate,
            'status'       => 'active',
        ]);

        // Refresh room status based on remaining slots
        $room->refreshAvailabilityStatus();

        return back()->with('success', 'Tenant successfully registered.');
    }

    public function deleteTenant(Tenant $tenant)
    {
        $this->authorizeHouseOwner($tenant->room?->boarding_house_id);

        $roomId = $tenant->room_id;
        $studentId = $tenant->student_id;

        // If there's an active reservation for this student in this room, mark it completed
        if ($studentId) {
            Reservation::where('student_id', $studentId)
                ->where('room_id', $roomId)
                ->whereIn('status', ['approved', 'reserved'])
                ->update(['status' => 'completed']);
        }

        // Update tenant status to ended & delete
        $tenant->update([
            'status'   => 'ended',
            'end_date' => now(),
        ]);
        $tenant->delete();

        // Refresh room availability
        $room = Room::find($roomId);
        $room?->refreshAvailabilityStatus();

        return back()->with('success', 'Tenancy ended successfully and room slots updated.');
    }

    public function profile()
    {
        $user = Auth::user();
        $house = $user->boardingHouses()->first();
        return Inertia::render('Owner/Profile', compact('user', 'house'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'thumbnail_file' => 'nullable|image|max:10240',
        ]);
        $user->update(['name' => $data['name'], 'phone' => $data['phone']]);

        if ($request->filled('house_name')) {
            $house = $user->boardingHouses()->first();
            $houseData = [
                'name'           => $request->house_name,
                'description'    => $request->house_description,
                'amenities'      => is_array($request->amenities) ? $request->amenities : [],
                'address'        => $request->house_address,
                'barangay'       => $request->barangay ?? 'Poblacion',
                'contact_number' => $request->house_contact,
                'map_url'        => $request->map_url,
                'thumbnail_url'  => $request->thumbnail_url,
            ];

            if ($request->hasFile('thumbnail_file')) {
                $path = $request->file('thumbnail_file')->store('houses', 'public');
                $houseData['thumbnail_url'] = '/storage/' . $path;
            }

            if ($house) {
                $house->update($houseData);
            } else {
                $houseData['owner_id'] = $user->id;
                $houseData['slug'] = Str::slug($request->house_name) . '-' . $user->id;
                $houseData['city'] = 'Kidapawan City';
                $houseData['status'] = 'approved';
                BoardingHouse::create($houseData);
            }
        }

        return back()->with('success', 'Profile & House details updated successfully.');
    }
}
