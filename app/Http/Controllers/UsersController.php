<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index() {
        $users = User::where('level', '!=', '0')->get();

        return view('backend/master/users', [
            'users' => $users
        ]);
    }

    public function store(Request $request) {
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
    
        return redirect('/users');
    }

    public function update(Request $request) {
        $existingUser = User::where('id', $request->id)->first();
    
        $rules = [
            'name' => 'min:3|max:255|unique:users,name,' . $existingUser->id,
            'email' => 'email|unique:users,email,' . $existingUser->id,
        ];
    
        try {
            $request->validate($rules);
    
            $existingUser->update([
                'name' => $request->val_name,
                'email' => $request->val_email,
                'level' => $request->val_level,
            ]);
    
            return redirect('/users');

        } catch (QueryException $e) {
            $sqlErrors = $e->getMessage();
            session()->flash('error', 'Failed to update user. SQL Error: ' . $sqlErrors);

            return redirect('/users');
        }
    }

    public function delete($id) {
        User::where('id', $id)->delete();
        return redirect('/users');
    }
}
