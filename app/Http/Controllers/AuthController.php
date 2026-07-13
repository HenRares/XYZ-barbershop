<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }

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
        if (! Auth::attempt(['email' => strtolower($credentials['email']), 'password' => $credentials['password']], $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password salah'])->onlyInput('email');
        }
        $request->session()->regenerate();
        $target = $request->user()->isAdmin() ? route('admin.dashboard') : route('queue.mine');
        return redirect()->intended($target)->with('success', 'Selamat datang, '.$request->user()->name.'!');
    }

    public function showRegister() { return view('auth.register'); }

    public function register(Request $request)
    {
        // $data = $request->validate([
        //     'name' => ['required', 'string', 'max:100'],
        //     'phone' => ['required', 'string', 'max:30'],
        //     'email' => ['required', 'email', 'max:150', 'unique:users,email'],
        //     'password' => ['required', 'confirmed', 'min:6'],
        // ]);

        $data = $request->validate(
            [
                'name' => ['required', 'string', 'max:100'],
                'phone' => ['required', 'string', 'max:30'],
                'email' => ['required', 'email', 'max:150', 'unique:users,email'],
                'password' => ['required', 'confirmed', 'min:6'],
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
                'password.min' => 'Password minimal 6 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            ]
        );

        $user = User::create([
            'name' => $data['name'], 'phone' => $data['phone'], 'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']), 'role' => 'pelanggan',
        ]);
        Auth::login($user); $request->session()->regenerate();
        return redirect()->route('home')->with('success', 'Pendaftaran berhasil!');
    }

    public function logout(Request $request)
    {
        Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Anda telah keluar.');
    }
}
