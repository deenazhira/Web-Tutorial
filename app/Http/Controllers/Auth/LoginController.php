<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/todo';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

     // Override the login method
     public function login(Request $request)
     {
         $request->validate([
             'email' => 'required|email',
             'password' => 'required|string',
         ]);

         // Try to find the user by email
         $user = User::where('email', $request->email)->first();

         if ($user) {
             // Combine input password with salt, then check hash
             $saltedPassword = $request->password . $user->salt;

             if (Hash::check($saltedPassword, $user->password)) {
                 Auth::login($user, $request->filled('remember'));
                 return redirect()->intended($this->redirectTo);
             }
         }

         return back()->withErrors([
             'email' => 'The provided credentials do not match our records.',
         ])->withInput($request->only('email'));
     }
}
