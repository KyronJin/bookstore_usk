<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class AuthController extends Controller
{
    // ====== Halaman Login USER ======
    public function showLogin()
    {
        // Sudah login sebagai user biasa → ke home
        if (Auth::check() && Auth::user()->isUser()) {
            return redirect()->route('home');
        }
        // Sudah login sebagai admin → arahkan ke admin login
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    // ====== Proses Login USER ======
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Jika yang login adalah admin, logout dan arahkan ke admin login
            if (Auth::user()->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                return redirect()->route('admin.login')
                    ->with('error', 'Akun admin tidak bisa login di sini. Gunakan halaman login admin.');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('home'))
                ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah. Silakan coba lagi.',
        ])->onlyInput('email');
    }

    // ====== Halaman Register USER ======
    public function showRegister()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }
        return view('auth.register');
    }

    // ====== Proses Register USER ======
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(6)],
            'phone'    => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => 'user',
            'phone'    => $request->phone,
        ]);

        Auth::login($user);
        return redirect()->route('home')->with('success', 'Akun berhasil dibuat! Selamat berbelanja.');
    }

    // ====== Logout USER ======
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }
}
