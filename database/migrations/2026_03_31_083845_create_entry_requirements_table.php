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
		Schema::create('entry_requirements', function (Blueprint $table) {
			$table->id();
			$table->uuid('slug')->unique();
			$table->foreignId('giveaway_id')->constrained()->cascadeOnDelete();
			$table->string('type');
			$table->string('label')->nullable();
			$table->json('config')->nullable();
			$table->integer('entries')->default(1);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('entry_requirements');
	}
};
