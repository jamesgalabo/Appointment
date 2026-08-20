@extends('layouts.app')

@section('title', 'Login - BoardEase')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h2>Welcome Back</h2>
                <p>Log in to manage your appointments and bookings</p>
            </div>

            <form action="#" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="student@example.com"
                        required autofocus>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <label
                        style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: var(--text-muted);">
                        <input type="checkbox" name="remember" style="accent-color: var(--primary);"> Remember me
                    </label>
                    <a href="#" class="auth-link" style="font-size: 0.9rem;">Forgot password?</a>
                </div>

                <div class="btn-block">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Sign In</button>
                </div>
            </form>

            <div class="auth-footer">
                <p>Don't have an account? <a href="#" class="auth-link">Register here</a></p>
            </div>
        </div>
    </div>
@endsection