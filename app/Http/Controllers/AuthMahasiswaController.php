<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthMahasiswaController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {
            return redirect()->route('mahasiswa.dashboard');
        }

        return view('auth.login');
    }

    public function showRegister()
    {
        if (auth()->check()) {
            return redirect()->route('mahasiswa.dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'nim' => 'required|string|max:20|unique:user,nim',
            'email' => 'required|email|max:150|unique:user,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'nama_lengkap' => $validated['nama_lengkap'],
                'nim' => $validated['nim'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            DB::table('user_role')->insert([
                'user_id' => $user->id_user,
                'role_id' => 1,
            ]);

            DB::commit();

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('mahasiswa.dashboard')
                ->with('success', 'Registrasi mahasiswa berhasil.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Registrasi gagal: ' . $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('nim', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withInput()
                ->withErrors([
                    'login' => 'Email/NIM atau password salah.',
                ]);
        }

        if (!$user->isMahasiswa()) {
            return back()
                ->withInput()
                ->withErrors([
                    'login' => 'Login ini hanya untuk mahasiswa.',
                ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('mahasiswa.dashboard');
    }

    public function dashboard()
    {
        if (!auth()->check() || !auth()->user()->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        return view('mahasiswa.dashboard');
    }

    public function fasilitas()
    {
        if (!auth()->check() || !auth()->user()->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        return view('mahasiswa.fasilitas');
    }

    public function jadwal()
    {
        if (!auth()->check() || !auth()->user()->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        return view('mahasiswa.jadwal');
    }

    public function pengajuan()
    {
        if (!auth()->check() || !auth()->user()->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        return view('mahasiswa.pengajuan');
    }

    public function pengembalian()
    {
        if (!auth()->check() || !auth()->user()->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        return view('mahasiswa.pengembalian');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
