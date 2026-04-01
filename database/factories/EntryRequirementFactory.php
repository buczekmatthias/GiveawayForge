<?php

namespace Database\Factories;

use App\Enum\EntryRequirementLabel;
use App\Enum\EntryRequirementType;
use App\Models\EntryRequirement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<EntryRequirement>
 */
class EntryRequirementFactory extends Factory
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
			'type' => EntryRequirementType::BUTTON_CLICK,
			'label' => EntryRequirementLabel::BUTTON_CLICK->value,
		];
	}
}
