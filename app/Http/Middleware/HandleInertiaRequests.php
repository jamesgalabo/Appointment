<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        if (app()->environment('local') || file_exists(public_path('hot'))) {
            return null;
        }

        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user();
        $pendingCounts = [
            'appointments' => 0,
            'reservations' => 0,
        ];

        if ($user && $user->role === 'owner') {
            $house = \App\Models\BoardingHouse::where('owner_id', $user->id)->first();
            if ($house) {
                $pendingCounts['appointments'] = \App\Models\Appointment::where('boarding_house_id', $house->id)
                    ->where('status', 'pending')
                    ->count();
                $pendingCounts['reservations'] = \App\Models\Reservation::whereHas('room', fn($q) => $q->where('boarding_house_id', $house->id))
                    ->where('status', 'pending')
                    ->count();
            }
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role'  => $user->role,
                ] : null,
            ],
            'pending_counts' => $pendingCounts,
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
            ],
        ];
    }
}
