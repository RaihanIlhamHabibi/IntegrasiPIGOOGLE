<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\GoogleDriveController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::prefix('auth')->group(function () {
    Route::get('google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return view('dashboard', [
            'driveCount' => $user->googleDriveFiles()->count(),
            'eventCount' => $user->googleCalendarEvents()->count(),
            'hasGoogle' => (bool) $user->googleToken,
        ]);
    })->name('dashboard');

    Route::middleware('google.connected')->prefix('google-drive')->group(function () {
        Route::get('/', [GoogleDriveController::class, 'index'])->name('google-drive.index');
        Route::post('upload', [GoogleDriveController::class, 'upload'])->name('google-drive.upload');
        Route::delete('files/{id}', [GoogleDriveController::class, 'delete'])->name('google-drive.delete');
        Route::get('list', [GoogleDriveController::class, 'list'])->name('google-drive.list');
    });

    Route::middleware('google.connected')->prefix('google-calendar')->group(function () {
        Route::get('/', [GoogleCalendarController::class, 'index'])->name('google-calendar.index');
        Route::get('list', [GoogleCalendarController::class, 'list'])->name('google-calendar.list');
        Route::get('create', [GoogleCalendarController::class, 'create'])->name('google-calendar.create');
        Route::post('store', [GoogleCalendarController::class, 'store'])->name('google-calendar.store');
        Route::get('{id}/edit', [GoogleCalendarController::class, 'edit'])->name('google-calendar.edit');
        Route::put('{id}', [GoogleCalendarController::class, 'update'])->name('google-calendar.update');
        Route::delete('{id}', [GoogleCalendarController::class, 'delete'])->name('google-calendar.delete');
    });
});
