<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('giveaway_entries', function (Blueprint $table) {
			$table->id();
			$table->uuid('slug')->unique();
			$table->foreignId('giveaway_id')->constrained()->cascadeOnDelete();
			$table->foreignId('user_id')->constrained()->cascadeOnDelete();
			$table->foreignId('entry_requirement_id')->constrained()->cascadeOnDelete();
			$table->string('answer')->nullable();
			$table->timestamps();

			$table->unique(['user_id', 'entry_requirement_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('giveaway_entries');
	}
};
