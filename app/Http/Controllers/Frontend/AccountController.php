<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\CrudRepositories;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $province;

    public function __construct()
    {
       
    }

    public function index()
    {
      $user = Auth::user();
      $profile = $user->profile; // Ambil data profile dari relasi
        return view('frontend.account', compact('profile'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'province' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        // Simpan data profile dengan user_id dari user yang sedang login
        $profile = new Profile($request->all());
        $profile->user_id = Auth::id(); // Ambil ID user yang sedang login
        $profile->save();

        return redirect()->route('profiles.create')
                         ->with('success', 'Profile created successfully.');
    }
    
}
