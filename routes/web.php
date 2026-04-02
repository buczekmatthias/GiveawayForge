<?php

use App\Http\Controllers\Application\HomepageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
	Route::get('/', HomepageController::class)->name('home');
});

require __DIR__.'/settings.php';
