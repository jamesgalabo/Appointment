<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\BoardingHouse;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users'         => User::count(),
            'total_students'      => User::where('role', 'student')->count(),
            'total_owners'        => User::where('role', 'owner')->count(),
            'total_houses'        => BoardingHouse::count(),
            'pending_houses'      => BoardingHouse::where('status', 'pending')->count(),
            'total_rooms'         => Room::count(),
            'available_rooms'     => Room::where('availability_status', 'available')->count(),
            'occupied_rooms'      => Room::where('availability_status', 'occupied')->count(),
            'total_appointments'  => Appointment::count(),
            'pending_appointments'=> Appointment::where('status', 'pending')->count(),
            'total_reservations'  => Reservation::count(),
            'active_tenants'      => Tenant::where('status', 'active')->count(),
        ];

        $recentUsers = User::latest()->take(5)->get(['id', 'name', 'email', 'role', 'created_at']);
        $recentHouses = BoardingHouse::with('owner:id,name')->latest()->take(5)->get();
        $recentReservations = Reservation::with(['student:id,name', 'room:id,room_number,boarding_house_id'])
            ->latest()->take(5)->get();

        return Inertia::render('Admin/Dashboard', compact('stats', 'recentUsers', 'recentHouses', 'recentReservations'));
    }

    public function users()
    {
        $users = User::latest()->paginate(15);
        return Inertia::render('Admin/Users', compact('users'));
    }

    public function houses()
    {
        $houses = BoardingHouse::with('owner:id,name')->withCount('rooms')->latest()->paginate(15);
        return Inertia::render('Admin/Houses', compact('houses'));
    }

    public function approveHouse(BoardingHouse $house)
    {
        $house->update(['status' => 'approved']);
        return back()->with('success', 'Boarding house approved.');
    }

    public function rejectHouse(BoardingHouse $house)
    {
        $house->update(['status' => 'rejected']);
        return back()->with('success', 'Boarding house rejected.');
    }

    public function appointments()
    {
        $appointments = Appointment::with(['student:id,name', 'boardingHouse:id,name'])
            ->latest()->paginate(15);
        return Inertia::render('Admin/Appointments', compact('appointments'));
    }

    public function reservations()
    {
        $reservations = Reservation::with(['student:id,name', 'room.boardingHouse:id,name'])
            ->latest()->paginate(15);
        return Inertia::render('Admin/Reservations', compact('reservations'));
    }

    public function reports()
    {
        $monthlyData = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $currentMonth = (int)date('n');

        for ($m = 1; $m <= 12; $m++) {
            $resCount = Reservation::whereMonth('created_at', $m)->count();
            $appCount = Appointment::whereMonth('created_at', $m)->count();
            // Estimated volume
            $monthlyData[] = [
                'month'        => $months[$m - 1],
                'month_num'    => $m,
                'reservations' => $resCount,
                'appointments' => $appCount,
                'total'        => $resCount + $appCount,
            ];
        }

        $totalRooms = Room::count();
        $occupiedRooms = Room::where('availability_status', 'occupied')->count();
        $availableRooms = Room::where('availability_status', 'available')->count();
        $maintenanceRooms = Room::where('availability_status', 'under_maintenance')->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;

        $totalMonthlyRent = Room::where('availability_status', 'occupied')->sum('monthly_rent');
        $potentialMonthlyRent = Room::sum('monthly_rent');

        $statusBreakdown = [
            'reservations_approved' => Reservation::whereIn('status', ['approved', 'reserved'])->count(),
            'reservations_pending'  => Reservation::where('status', 'pending')->count(),
            'reservations_cancelled' => Reservation::whereIn('status', ['cancelled', 'rejected'])->count(),
            'appointments_approved' => Appointment::where('status', 'approved')->count(),
            'appointments_pending'  => Appointment::where('status', 'pending')->count(),
            'appointments_completed' => Appointment::where('status', 'completed')->count(),
        ];

        $housesSummary = BoardingHouse::withCount('rooms')
            ->withCount(['rooms as occupied_count' => fn($q) => $q->where('availability_status', 'occupied')])
            ->get()
            ->map(function ($h) {
                return [
                    'id' => $h->id,
                    'name' => $h->name,
                    'barangay' => $h->barangay,
                    'rooms_count' => $h->rooms_count,
                    'occupied_count' => $h->occupied_count,
                    'occupancy_rate' => $h->rooms_count > 0 ? round(($h->occupied_count / $h->rooms_count) * 100) : 0,
                    'status' => $h->status,
                ];
            });

        return Inertia::render('Admin/Reports', compact(
            'monthlyData',
            'occupancyRate',
            'totalRooms',
            'occupiedRooms',
            'availableRooms',
            'maintenanceRooms',
            'totalMonthlyRent',
            'potentialMonthlyRent',
            'statusBreakdown',
            'housesSummary'
        ));
    }
}
