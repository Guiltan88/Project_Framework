<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PropertyController;

/*
|--------------------------------------------------------------------------
| Web Routes (Testing Mode - No Middleware)
|--------------------------------------------------------------------------
*/

// Landing
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// Property
Route::get('/property', [PropertyController::class, 'index'])
    ->name('property');

// ================= ROOM MANAGEMENT =================
Route::resource('room', RoomController::class)->names('Room');


// Staff & Guest
Route::get('/staff', [StaffController::class, 'index'])->name('staff');
Route::get('/guest', [GuestController::class, 'index'])->name('guest');
