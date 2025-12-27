<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\RsvpController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [InvitationController::class, 'index'])->name('invitation');
Route::get('/home', [InvitationController::class, 'home'])->name('home');

// RSVP routes
Route::post('/rsvp', [RsvpController::class, 'store'])->name('rsvp.store');
Route::get('/rsvp', function() {
    return redirect()->route('home');
});

// Admin routes (with middleware check)
Route::middleware(['web'])->group(function () {
    Route::get('/admin/rsvps', [RsvpController::class, 'adminView'])->name('admin.rsvps');
    Route::get('/admin/download-excel', [RsvpController::class, 'downloadExcel'])->name('admin.download');
    Route::get('/admin/logout', [RsvpController::class, 'logout'])->name('admin.logout');
});