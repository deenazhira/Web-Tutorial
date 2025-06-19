<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication with email verification
Auth::routes(['verify' => true]);

// Grouped Auth Routes
Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Two-Factor Authentication (MFA) routes
    Route::get('/two-factor', [TwoFactorController::class, 'showTwoFactorForm'])->name('two-factor.index');
    Route::post('/two-factor/send', [TwoFactorController::class, 'sendTwoFactorCode'])->name('two-factor.send');
    Route::post('/two-factor/verify', [TwoFactorController::class, 'verifyTwoFactorCode'])->name('two-factor.verify');

    // MFA Simulation (testing only)
    Route::get('/mfa', function () {
        $code = 123456;
        session(['mfa_code' => $code]);
        return view('auth.mfa', ['code' => $code]);
    });

    Route::post('/mfa', function (Request $request) {
        $request->validate(['code' => 'required']);
        if ($request->code == session('mfa_code')) {
            session(['mfa_verified' => true]);
            return redirect('/home');
        }
        return back()->withErrors(['code' => 'Invalid code.']);
    });

    // Dashboard or home
    Route::get('/home', function () {
        return view('home');
    });

    // Todo routes for users only
    Route::middleware(['auth', 'role:user'])->group(function () {
        Route::resource('/todo', TodoController::class);
    });

    // Admin dashboard
    Route::middleware(['role:admin'])->get('/admin', function () {
        return view('admin.index');
    });

    Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/assign-role', [AdminController::class, 'assignRole'])->name('admin.assignRole');
    Route::post('/admin/toggle-status/{id}', [AdminController::class, 'toggleStatus'])->name('admin.toggleStatus');
});
});
