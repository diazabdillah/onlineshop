<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\CrudRepositories;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Services\Rajaongkir\RajaongkirService;
use Illuminate\Validation\Rules;
class AccountController extends Controller
{

    protected $province,$rajaongkirService;
    public function __construct(RajaongkirService $rajaongkirService)
    {

        $this->rajaongkirService = $rajaongkirService;
      
    }


    public function index()
    {
        $data['provinces'] = $this->rajaongkirService->getProvince();
      $user = Auth::user();
      $profile = $user->profile()
      ->join('users', 'profile.user_id', '=', 'users.id') // Melakukan join dengan tabel users
      ->select('profile.*', 'users.name', 'users.email') // Memilih kolom yang diinginkan
      ->first(); // Ambil data profile dengan join ke users // Ambil data profile dengan join ke users // Ambil data profile dengan join ke users
   
        return view('frontend.account', compact('profile','data'));
    }
  // ... existing code ...
  public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'province' => 'required|string',
            'city' => 'required|string',
            'id_province' => 'required|integer',
            'id_city' => 'required|integer',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'bank_account' => 'required|string',
            'bank_book_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $profile = auth()->user()->profile;

        $profile->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'province' => $request->province,
            'city' => $request->city,
            'id_province' => $request->id_province,
            'id_city' => $request->id_city,
            'phone' => $request->phone,
            'address' => $request->address,
            'bank_account' => $request->bank_account,
        ]);

        if ($request->hasFile('bank_book_image')) {
            if ($profile->bank_book_image) {
                Storage::delete('public/' . $profile->bank_book_image);
            }
            $imagePath = $request->file('bank_book_image')->store('bank_books', 'public');
            $profile->update(['bank_book_image' => $imagePath]);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
  
// ... existing code ...
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
