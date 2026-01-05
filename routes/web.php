<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// ADMIN CONTROLLERS
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\StaffController as AdminStaffController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Admin\BuildingController as AdminBuildingController;

// STAFF CONTROLLERS
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\ProfileController as StaffProfileController;
use App\Http\Controllers\Staff\BookingController as StaffBookingController;
use App\Http\Controllers\Staff\RoomController as StaffRoomController;

// GUEST CONTROLLERS
use App\Http\Controllers\Guest\DashboardController as GuestDashboardController;
use App\Http\Controllers\Guest\BookingController as GuestBookingController;
use App\Http\Controllers\Guest\RoomController as GuestRoomController;
use App\Http\Controllers\Guest\ProfileController as GuestProfileController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTH — GUEST ONLY
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Register
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    // Reset Password
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | ONE ENTRY DASHBOARD (ANTI BUG ROLE)
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isStaff()) {
            return redirect()->route('staff.dashboard');
        }
        return redirect()->route('guest.dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // Dashboard Admin
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])
                ->name('dashboard');

            Route::prefix('profile')->name('profile.')->group(function () {
                Route::get('/', [AdminProfileController::class, 'index'])->name('index');
                Route::get('/edit', [AdminProfileController::class, 'edit'])->name('edit');
                Route::put('/update', [AdminProfileController::class, 'update'])->name('update');
                Route::post('/remove-photo', [AdminProfileController::class, 'removePhoto'])->name('remove-photo');
            });


            // Staff Management (CRUD)
            Route::resource('users', AdminUserController::class);

            // Master Data
            Route::resource('rooms', AdminRoomController::class);
            Route::resource('facilities', AdminFacilityController::class);
            Route::resource('buildings', AdminBuildingController::class);

            Route::resource('bookings', AdminBookingController::class)->except(['create', 'store', 'edit', 'update']);
            Route::post('bookings/{booking}/approve', [AdminBookingController::class, 'approve'])->name('bookings.approve');
            Route::post('bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');
            Route::post('bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
            Route::get('bookings/export', [AdminBookingController::class, 'export'])->name('bookings.export');
            Route::get('bookings/statistics', [AdminBookingController::class, 'getStatistics'])->name('bookings.statistics');
    });

    /*
    |--------------------------------------------------------------------------
    | STAFF
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:staff')
        ->prefix('staff')
        ->name('staff.')
        ->group(function () {

            // Dashboard Staff
            Route::get('/dashboard', [StaffDashboardController::class, 'index'])
                ->name('dashboard');

            // Profile Staff - PERBAIKAN: TAMBAHKAN ROUTE remove-photo
            Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [StaffProfileController::class, 'index'])->name('index');
            Route::get('/edit', [StaffProfileController::class, 'edit'])->name('edit');
            Route::put('/update', [StaffProfileController::class, 'update'])->name('update');
            Route::post('/remove-photo', [StaffProfileController::class, 'removePhoto'])
                ->name('remove-photo'); // NAMA ROUTE DIPERBAIKI
        });

            // Bookings (Approve/Reject only)
            Route::get('/bookings', [StaffBookingController::class, 'index'])->name('bookings.index');
            Route::get('/bookings/{booking}', [StaffBookingController::class, 'show'])->name('bookings.show');
            Route::post('/bookings/{booking}/approve', [StaffBookingController::class, 'approve'])
                ->name('bookings.approve');
            Route::post('/bookings/{booking}/reject', [StaffBookingController::class, 'reject'])
                ->name('bookings.reject');
        });

    /*
    |--------------------------------------------------------------------------
    | GUEST
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:guest')
        ->prefix('guest')
        ->name('guest.')
        ->group(function () {

            // Dashboard Guest
            Route::get('/dashboard', [GuestDashboardController::class, 'index'])
                ->name('dashboard');

            // Profile Guest
            Route::prefix('profile')->name('profile.')->group(function () {
                Route::get('/', [GuestProfileController::class, 'index'])->name('index');
                Route::get('/edit', [GuestProfileController::class, 'edit'])->name('edit');
                Route::put('/update', [GuestProfileController::class, 'update'])->name('update'); // PERUBAHAN: dari '/' menjadi '/update'
                Route::post('/remove-photo', [GuestProfileController::class, 'removePhoto'])->name('remove-photo'); // TAMBAHKAN
            });

            // Rooms (Browse available)
            Route::get('/rooms', [GuestRoomController::class, 'index'])->name('rooms.index');
            Route::get('/rooms/{room}', [GuestRoomController::class, 'show'])->name('rooms.show');

            // Bookings
            Route::get('/bookings/history', [GuestBookingController::class, 'index'])
                ->name('bookings.history');
            Route::get('/bookings/{booking}', [GuestBookingController::class, 'show'])
                ->name('bookings.show');
            Route::get('/bookings/{booking}/edit', [GuestBookingController::class, 'edit'])
                ->name('bookings.edit');
            Route::put('/bookings/{booking}', [GuestBookingController::class, 'update'])
                ->name('bookings.update');
            Route::post('/bookings/{booking}/cancel', [GuestBookingController::class, 'cancel'])
                ->name('bookings.cancel');
        });

    /*
    |--------------------------------------------------------------------------
    | BOOKING CREATE (ALL AUTH)
    |--------------------------------------------------------------------------
    */
    Route::get('/bookings/create', [GuestBookingController::class, 'create'])
        ->name('bookings.create');
    Route::post('/bookings', [GuestBookingController::class, 'store'])
        ->name('bookings.store');
});

/*
|--------------------------------------------------------------------------
| FALLBACK (ANTI LOOP / 404)
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});
