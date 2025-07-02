<?php

use App\Http\Controllers\PatientsSchedulesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SchedulesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/users', UsersController::class);

    Route::resource('/schedules-doctor', SchedulesController::class);
    Route::get('/schedules-doctor', [SchedulesController::class, 'index'])->name('schedules.doctor');

    Route::resource('/schedules-patient', PatientsSchedulesController::class);
    Route::get('/schedules-patient', [PatientsSchedulesController::class, 'index'])->name('schedules.patient');
    Route::put('/schedules/confirm/{id}', [PatientsSchedulesController::class, 'confirm'])->name('schedules.confirm');


    Route::get('/patients', [UsersController::class, 'patient'])->name('users.patients');
    Route::get('/doctors', [UsersController::class, 'doctor'])->name('users.doctors');

    Route::get('/report', [ReportController::class, 'index'])->name('schedules.report');
});

Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/admin', [UsersController::class, 'admin'])->name('users.admin');
});

require __DIR__ . '/auth.php';
