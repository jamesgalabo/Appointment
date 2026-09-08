<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardingHouse extends Model
{
    protected $fillable = [
        'owner_id', 'name', 'slug', 'description', 'address', 'barangay',
        'city', 'contact_number', 'latitude', 'longitude', 'thumbnail_url',
        'map_url', 'rating', 'status',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function availableRoomsCount(): int
    {
        return $this->rooms()->where('availability_status', 'available')->count();
    }
}
