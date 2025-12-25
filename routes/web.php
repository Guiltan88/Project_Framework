<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');
Route::get('property',[PropertyController::class, 'index'])->name('property');
Route::get('room',[RoomController::class, 'index'])->name('room');
Route::get('staff',[StaffController::class, 'index'])->name('staff');
Route::get('guest',[GuestController::class, 'index'])->name('guest');

