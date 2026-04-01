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
		Schema::create('giveaways', function (Blueprint $table) {
			$table->id();
			$table->uuid('slug')->unique();
			$table->string('title');
			$table->text('description')->nullable();
			$table->string('banner')->nullable();
			$table->timestamp('starts_at');
			$table->timestamp('ends_at');
			$table->integer('winners_count')->default(1);
			$table->string('status');
			$table->foreignId('user_id')->constrained()->cascadeOnDelete();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('giveaways');
	}
};
