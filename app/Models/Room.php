<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'boarding_house_id', 'room_number', 'room_type', 'capacity',
        'monthly_rent', 'description', 'availability_status', 'photo_url',
    ];

    protected $casts = [
        'capacity'     => 'integer',
        'monthly_rent' => 'float',
    ];

    protected $appends = [
        'occupied_slots',
        'remaining_slots',
    ];

    public function getOccupiedSlotsAttribute(): int
    {
        $reservedSlots = (int) $this->reservations()
            ->whereIn('status', ['approved', 'reserved'])
            ->sum('slots_reserved');

        $manualTenants = (int) $this->tenants()
            ->where('status', 'active')
            ->whereNull('student_id')
            ->count();

        return $reservedSlots + $manualTenants;
    }

    public function getRemainingSlotsAttribute(): int
    {
        $capacity = max(1, (int) $this->capacity);
        return max(0, $capacity - $this->occupied_slots);
    }

    /**
     * Refresh and persist availability_status based on capacity and occupied slots.
     */
    public function refreshAvailabilityStatus(): string
    {
        if ($this->availability_status === 'under_maintenance') {
            return 'under_maintenance';
        }

        $capacity = max(1, (int) $this->capacity);
        $newStatus = $this->occupied_slots >= $capacity ? 'occupied' : 'available';

        if ($this->availability_status !== $newStatus) {
            $this->update(['availability_status' => $newStatus]);
        }

        return $newStatus;
    }

    public function boardingHouse()
    {
        return $this->belongsTo(BoardingHouse::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'room_amenities');
    }
}
