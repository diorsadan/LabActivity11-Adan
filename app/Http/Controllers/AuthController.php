<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // copied from my Lab 9
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Auth::attempt finds the user by email and checks the hashed password
        if (Auth::attempt($credentials)) {
            // new session id after login to prevent session fixation
            $request->session()->regenerate();

            return redirect()->intended('/posts');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // clear the session and make a new csrf token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/posts');
    }
}
