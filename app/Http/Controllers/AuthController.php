<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Memproses login pengguna.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ],
            [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'password.required' => 'Password wajib diisi.',
            ]
        );

        $loginData = [
            'email' => strtolower(trim($credentials['email'])),
            'password' => $credentials['password'],
        ];

        if (!Auth::attempt($loginData, $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah.',
                ])
                ->onlyInput('email');
        }

        // Mencegah session fixation setelah login.
        $request->session()->regenerate();

        $user = $request->user();

        /*
         * Admin selalu diarahkan ke dashboard admin.
         */
        if ($user->isAdmin()) {
            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'Selamat datang, '.$user->name.'!');
        }

        /*
         * Pelanggan dikembalikan ke halaman yang sebelumnya ingin dibuka.
         *
         * Contoh:
         * 1. Pelanggan membuka /booking.
         * 2. Middleware auth mengarahkannya ke /login.
         * 3. Setelah login, pelanggan otomatis kembali ke /booking.
         *
         * Jika tidak ada halaman sebelumnya, pelanggan diarahkan
         * ke halaman antrean saya.
         */
        return redirect()
            ->intended(route('queue.mine'))
            ->with('success', 'Selamat datang, '.$user->name.'!');
    }

    /**
     * Menampilkan halaman registrasi.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Memproses registrasi pelanggan.
     */
    public function register(Request $request)
    {
        $data = $request->validate(
            [
                'name' => ['required', 'string', 'max:100'],
                'phone' => [
                    'required',
                    'string',
                    'min:8',
                    'max:30',
                    'regex:/^[0-9+()\-\s]+$/',
                    'unique:users,phone',
                ],
                'email' => ['required', 'email', 'max:150', 'unique:users,email'],
                'password' => ['required', 'confirmed', 'min:8'],
            ],
            [
                'name.required' => 'Nama lengkap wajib diisi.',
                'name.max' => 'Nama maksimal 100 karakter.',

                'phone.required' => 'Nomor HP wajib diisi.',
                'phone.max' => 'Nomor HP maksimal 30 karakter.',

                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',

                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            ]
        );

        $user = User::create([
            'name' => trim($data['name']),
            'phone' => trim($data['phone']),
            'email' => strtolower(trim($data['email'])),
            'password' => Hash::make($data['password']),
            'role' => 'pelanggan',
        ]);

        Auth::login($user);

        // Membuat session login baru setelah registrasi.
        $request->session()->regenerate();

        /*
         * Jika pengguna mendaftar setelah mencoba membuka halaman booking,
         * pengguna akan dikembalikan ke halaman booking.
         *
         * Jika tidak ada halaman sebelumnya, diarahkan ke halaman utama.
         */
        return redirect()
            ->intended(route('home'))
            ->with('success', 'Pendaftaran berhasil! Selamat datang, '.$user->name.'!');
    }

    /**
     * Memproses logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Menghapus session lama.
        $request->session()->invalidate();

        // Membuat ulang token CSRF.
        $request->session()->regenerateToken();
        
        return redirect()
            ->route('home')
            ->with('success', 'Anda telah keluar.');
    }
}
