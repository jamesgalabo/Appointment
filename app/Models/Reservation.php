<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'student_id', 'room_id', 'reservation_date', 'intended_move_in_date',
        'deposit_amount', 'payment_method', 'payment_reference', 'payment_receipt_url',
        'status', 'qr_reference', 'remarks', 'cancellation_reason',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'intended_move_in_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
