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
use Inertia\Inertia;

class OwnerController extends Controller
{
    protected function house()
    {
        return Auth::user()->boardingHouses()->first();
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
            'room_number'         => 'required|string|max:20',
            'room_type'           => 'required|string|max:50',
            'capacity'            => 'required|integer|min:1',
            'monthly_rent'        => 'required|numeric|min:0',
            'description'         => 'nullable|string',
            'photo_url'           => 'nullable|string|max:500',
            'photo_file'          => 'nullable|image|max:10240', // Upload from device (up to 10MB)
            'availability_status' => 'in:available,occupied,under_maintenance',
        ]);

        if ($request->hasFile('photo_file')) {
            $path = $request->file('photo_file')->store('rooms', 'public');
            $data['photo_url'] = '/storage/' . $path;
        }

        unset($data['photo_file']);
        $house->rooms()->create($data);

        return back()->with('success', 'Room added successfully with photo.');
    }

    public function updateRoom(Request $request, Room $room)
    {
        $data = $request->validate([
            'room_number'         => 'required|string|max:20',
            'room_type'           => 'required|string|max:50',
            'capacity'            => 'required|integer|min:1',
            'monthly_rent'        => 'required|numeric|min:0',
            'description'         => 'nullable|string',
            'photo_url'           => 'nullable|string|max:500',
            'photo_file'          => 'nullable|image|max:10240',
            'availability_status' => 'in:available,occupied,under_maintenance',
        ]);

        if ($request->hasFile('photo_file')) {
            $path = $request->file('photo_file')->store('rooms', 'public');
            $data['photo_url'] = '/storage/' . $path;
        }

        unset($data['photo_file']);
        $room->update($data);

        return back()->with('success', 'Room updated successfully.');
    }

    public function deleteRoom(Room $room)
    {
        $room->delete();
        return back()->with('success', 'Room deleted.');
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
        $request->validate(['status' => 'required|in:approved,rejected,rescheduled,completed']);
        $appointment->update(['status' => $request->status]);

        try {
            event(new BookingStatusUpdated('appointment', $appointment->id, $request->status, $appointment->student_id, $appointment->boarding_house_id));
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
        $request->validate(['status' => 'required|in:approved,rejected,completed']);
        $reservation->update(['status' => $request->status]);
        
        // If approved, mark room occupied & create active Tenant record
        if ($request->status === 'approved') {
            $reservation->room?->update(['availability_status' => 'occupied']);

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
        } elseif ($request->status === 'rejected') {
            // End tenant status if previously approved
            Tenant::where('student_id', $reservation->student_id)
                ->where('room_id', $reservation->room_id)
                ->update(['status' => 'ended']);

            $otherActive = Reservation::where('room_id', $reservation->room_id)
                ->where('id', '!=', $reservation->id)
                ->whereIn('status', ['approved', 'reserved'])
                ->exists();
            if (!$otherActive) {
                $reservation->room?->update(['availability_status' => 'available']);
            }
        }

        try {
            event(new BookingStatusUpdated('reservation', $reservation->id, $request->status, $reservation->student_id, $reservation->room?->boarding_house_id));
        } catch (\Throwable $e) {}

        return back()->with('success', 'Reservation status updated to ' . $request->status . '.');
    }

    public function tenants()
    {
        $house = $this->house();
        if ($house) {
            $approvedReservations = Reservation::with(['student', 'room'])
                ->whereHas('room', fn($q) => $q->where('boarding_house_id', $house->id))
                ->whereIn('status', ['approved', 'reserved'])
                ->get();
            foreach ($approvedReservations as $res) {
                Tenant::firstOrCreate(
                    [
                        'student_id' => $res->student_id,
                        'room_id'    => $res->room_id,
                    ],
                    [
                        'tenant_name'  => $res->student?->name ?? 'Student',
                        'tenant_phone' => $res->student?->phone,
                        'tenant_email' => $res->student?->email,
                        'start_date'   => $res->intended_move_in_date ?? now()->toDateString(),
                        'monthly_rate' => $res->room?->monthly_rent ?? 0,
                        'status'       => 'active',
                    ]
                );
            }
        }

        $rooms = $house ? $house->rooms()->get() : collect();
        $tenants = $house
            ? Tenant::with(['student:id,name,email,phone', 'room:id,room_number,room_type,monthly_rent'])
                ->whereHas('room', fn($q) => $q->where('boarding_house_id', $house->id))
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

        $tenant = Tenant::create([
            'room_id'      => $request->room_id,
            'tenant_name'  => $request->tenant_name,
            'tenant_phone' => $request->tenant_phone,
            'tenant_email' => $request->tenant_email,
            'start_date'   => $request->start_date,
            'monthly_rate' => $request->monthly_rate,
            'status'       => 'active',
        ]);

        // Mark room as occupied
        Room::where('id', $request->room_id)->update(['availability_status' => 'occupied']);

        return back()->with('success', 'Tenant successfully registered and room marked as occupied.');
    }

    public function deleteTenant(Tenant $tenant)
    {
        $roomId = $tenant->room_id;
        $tenant->delete();

        // Check if any other active tenant in room
        $hasActive = Tenant::where('room_id', $roomId)->where('status', 'active')->exists();
        if (!$hasActive) {
            Room::where('id', $roomId)->update(['availability_status' => 'available']);
        }

        return back()->with('success', 'Tenant record removed.');
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
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);
        $user->update($data);

        if ($request->filled('house_name')) {
            $house = $user->boardingHouses()->first();
            $houseData = [
                'name'           => $request->house_name,
                'description'    => $request->house_description,
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
