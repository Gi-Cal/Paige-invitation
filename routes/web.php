<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\RsvpController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [InvitationController::class, 'index'])->name('invitation');
Route::get('/home', [InvitationController::class, 'home'])->name('home');
Route::post('/rsvp', [RsvpController::class, 'store'])->name('rsvp.store');