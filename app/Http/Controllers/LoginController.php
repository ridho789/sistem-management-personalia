<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('backend/login/login', [
            'title' => 'Login',
        ]);

    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' =>'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        return back()->with('loginError', 'Login failed');
    }

    public function logout()
    {
        Auth::logout();
 
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    }
}
