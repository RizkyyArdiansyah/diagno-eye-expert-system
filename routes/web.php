<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DiagnosaController;


Route::get('/', [DiagnosaController::class, 'index'])->name('home');

// AUTH (Register & Login)
Route::post('/register', [UserController::class, 'register'])->name('register.submit');
Route::get('/login', [DiagnosaController::class, 'index'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.submit');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// 🧑‍⚕️ Route untuk Admin
Route::middleware(['auth', 'cekrole:admin'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');

    // Gejala
    Route::post('/gejala/store', [UserController::class, 'storeGejala'])->name('gejala.store');
    Route::put('/gejala/update/{id}', [UserController::class, 'updateGejala'])->name('gejala.update');
    Route::delete('/dashboard/gejala/{id}', [UserController::class, 'destroyGejala'])->name('gejala.destroy');

    // Penyakit
    Route::post('/penyakit', [UserController::class, 'storePenyakit'])->name('penyakit.store');
    Route::put('/penyakit/update/{id}', [UserController::class, 'updatePenyakit'])->name('penyakit.update');
    Route::delete('/penyakit/delete/{id}', [UserController::class, 'destroyPenyakit'])->name('penyakit.destroy');

    // Aturan
    Route::post('/aturan/store', [UserController::class, 'storeAturan'])->name('aturan.store');
    Route::put('/aturan/update/{id}', [UserController::class, 'updateAturan'])->name('aturan.update');
    Route::delete('/aturan/delete/{id}', [UserController::class, 'destroyAturan'])->name('aturan.destroy');

    // User
    Route::post('/user/store', [UserController::class, 'storeUser'])->name('user.store');
    Route::delete('/user/delete/{id}', [UserController::class, 'destroyUser'])->name('user.destroy');
});

// 👤 Route untuk User biasa
Route::middleware(['auth', 'cekrole:user'])->group(function () {
    Route::get('/diagnosa', [DiagnosaController::class, 'index'])->name('home');
    Route::post('/diagnosa', [DiagnosaController::class, 'prosesDiagnosa'])->name('diagnosa.proses');
});


