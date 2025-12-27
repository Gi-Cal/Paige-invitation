<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\RsvpController;

Route::get('/', [InvitationController::class, 'index'])->name('invitation');
Route::get('/home', [InvitationController::class, 'home'])->name('home');
Route::post('/rsvp', [RsvpController::class, 'store'])->name('rsvp.store');

// Admin routes
Route::get('/admin/rsvps', [RsvpController::class, 'adminView'])->name('admin.rsvps');
Route::get('/admin/download-excel', [RsvpController::class, 'downloadExcel'])->name('admin.download');