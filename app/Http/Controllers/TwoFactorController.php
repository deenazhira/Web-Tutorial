<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class TwoFactorController extends Controller
{
    public function showTwoFactorForm()
    {
        return view('auth.two-factor-challenge');
    }

    public function sendTwoFactorCode()
    {
        $user = Auth::user();
        $code = rand(100000, 999999);

        $user->update([
            'two_factor_code' => $code,
            'two_factor_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::raw("Your verification code is: $code", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Your 2FA Code');
        });

        return redirect()->route('two-factor.index')->with('message', 'Verification code sent to your email.');
    }

    public function verifyTwoFactorCode(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required',
        ]);

        $user = Auth::user();

        if ($request->two_factor_code === $user->two_factor_code &&
            Carbon::now()->lt($user->two_factor_expires_at)) {
            $user->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]);

            return redirect()->intended('/home');
        }

        return back()->withErrors(['two_factor_code' => 'The verification code is invalid or has expired.']);
    }
}
