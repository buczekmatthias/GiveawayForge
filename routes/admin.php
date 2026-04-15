<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GiveawayListController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'is_staff'])->prefix('staff')->name('staff.')->group(function () {
	Route::get('/dashboard', DashboardController::class)->name('dashboard');

	Route::get('/giveaways', GiveawayListController::class)->name('giveaways.index');

	Route::resource('users', UserController::class)->only(['index', 'update', 'destroy']);
});
