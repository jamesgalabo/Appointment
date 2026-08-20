@extends('layouts.app')

@section('title', 'Welcome to BoardEase')

@section('content')
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-bg-2"></div>
        <div class="hero-container">
            <div class="hero-content">
                <h1>Find Your Perfect Boarding House</h1>
                <p>A centralized platform for students to discover, book, and manage boarding house accommodations seamlessly. Connect with owners and secure your space today.</p>
                <div class="hero-actions">
                    <a href="/login" class="btn btn-primary">Get Started</a>
                    <a href="#features" class="btn btn-outline">Explore Features</a>
                </div>
            </div>
            <div class="hero-image">
                <!-- Using a placeholder API for a nice modern image -->
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&q=80&w=1000" alt="Modern Boarding House Room">
            </div>
        </div>
    </section>

    <section id="features" class="features">
        <div class="features-container">
            <h2 class="section-title">Why Choose BoardEase?</h2>
            <div class="features-grid">
                
                <!-- Feature 1 -->
                <div class="feature-card">
                    <div class="feature-icon">🔍</div>
                    <h3>Smart Search</h3>
                    <p>Easily find boarding houses and available rooms without the hassle of visiting different places personally. Filter by price, amenities, and location.</p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card">
                    <div class="feature-icon">📅</div>
                    <h3>Easy Booking</h3>
                    <p>Conveniently request an appointment or book a room directly through our platform. Monitor the status of your requests in real-time.</p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card">
                    <div class="feature-icon">⚙️</div>
                    <h3>Effortless Management</h3>
                    <p>For owners: Manage your rooms, set prices, update availability, and organize booking requests efficiently in one centralized dashboard.</p>
                </div>

            </div>
        </div>
    </section>
@endsection
