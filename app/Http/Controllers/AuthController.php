<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // copied from my Lab 9
    // just shows the form, the guest middleware keeps logged in users out
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // if this fails laravel goes back to the form with the errors
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Auth::attempt finds the user by email and checks the hashed password
        if (Auth::attempt($credentials)) {
            // new session id after login to prevent session fixation
            $request->session()->regenerate();

            // intended = go back to the page they wanted (ex. /posts/create), else /posts
            return redirect()->intended('/posts');
        }

        // wrong email or password, onlyInput keeps the email but not the password
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // POST not GET so a random link can't log someone out
    public function logout(Request $request)
    {
        Auth::logout();

        // clear the session and make a new csrf token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/posts');
    }
}
