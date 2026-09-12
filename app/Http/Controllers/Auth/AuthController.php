    <?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class AuthController extends Controller
{
    /* ──────────────────────────────────────────────
     |  LOGIN
     ─────────────────────────────────────────────── */

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return Inertia::render('Auth/Login', [
            'errors' => session('errors') ? session('errors')->toArray() : [],
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user()->role);
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    /* ──────────────────────────────────────────────
     |  REGISTER — Step 1: Collect info & send OTP
     ─────────────────────────────────────────────── */

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return Inertia::render('Auth/Register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'role'                  => ['required', 'in:student,owner'],
            'password'              => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            'terms'                 => ['accepted'],
        ], [
            'terms.accepted'        => 'You must agree to the Terms & Conditions and acknowledge the Privacy Policy to create an account.',
        ]);

        // Generate a 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Delete any previous OTP for this email
        DB::table('email_verification_otps')->where('email', $data['email'])->delete();

        // Store OTP + pending user payload
        DB::table('email_verification_otps')->insert([
            'email'      => $data['email'],
            'otp'        => $otp,
            'payload'    => json_encode([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'phone'    => $data['phone'] ?? null,
                'role'     => $data['role'],
                'password' => Hash::make($data['password']),
            ]),
            'expires_at'  => now()->addMinutes(10),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Queue OTP email so the HTTP request is never blocked by SMTP
        try {
            Mail::to($data['email'])->queue(new VerificationOtpMail($otp, $data['name']));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Registration OTP Email failed for {$data['email']}: {$e->getMessage()} | OTP is: {$otp}");
        }

        return Inertia::render('Auth/VerifyOtp', [
            'email' => $data['email'],
        ]);
    }

    /* ──────────────────────────────────────────────
     |  REGISTER — Step 2: Verify OTP & create user
     ─────────────────────────────────────────────── */

    public function showVerifyOtp()
    {
        return Inertia::render('Auth/VerifyOtp', [
            'email' => session('pending_email', ''),
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp'   => ['required', 'string', 'size:6'],
        ]);

        $record = DB::table('email_verification_otps')
            ->where('email', $request->email)
            ->latest('created_at')
            ->first();

        if (! $record) {
            return back()->withErrors(['otp' => 'No verification code was found for this email. Please register again.']);
        }

        if (now()->isAfter($record->expires_at)) {
            DB::table('email_verification_otps')->where('id', $record->id)->delete();
            return back()->withErrors(['otp' => 'This code has expired. Please register again.']);
        }

        if ($record->otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Incorrect verification code. Please try again.']);
        }

        // OTP valid — create the user
        $payload = json_decode($record->payload, true);

        $user = \App\Models\User::create([
            'name'     => $payload['name'],
            'email'    => $payload['email'],
            'phone'    => $payload['phone'],
            'role'     => $payload['role'],
            'password' => $payload['password'], // already hashed
        ]);

        // Clean up OTP record
        DB::table('email_verification_otps')->where('id', $record->id)->delete();

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectByRole($user->role);
    }

    public function resendOtp(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $record = DB::table('email_verification_otps')
            ->where('email', $request->email)
            ->latest('created_at')
            ->first();

        if (! $record) {
            return back()->withErrors(['otp' => 'Session expired. Please register again.']);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $payload = json_decode($record->payload, true);

        DB::table('email_verification_otps')
            ->where('email', $request->email)
            ->update([
                'otp'        => $otp,
                'expires_at' => now()->addMinutes(10),
                'updated_at' => now(),
            ]);

        try {
            Mail::to($request->email)->queue(new VerificationOtpMail($otp, $payload['name']));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Resend OTP Email failed for {$request->email}: {$e->getMessage()} | OTP is: {$otp}");
        }

        return back()->with('resent', true);
    }

    /* ──────────────────────────────────────────────
     |  LOGOUT
     ─────────────────────────────────────────────── */

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    /* ──────────────────────────────────────────────
     |  HELPERS
     ─────────────────────────────────────────────── */

    private function redirectByRole(string $role)
    {
        return match ($role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'owner'   => redirect()->route('owner.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default   => redirect('/login'),
        };
    }
}
