<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    public function __construct(
        public UserService $services
    ) {
    }
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
     * logouts the user
     */
    public function logout()
    {
        return $this->services->logout();
    }

    /**
     * Checks the user's seesion
     */
    public function checkSession()
    {
        return $this->services->isUserLoggedIn();
    }

    /**
     * logs in the user to the system
     * @param Request $request
     */
    public function login(Request $request)
    {
        return $this->services->login($request);
    }

    /**
     * returns the logged in user id 
     */
    public  function viewUserId()
    {
        return $this->services->loggedUserId();
    }
}
