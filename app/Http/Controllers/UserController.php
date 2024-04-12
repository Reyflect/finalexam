<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Session\Session;



class UserController extends Controller
{

    /**
     * renders login page
     */
    public function loginPage()
    {
        return Inertia::render('Login');
    }

    /**
     * renders dashboard page
     */
    public function dashboardPage()
    {
        return Inertia::render('Dashboard');
    }

    /**
     * creates a new user
     * @param Request $request of current instance 
     */
    public function register(Request $request)
    {

        // Validates fields
        $incomingFields = $request->validate([
            'name' => ['required',  Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required']
        ]);

        // Encrypts password
        $incomingFields['password'] = bcrypt($incomingFields['password']);

        // Creates user
        $user = User::create($incomingFields);

        auth()->login($user);

        return redirect('/');
    }

    /**
     * logouts the user
     */
    public function logout()
    {

        auth()->logout();

        return redirect('/login');
    }

    /**
     * Checks the user's seesion
     */
    public function checkSession()
    {

        // Checks if the user needs to login again
        if (Auth::check() || Auth::viaRemember()) {
            return Inertia::render('Dashboard', ['content' => 'product']);
        } else {
            return Inertia::render('Login');
        }
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
            // Authentication successful, authorize user

            $this->authorizeUser($request);
        }

        // Authentication failed, redirect back with error
        return back()->withErrors(['login_name' => 'Invalid credentials']);
    }

    public function authorizeUser($request)
    {
        $request->session()->regenerate();

        return redirect('dashboard');
    }

    public static function viewUserId()
    {
        return auth()->id();
    }
}
