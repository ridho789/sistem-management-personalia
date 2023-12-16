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
            switch (Auth::user()->level) {
                case 1:
                    return redirect()->intended('/dashboard');
                    break;
                case 2:
                    return redirect()->intended('/leaves-summary');
                    break;
                case 3:
                    return redirect()->intended('/list-daily-report');
                    break;
                default:
                    return redirect()->intended('/dashboard');
            }
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
