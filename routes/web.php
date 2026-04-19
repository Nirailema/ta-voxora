<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\User\DocumentController;
use App\Http\Controllers\User\LibraryController;
use App\Http\Controllers\User\ConversationController;
use App\Http\Controllers\User\BrailleController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::post('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
        Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
        Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
        Route::post('/devices/{device}/toggle', [DeviceController::class, 'toggleStatus'])->name('devices.toggle');
        Route::post('/devices/{device}', [DeviceController::class, 'update'])->name('devices.update');
        Route::delete('/devices/{device}', [DeviceController::class, 'destroy'])->name('devices.destroy');
    });

    // User panel
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/upload', [DocumentController::class, 'showUpload'])->name('upload');
        Route::post('/upload', [DocumentController::class, 'upload'])->name('upload.submit');
        Route::get('/library', [LibraryController::class, 'index'])->name('library');
        Route::get('/conversation/{document}', [ConversationController::class, 'show'])->name('conversation.show');
        Route::post('/conversation/{document}/ask', [ConversationController::class, 'ask'])->name('conversation.ask');
        Route::get('/conversation/{document}/export', [ConversationController::class, 'exportToWord'])->name('conversation.export');
        Route::post('/conversation/{document}/clear', [ConversationController::class, 'clearConversation'])->name('conversation.clear');
        Route::get('/braille/{document}', [BrailleController::class, 'index'])->name('braille');
        Route::post('/braille/{document}/convert', [BrailleController::class, 'convert'])->name('braille.convert');
        Route::post('/braille/{document}/send', [BrailleController::class, 'sendToDevice'])->name('braille.send');
    });
});
