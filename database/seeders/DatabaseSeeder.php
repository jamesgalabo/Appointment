<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Actors & Roles
        $admin = User::firstOrCreate(
            ['email' => 'admin@kidaboard.ph'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('Admin@2025!'),
                'role' => 'admin',
                'phone' => '09171234567',
            ]
        );

        $owner = User::firstOrCreate(
            ['email' => 'owner@kidaboard.ph'],
            [
                'name' => 'Roberto Dela Cruz',
                'password' => Hash::make('Owner@2025!'),
                'role' => 'owner',
                'phone' => '09289876543',
            ]
        );

        $student = User::firstOrCreate(
            ['email' => 'student@kidaboard.ph'],
            [
                'name' => 'Juan Miguel Santos',
                'password' => Hash::make('Student@2025!'),
                'role' => 'student',
                'phone' => '09395551234',
            ]
        );

        // 2. Seed Amenities
        $amenityList = [
            ['name' => 'High-Speed Wi-Fi', 'icon' => '🛜'],
            ['name' => 'Air Conditioning', 'icon' => '❄️'],
            ['name' => '24/7 CCTV & Security', 'icon' => '🔒'],
            ['name' => 'Continuous Water Supply', 'icon' => '💧'],
            ['name' => 'Kitchen & Cooking Allowed', 'icon' => '🍳'],
            ['name' => 'Quiet Study Area', 'icon' => '📚'],
            ['name' => 'Laundry Area', 'icon' => '🧺'],
            ['name' => 'Motorcycle Parking', 'icon' => '🛵'],
        ];

        foreach ($amenityList as $am) {
            DB::table('amenities')->updateOrInsert(['name' => $am['name']], ['icon' => $am['icon'], 'created_at' => now(), 'updated_at' => now()]);
        }

        // 3. Seed Sample Kidapawan Boarding Houses
        $houses = [
            [
                'owner_id' => $owner->id,
                'name' => 'Kost Oemah Kita',
                'slug' => 'kost-oemah-kita',
                'description' => 'Modern 2-floor accommodation with complete facilities, study lounges, and fast fiber internet near NVC campus.',
                'address' => 'Purok 4, Quezon Boulevard',
                'barangay' => 'Poblacion',
                'city' => 'Kidapawan City',
                'contact_number' => '09289876543',
                'rating' => 4.8,
                'status' => 'approved',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&q=80&w=800',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'owner_id' => $owner->id,
                'name' => 'Kost Putra Residence',
                'slug' => 'kost-putra-residence',
                'description' => 'Peaceful and secure environment ideal for university students and board exam reviewees.',
                'address' => 'Balindog Road, Sudapin',
                'barangay' => 'Sudapin',
                'city' => 'Kidapawan City',
                'contact_number' => '09301112233',
                'rating' => 4.5,
                'status' => 'approved',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&q=80&w=800',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'owner_id' => $owner->id,
                'name' => 'Skyline Central Suites',
                'slug' => 'skyline-central-suites',
                'description' => 'Premium studio apartments with individual sub-meters, private bathrooms, and gated perimeter.',
                'address' => 'Apo Sandawa Homes Phase 2',
                'barangay' => 'Poblacion',
                'city' => 'Kidapawan City',
                'contact_number' => '09456667788',
                'rating' => 4.9,
                'status' => 'approved',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&q=80&w=800',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($houses as $house) {
            $houseId = DB::table('boarding_houses')->insertGetId($house);

            // Seed Rooms for each house
            for ($i = 1; $i <= 4; $i++) {
                DB::table('rooms')->insert([
                    'boarding_house_id' => $houseId,
                    'room_number' => 'Room 10' . $i,
                    'room_type' => $i === 1 ? 'Single' : ($i === 2 ? 'Duo' : ($i === 3 ? 'Studio' : 'Bedspace')),
                    'capacity' => $i === 4 ? 4 : ($i === 2 ? 2 : 1),
                    'monthly_rent' => 1500.00 + ($i * 500),
                    'description' => 'Clean room with study desk, mattress, and wardrobe cabinet.',
                    'availability_status' => $i % 2 === 0 ? 'occupied' : 'available',
                    'photo_url' => $house['thumbnail_url'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
