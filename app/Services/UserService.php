<?php

namespace App\Services;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserService
{
    public static function loggedUserId()
    {
        return auth()->id();
    }

    public function login(Request $request)
    {

        // Validate login credentials
        $incomingFields = $request->validate([
            'login_name' => ['required'],
            'login_password' => ['required'],
        ]);

        // Check if "remember me" is checked
        $rememberMe = $request->has('remember');

        // Attempt to authenticate with username or email
        if (
            Auth::attempt(['username' => $incomingFields['login_name'], 'password' => $incomingFields['login_password']], $rememberMe)
            || Auth::attempt(['email' => $incomingFields['login_name'], 'password' => $incomingFields['login_password']], $rememberMe)
        ) {


            $request->session()->regenerate();

            return redirect('dashboard');
        }

        // Authentication failed, redirect back with error
        return back()->withErrors(['login_name' => 'Invalid credentials']);
    }

    public function isUserLoggedIn()
    {
        // Checks if the user needs to login again
        if (Auth::check() || Auth::viaRemember()) {
            return Inertia::render('Dashboard', ['content' => 'product']);
        } else {
            return Inertia::render('Login');
        }
    }

    public function logout()
    {
        auth()->logout();

        return redirect('/login');
    }
}
