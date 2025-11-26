<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user(); 

            // Redirect sesuai role dengan pesan SUKSES
            if($user->role === 'admin'){
                return redirect()->intended('/admin/dashboard')->with('success', 'Login berhasil! Selamat datang Admin.');
            }
            return redirect()->intended('/')->with('success', 'Login berhasil! Selamat berbelanja.');
        }

        // Jika GAGAL
        return back()->with('loginError', 'Login Gagal! Username atau password salah.');
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        $credentials = $request->only('name', 'password');
        Auth::attempt($credentials);

        // Redirect setelah register dengan pesan SUKSES
        return redirect('/')->with('success', 'Registrasi berhasil! Akun Anda telah dibuat.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // Redirect setelah logout
        return redirect('/login')->with('success', 'Anda berhasil logout.');
    }
}