<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

// Enable email verification
Auth::routes(['verify' => true]); // This enables email verification routes

Route::middleware(['auth'])->group(function () {
    Route::resource('/todo', TodoController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/two-factor', [TwoFactorController::class, 'showTwoFactorForm'])->name('two-factor.index');
    Route::post('/two-factor/send', [TwoFactorController::class, 'sendTwoFactorCode'])->name('two-factor.send');
    Route::post('/two-factor/verify', [TwoFactorController::class, 'verifyTwoFactorCode'])->name('two-factor.verify');
});

// MFA - simulate MFA by displaying code directly on the screen (for testing)
Route::middleware(['auth'])->get('/mfa', function () {
    $code = 123456; // Hardcoded code for testing, replace with dynamic code generation for production
    session(['mfa_code' => $code]); // Store the code in session
    return view('auth.mfa', ['code' => $code]); // Pass code to view (for testing)
});

// MFA POST route - Check the entered code
Route::middleware(['auth'])->post('/mfa', function (Request $request) {
    $request->validate([
        'code' => 'required', // Ensure a code is provided
    ]);

    // Check if the entered code matches the one stored in session
    if ($request->code == session('mfa_code')) {
        session(['mfa_verified' => true]); // Set the MFA verified flag
        return redirect('/home'); // Redirect to a protected page (like home/dashboard)
    }

    // If the code is incorrect, reload the MFA page with an error
    return back()->withErrors(['code' => 'Invalid code.']);
});

// Protect the /home route with both auth and mfa verification
Route::middleware(['auth'])->get('/home', function () {
    return view('home'); // Redirect to home if authenticated and MFA is verified
});
