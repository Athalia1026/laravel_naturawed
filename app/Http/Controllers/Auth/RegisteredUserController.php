<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CustomerProfile;
use App\Models\VendorProfile;
use App\Models\JournalistProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request)
    {
        // Tangkap role dari URL, jika tidak ada default ke 'customer' (couple)
    $role = $request->query('role', 'customer');

    // Kirimkan variabel $role ke dalam file blade register
    return view('auth.register', compact('role'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi Input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:customer,vendor,journalist'],
        ]);

        // 2. Eksekusi Transaksi Database (Akun & Profil NaturaWed)
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            // Pecah data ke tabel profil sesuai Role
            if ($request->role === 'vendor') {
                VendorProfile::create(['user_id' => $user->id, 'business_name' => $request->name]);
            } elseif ($request->role === 'journalist') {
                JournalistProfile::create(['user_id' => $user->id, 'full_name' => $request->name]);
            } else {
                CustomerProfile::create(['user_id' => $user->id, 'full_name' => $request->name]);
            }

            return $user;
        });

        event(new Registered($user));

        // 3. Login otomatis setelah daftar
        Auth::login($user);

        // 4. Redirect berdasarkan role
        if ($user->role === 'vendor') {
            return redirect()->intended('/dashboard-vendor'); // Sesuaikan URL rute Anda
        } elseif ($user->role === 'journalist') {
            return redirect()->intended('/journalist-dashboard');
        }

        return redirect()->intended('/');
    }
}