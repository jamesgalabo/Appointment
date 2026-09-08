<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'boarding_house_id', 'room_number', 'room_type', 'capacity',
        'monthly_rent', 'description', 'availability_status', 'photo_url',
    ];

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
