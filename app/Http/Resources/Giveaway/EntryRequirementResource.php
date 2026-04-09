<?php

namespace App\Http\Resources\Giveaway;

use App\Enum\EntryRequirementType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntryRequirementResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'slug' => $this->slug,
			'type' => $this->type,
			'label' => $this->label,
			'config' => $this->type === EntryRequirementType::QUESTION ? collect($this->config)->except(['answer']) : null,
			'entries' => $this->entries,
			'has_user_used_this_requirement' => $this->has_user_used_this_requirement_exists
		];
	}
}
