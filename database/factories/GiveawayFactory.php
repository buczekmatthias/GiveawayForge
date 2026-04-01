<?php

namespace Database\Factories;

use App\Enum\GiveawayStatus;
use App\Models\Giveaway;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends Factory<Giveaway>
 */
class GiveawayFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'slug' => Str::orderedUuid(),
			'title' => fake()->words(fake()->numberBetween(1, 8), true),
			'description' => fake()->boolean() ? fake()->sentences(fake()->numberBetween(1, 5), true) : null,
			'starts_at' => $starts_at = Carbon::instance(fake()->dateTimeBetween('-3 months', '+3 months')),
			'ends_at' => $ends_at = Carbon::instance(fake()->dateTimeBetween($starts_at->copy()->addDay(), $starts_at->copy()->addMonths(6))),
			'winners_count' => fake()->numberBetween(1, 6),
			'status' => match (true) {
				$starts_at->isFuture() => GiveawayStatus::SCHEDULED,
				$starts_at->isPast() && $ends_at->isFuture() => GiveawayStatus::ACTIVE,
				$starts_at->isPast() && $ends_at->isPast() => GiveawayStatus::ENDED
			},
			'user_id' => User::select(['id'])->inRandomOrder()->limit(1)->first()->id
		];
	}
}
