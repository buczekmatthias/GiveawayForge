<?php

namespace Database\Seeders;

use App\Models\EntryRequirement;
use App\Models\Giveaway;
use Illuminate\Database\Seeder;

class EntryRequirementSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$giveaways = Giveaway::all();

		$giveaways->each(function ($giveaway) {
			EntryRequirement::factory()->for($giveaway)->create();
		});
	}
}
