<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DialController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AccountController::class, 'index'])->name('home');
Route::post('/accounts', [AccountController::class, 'create'])->name('accounts.create');

Route::get('/dials', [DialController::class, 'index'])->name('dials.index');
Route::post('/dials', [DialController::class, 'create'])->name('dials.create');
