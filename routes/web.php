<?php

use App\Livewire\ExternalDashboard;
use App\Livewire\ExternalLogin;
use App\Livewire\JoinTeam;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Google Authentication Routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Join Team Route
Route::get('/join-team', JoinTeam::class)->name('join-team');

// External Dashboard Routes
Route::prefix('external')->name('external.')->group(function () {
    Route::get('/{token}', ExternalLogin::class)->name('login');
    Route::get('/{token}/dashboard', ExternalDashboard::class)->name('dashboard');
});
