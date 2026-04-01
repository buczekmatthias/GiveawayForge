<?php

namespace Database\Seeders;

use App\Models\Giveaway;
use Illuminate\Database\Seeder;

class GiveawaySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		Giveaway::factory(1000)->create();
	}
}
