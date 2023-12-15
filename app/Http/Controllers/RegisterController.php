<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    public function index()
    {   
        $data = [
            'user' => Auth::user(), 
        ];

        $register = '';
        return view('backend/login/register', compact('register')
        );
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'min:3', 'max:255', 'unique:users'],
            'email' => 'required|email:dns|unique:users',
            'password' => 'required|min:5|max:255',
            'level' => 'required',
        ]);
    
        // Periksa email
        $existingUser = User::where('email', $request->email)->first();
    
        if ($existingUser) {
            return redirect('/registration')->with('error', 'Alamat email sudah terdaftar.');
        }
    
        // Hash password
        $validatedData['password'] = Hash::make($validatedData['password']);
    
        // Save new user
        User::create($validatedData);
    
        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
