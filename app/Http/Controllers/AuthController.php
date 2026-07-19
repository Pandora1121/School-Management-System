<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\PasswordResetRequest;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::lower($credentials['username']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'username' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);
            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();
        Auth::user()->update(['last_login_time' => now()]);

        return redirect()->intended('dashboard');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:tbl_users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:tbl_users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = \App\Models\User::create([
            'creation_time' => now(),
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 4, // default: Siswa
            'status' => 1,
            'archived' => 0,
        ]);

        Auth::login($user);

        return redirect('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
        ]);

        $user = \App\Models\User::where('username', $validated['username'])
            ->orWhere('email', $validated['username'])
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'errors' => ['username' => ['Username/email tidak ditemukan.']]], 422);
        }

        $existing = PasswordResetRequest::where('id_user', $user->id)->where('status', 0)->first();

        if (!$existing) {
            PasswordResetRequest::create([
                'creation_time' => now(),
                'archived' => 0,
                'id_user' => $user->id,
                'status' => 0,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Permintaan reset password berhasil dikirim. Silakan hubungi Admin sekolah untuk password baru Anda.']);
    }
    
}