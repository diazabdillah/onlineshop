<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\CrudRepositories;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
class AccountController extends Controller
{
    protected $province;

    public function __construct()
    {
       
    }

    public function index()
    {
      $user = Auth::user();
      $profile = $user->profile()
      ->join('users', 'profile.user_id', '=', 'users.id') // Melakukan join dengan tabel users
      ->select('profile.*', 'users.name', 'users.email') // Memilih kolom yang diinginkan
      ->first(); // Ambil data profile dengan join ke users // Ambil data profile dengan join ke users // Ambil data profile dengan join ke users
   
        return view('frontend.account', compact('profile'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'province' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        // ... existing code ...
        $profile = Profile::where('user_id', Auth::id())->first(); // Mencari profil berdasarkan user_id
        if ($profile) {
            $profile->update($request->all()); // Memperbarui data profil
        } else {
            $profile = new Profile($request->all());
            $profile->user_id = Auth::id(); // Ambil ID user yang sedang login
            $profile->save();
        }
        return back()->with('success', 'Profil berhasil diperbarui.');
    }
    public function updateaccount(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'old_password' => 'required|string|min:8', // Menambahkan validasi untuk password lama
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Memverifikasi password lama
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Password lama tidak cocok.']);
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password); // Mengubah password jika diisi
        }

        $user->save();

        return back()->with('success', 'Akun berhasil diperbarui.');
    }
}
