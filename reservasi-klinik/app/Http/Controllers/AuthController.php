<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Patient;

class AuthController extends Controller
{
    // REGISTRASI PASIEN
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed', 
            'nik' => 'required|numeric|unique:patients,nik',
            'phone' => 'required',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'address' => 'required',
        ]);

        DB::beginTransaction(); 

        try {
            // 1. Buat User Login
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pasien'
            ]);

            // 2. Buat Profil Pasien
            Patient::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'nik' => $request->nik,
                'phone' => $request->phone,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
            ]);

            DB::commit();
            return redirect('/login-pasien')->with('success', 'Akun berhasil dibuat. Silakan login.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // LOGIN MULTI-ROLE
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $role = Auth::user()->role;

            // Redirect sesuai peran
            switch ($role) {
                case 'pasien':
                    return redirect('/pasien/dashboard');
                case 'dokter':
                    return redirect('/dokter/dashboard');
                case 'staff':
                case 'admin':
                    return redirect('/staff/dashboard');
                default:
                    Auth::logout();
                    return back()->with('error', 'Peran akun tidak dikenali.');
            }
        }

        return back()->withErrors(['email' => 'Kombinasi email dan password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}