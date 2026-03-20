<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Middleware\RedirectIfAuthenticated;

class LoginRegisterController extends Controller
{
    /**
     * Instantiate a new LoginRegisterController instance.
     */
    public function __construct()
    {


    }

    /**
     * Display a login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials))
        {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')
                ->withSuccess('Je bent nu ingelogd!');
        }

        return back()->withErrors([
            'email' => 'Wachtwoord of e-mail adres is onjuist.',
        ])->onlyInput('email');

    } 
    
    /**
     * Display a dashboard to authenticated users.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if(Auth::check())
        {
            return view('admin.dashboard');
        }
        
        return redirect()->route('login')
            ->withErrors([
            'email' => 'Log in op bij het administrator paneel te komen.',
        ])->onlyInput('email');
    } 
    
    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('Je bent nu ingelogd!');;
        
    }   
    


    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $tableIsEmpty = User::count() === 0;

        // if (!$tableIsEmpty){
        //     return redirect()->route('login');
        // } 

        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_role' => $request->user_role,
            'employee_code' => 'TEMP001'    
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('admin.dashboard')
        ->withSuccess('U bent nu geregistreerd en ingelogd!');
    }

}