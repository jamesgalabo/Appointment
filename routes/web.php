<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'owner'   => redirect()->route('owner.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default   => Inertia::render('Welcome'),
        };
    }
    return Inertia::render('Welcome');
})->name('home');

Route::get('/privacy-policy', fn () => Inertia::render('Legal/Index', ['initialTab' => 'privacy']))->name('legal.privacy');
Route::get('/terms', fn () => Inertia::render('Legal/Index', ['initialTab' => 'terms']))->name('legal.terms');
Route::get('/refund-policy', fn () => Inertia::render('Legal/Index', ['initialTab' => 'refund']))->name('legal.refund');
Route::get('/cookie-policy', fn () => Inertia::render('Legal/Index', ['initialTab' => 'cookies']))->name('legal.cookies');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('verify-otp');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp.post');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend-otp');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/houses', [AdminController::class, 'houses'])->name('houses');
    Route::patch('/houses/{house}/approve', [AdminController::class, 'approveHouse'])->name('houses.approve');
    Route::patch('/houses/{house}/reject', [AdminController::class, 'rejectHouse'])->name('houses.reject');
    Route::get('/appointments', [AdminController::class, 'appointments'])->name('appointments');
    Route::get('/reservations', [AdminController::class, 'reservations'])->name('reservations');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});

/*
|--------------------------------------------------------------------------
| Owner Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/rooms', [OwnerController::class, 'rooms'])->name('rooms');
    Route::post('/rooms', [OwnerController::class, 'storeRoom'])->name('rooms.store');
    Route::patch('/rooms/{room}', [OwnerController::class, 'updateRoom'])->name('rooms.update');
    Route::delete('/rooms/{room}', [OwnerController::class, 'deleteRoom'])->name('rooms.delete');
    Route::get('/appointments', [OwnerController::class, 'appointments'])->name('appointments');
    Route::patch('/appointments/{appointment}', [OwnerController::class, 'updateAppointment'])->name('appointments.update');
    Route::get('/reservations', [OwnerController::class, 'reservations'])->name('reservations');
    Route::patch('/reservations/{reservation}', [OwnerController::class, 'updateReservation'])->name('reservations.update');
    Route::get('/tenants', [OwnerController::class, 'tenants'])->name('tenants');
    Route::post('/tenants', [OwnerController::class, 'storeTenant'])->name('tenants.store');
    Route::delete('/tenants/{tenant}', [OwnerController::class, 'deleteTenant'])->name('tenants.delete');
    Route::get('/profile', [OwnerController::class, 'profile'])->name('profile');
    Route::post('/profile', [OwnerController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/explore', [StudentController::class, 'explore'])->name('explore');
    Route::get('/houses/{house}', [StudentController::class, 'showHouse'])->name('houses.show');
    Route::post('/houses/{house}/appointment', [StudentController::class, 'bookAppointment'])->name('appointment.book');
    Route::post('/rooms/{room}/reserve', [StudentController::class, 'reserveRoom'])->name('room.reserve');
    Route::get('/appointments', [StudentController::class, 'appointments'])->name('appointments');
    Route::patch('/appointments/{appointment}/cancel', [StudentController::class, 'cancelAppointment'])->name('appointments.cancel');
    Route::get('/reservations', [StudentController::class, 'reservations'])->name('reservations');
    Route::patch('/reservations/{reservation}/cancel', [StudentController::class, 'cancelReservation'])->name('reservations.cancel');
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    Route::patch('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');
    Route::post('/houses/{house}/review', [StudentController::class, 'submitReview'])->name('houses.review.submit');
    Route::delete('/houses/{house}/review', [StudentController::class, 'deleteReview'])->name('houses.review.delete');
});

/*
|--------------------------------------------------------------------------
| Real-Time Chat Routes (Students & Owners)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/messages', [ChatController::class, 'index'])->name('messages.index');
    Route::get('/api/chat/conversations', [ChatController::class, 'getConversations'])->name('chat.conversations');
    Route::post('/api/chat/start', [ChatController::class, 'startOrGetConversation'])->name('chat.start');
    Route::get('/api/chat/conversations/{conversation}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/api/chat/conversations/{conversation}/messages', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/api/chat/conversations/{conversation}/read', [ChatController::class, 'markAsRead'])->name('chat.read');
});

