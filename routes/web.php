<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::view('/login', 'authentication.login')->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    Route::view('/register', 'authentication.register');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])
        ->name('forget.password.get');

    Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])
        ->name('forget.password.post');

    Route::get('/reset-password/{token}', function (string $token) {
        return view('authentication.forgetPasswordLink', ['token' => $token]);
    })->name('password.reset');

    Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])
        ->name('reset.password.post');

});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::delete('/users/bulk-delete', [UserController::class, 'bulkDelete'])
        ->name('users.bulkDelete');

    Route::patch('/users/bulk-update', [UserController::class, 'bulkUpdate'])
        ->name('users.bulkUpdate');

    Route::resource('users', UserController::class);
});
