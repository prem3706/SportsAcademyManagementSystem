<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LevelsController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PlayerFeesController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SportLevelController;
use App\Http\Controllers\SportsController;
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
        $stats = [
            'total_players' => \App\Models\User::where('role', 'player')->count(),
            'total_coaches' => \App\Models\User::where('role', 'coach')->count(),
            'total_sports' => \App\Models\Sport::count(),
            'total_batches' => \App\Models\Batch::count(),
            'total_fees_paid' => \App\Models\PlayerFee::where('status', 'paid')->sum('total_amt'),
            'total_fees_pending' => \App\Models\PlayerFee::where('status', 'pending')->sum('total_amt'),
            'recent_fees' => \App\Models\PlayerFee::with('player')->latest()->take(5)->get(),
            'recent_players' => \App\Models\User::where('role', 'player')->latest()->take(5)->get(),
            'recent_batches' => \App\Models\Batch::with(['sport', 'level', 'coaches'])->latest()->take(5)->get(),
        ];
        return view('dashboard', $stats);
    });

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::delete('/users/bulk-delete', [UserController::class, 'bulkDelete'])
        ->name('users.bulkDelete');

    Route::patch('/users/bulk-update', [UserController::class, 'bulkUpdate'])
        ->name('users.bulkUpdate');

    Route::resource('users', UserController::class);

    Route::delete('/sports/bulk-delete', [SportsController::class, 'bulkDelete'])
        ->name('sports.bulkDelete');

    Route::patch('/sports/bulk-update', [SportsController::class, 'bulkUpdate'])
        ->name('sports.bulkUpdate');

    Route::resource('sports', SportsController::class);

    Route::delete('/levels/bulk-delete', [LevelsController::class, 'bulkDelete'])
        ->name('levels.bulkDelete');

    Route::patch('/levels/bulk-update', [LevelsController::class, 'bulkUpdate'])
        ->name('levels.bulkUpdate');

    Route::resource('levels', LevelsController::class);

    Route::resource('sport-levels', SportLevelController::class);

    Route::get(
        'get-sport-levels/{id}',
        [BatchController::class, 'getSportLevels']
    );

    Route::delete('/batches/bulk-delete', [BatchController::class, 'bulkDelete'])
        ->name('batches.bulkDelete');

    Route::patch('/batches/bulk-update', [BatchController::class, 'bulkUpdate'])
        ->name('batches.bulkUpdate');

    Route::resource('batches', BatchController::class);

    Route::get('player-fees/player-details/{id}', [PlayerFeesController::class, 'getPlayerDetails'])
        ->name('player-fees.player-details');
    Route::resource('player-fees', PlayerFeesController::class);

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings/penalty', [SettingController::class, 'updatePenalty'])->name('settings.updatePenalty');
    Route::post('settings/discount', [SettingController::class, 'updateDiscount'])->name('settings.updateDiscount');

    Route::get('get-batches/{sport_id}/{level_id}', [PlayerController::class, 'getBatches'])->name('players.getBatches');
    Route::delete('/players/bulk-delete', [PlayerController::class, 'bulkDelete'])->name('players.bulkDelete');
    Route::patch('/players/bulk-update', [PlayerController::class, 'bulkUpdate'])->name('players.bulkUpdate');
    Route::resource('players', PlayerController::class);

});
