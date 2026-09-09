<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardingHouse extends Model
{
    protected $fillable = [
        'owner_id', 'name', 'slug', 'description', 'amenities', 'address', 'barangay',
        'city', 'contact_number', 'latitude', 'longitude', 'thumbnail_url',
        'map_url', 'rating', 'status',
    ];

    protected $casts = [
        'amenities' => 'array',
        'rating'    => 'float',
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

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function availableRoomsCount(): int
    {
        return $this->rooms()->where('availability_status', 'available')->count();
    }

    /**
     * Recompute and persist the average rating from actual reviews.
     */
    public function refreshRating(): void
    {
        $avg = $this->reviews()->avg('rating');
        $this->update(['rating' => $avg ? round($avg, 1) : null]);
    }
}
