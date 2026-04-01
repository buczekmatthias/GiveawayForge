<?php

namespace Database\Seeders;

use App\Enum\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		User::factory()->create([
			'name' => 'Admin User',
			'email' => 'test@example.com',
			'role' => UserRole::ADMIN
		]);

		User::factory(50)->create(['role' => UserRole::MOD]);

		User::factory(3000)->create();
	}
}
