@extends('layouts.app')

@section('title', 'Sign In  KidaBoard Kidapawan')

@section('content')
<div class="min-h-[calc(100vh-4rem)] flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden bg-slate-950">
    <!-- Ambient Background Glows -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute bottom-10 right-10 w-80 h-80 bg-blue-600/15 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <div class="w-full max-w-md">
        <!-- Brand Header Card -->
        <div class="glass-card rounded-3xl p-8 sm:p-10 shadow-2xl shadow-indigo-950/40 relative">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 to-blue-500 shadow-xl shadow-indigo-500/25 mb-4 ring-4 ring-indigo-500/20">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Welcome Back</h1>
                <p class="text-sm text-slate-400 mt-2">Sign in to your account to manage reservations, rooms, and appointments in Kidapawan City</p>
            </div>

            <!-- Role Selector Tabs -->
            <div class="grid grid-cols-3 gap-1 bg-slate-900/90 p-1.5 rounded-2xl border border-slate-800/80 mb-6 text-xs font-semibold">
                <button type="button" class="py-2 rounded-xl bg-indigo-600 text-white shadow-sm transition">Student</button>
                <button type="button" class="py-2 rounded-xl text-slate-400 hover:text-white transition">Owner</button>
                <button type="button" class="py-2 rounded-xl text-slate-400 hover:text-white transition">Admin</button>
            </div>

            @if(session('error'))
                <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-xs text-rose-300 flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form action="/login" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="student@kidapawan.edu.ph"
                            class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700/70 text-slate-100 placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-inner">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Password</label>
                        <a href="#" class="text-xs font-medium text-indigo-400 hover:text-indigo-300 transition">Forgot password?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required
                            placeholder="••••••••••••"
                            class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700/70 text-slate-100 placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-inner">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none text-xs text-slate-400 hover:text-slate-300">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                        <span>Remember me on this device</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-bold text-sm shadow-xl shadow-indigo-600/30 hover:shadow-indigo-600/50 hover:scale-[1.01] transition-all duration-200 flex items-center justify-center gap-2">
                        <span>Sign In to Dashboard</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-800 text-center">
                <p class="text-xs text-slate-400">
                    New student or boarding house owner?
                    <a href="/register" class="font-semibold text-indigo-400 hover:text-indigo-300 transition underline underline-offset-4 ml-1">Create an account</a>
                </p>
            </div>

            <!-- Quick Demo Credentials Pill -->
            <div class="mt-5 p-3 rounded-xl bg-indigo-950/40 border border-indigo-800/40 text-[11px] text-slate-400 flex flex-col gap-1">
                <div class="font-semibold text-indigo-300 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span>Demo Credentials for Presentation:</span>
                </div>
                <div class="flex justify-between items-center text-slate-300">
                    <span>Admin: admin@kidaboard.ph</span>
                    <span class="text-slate-500">pass: password</span>
                </div>
                <div class="flex justify-between items-center text-slate-300">
                    <span>Student: student@kidaboard.ph</span>
                    <span class="text-slate-500">pass: password</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection