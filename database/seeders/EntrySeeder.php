<?php

namespace Database\Seeders;

use App\Models\Entry;
use App\Models\Giveaway;
use App\Models\User;
use Illuminate\Database\Seeder;

class EntrySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$limit = 50;

		$giveaways = Giveaway::limit($limit)->get();
		$users = User::limit($limit)->get();

		for ($i = 0; $i < $limit; $i++) {
			$g = $giveaways->random();
			$user = $users->random();

			$users->reject(fn ($u) => $u->id === $user->id);

			Entry::factory()
				->for($g)
				->for($user)
				->for($g->entryRequirements()->first())
				->create();
		}
	}
}
