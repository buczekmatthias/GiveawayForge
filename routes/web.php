<?php

use App\Http\Controllers\Application\GiveawayController;
use App\Http\Controllers\Application\HomepageController;
use App\Http\Controllers\Application\StoreGiveawayEntryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
	Route::get('/', HomepageController::class)->name('home');

	Route::resource('giveaways', GiveawayController::class);
	Route::patch('/giveaways/{giveaway}/start-early', [GiveawayController::class, 'startEarly'])->name('giveaways.early.start');
	Route::patch('/giveaways/{giveaway}/end-early', [GiveawayController::class, 'endEarly'])->name('giveaways.early.end');

	Route::post('/giveaways/{giveaway}/entries/{entry_requirement}', StoreGiveawayEntryController::class)->name('giveaways.entry.store');
});

require __DIR__.'/settings.php';
