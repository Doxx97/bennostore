<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password; // Tambahan untuk Reset Password
use Illuminate\Auth\Events\PasswordReset; // Tambahan untuk Event Reset
use Illuminate\Support\Str; // Tambahan untuk Random String
use App\Models\User;

class AuthController extends Controller
{
    // BAGIAN 1: LOGIN
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

    // BAGIAN 2: REGISTER
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

    // BAGIAN 3: LOGOUT
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // Redirect setelah logout
        return redirect('/login')->with('success', 'Anda berhasil logout.');
    }

    // BAGIAN 4: LUPA PASSWORD / RESET PASSWORD (BARU)
    // A. Tampilkan Form Input Email
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // B. Proses Kirim Email Link Reset
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Kirim link reset password ke email
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(['status' => __($status)]);
        }

        return back()->withErrors(['email' => __($status)]);
    }

    // C. Tampilkan Form Input Password Baru (Setelah klik link di email)
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    // D. Proses Simpan Password Baru ke Database
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Proses reset password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        // Redirect jika berhasil
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password berhasil diubah! Silakan login dengan password baru.');
        }

        return back()->withErrors(['email' => [__($status)]]);
    }
}