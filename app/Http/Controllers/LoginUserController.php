<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginUserController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8, string'
        ]);

        //log in the user
        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended(route('posts.index'));
        } else {
            return back()->withErrors([
                'email' => 'The provided credentials are not valid.',
            ]);
        }
    }
    public function logout(Request $request)
    {
        // we are working with web not API
        Auth::guard('web')->logout();

        // destroy the seession
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('posts.index');
    }
}
