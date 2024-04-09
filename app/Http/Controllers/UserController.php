<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use function Psy\debug;

class UserController extends Controller
{

    public function loginPage()
    {
        return Inertia::render('Login');
    }

    public function dashboardPage()
    {
        return Inertia::render('Dashboard');
    }

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

    public function logout()
    {

        auth()->logout();

        return redirect('/login');
    }

    public function goToLoginPage()
    {
        if (Auth::check() || Auth::viaRemember()) {
            return Inertia::render('Dashboard', ['content' => 'product']);
        }
        return Inertia::render('Login');
    }
    public function goToDashboardPage()
    {
        if (!Auth::check()) {
            return Inertia::render('Login');
        }
        return Inertia::render('Dashboard', ['content' => 'product']);
    }


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


        // Checks if login credentials are entered
        $incomingFields = $request->validate([
            'login_name' => ['required'],
            'login_password' => ['required'],

        ]);

        // Check if there is a remember me checked
        $rememberMe = $request->has('remember');

        // Username and password user authentication
        if (Auth::attempt([
            'username' => $incomingFields['login_name'],
            'password' => $incomingFields['login_password']
        ], $rememberMe)) {
            $request->session()->regenerate();
            Auth::user()->username;
        }
        // Email and password user authentication
        else if (Auth::attempt([
            'email' => $incomingFields['login_name'],
            'password' => $incomingFields['login_password']
        ], $rememberMe)) {
            $request->session()->regenerate();
            Auth::user()->username;
        }
        return redirect('dashboard');
    }
}
