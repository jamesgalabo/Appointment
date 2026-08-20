<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'BoardEase - Boarding House Management')</title>
        <link rel="icon" href="{{ asset('NVC.jpg') }}" type="image/jpeg">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    </head>
    <body>
        <nav class="navbar">
            <div class="nav-container">
                <a href="/" class="nav-logo">
                    <img src="{{ asset('NVC.jpg') }}" alt="BoardEase Logo" style="height: 36px; width: auto; border-radius: 4px; margin-right: 8px;">
                    BoardEase
                </a>
                <div class="nav-links">
                    <a href="/" class="nav-link">Home</a>
                    <a href="#" class="nav-link">Find Rooms</a>
                    <a href="#" class="nav-link">Bookings</a>
                    <a href="#" class="nav-link">Owners Portal</a>
                    <a href="/login" class="nav-link btn-login">Login</a>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
        
        <footer class="footer">
            <p>&copy; {{ date('Y') }} Boarding House Appointment & Booking Management System. All rights reserved.</p>
        </footer>
    </body>
</html>
