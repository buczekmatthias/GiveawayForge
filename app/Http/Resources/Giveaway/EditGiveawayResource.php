<?php

namespace App\Http\Resources\Giveaway;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EditGiveawayResource extends JsonResource
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
			'title' => $this->title,
			'description' => $this->description,
			'banner' => $this->banner ? Storage::url($this->banner) : null,
			'winners_count' => $this->winners_count,
			'entry_requirements' => $this->entryRequirements->map(fn ($e) => [
				'slug' => $e->slug,
				'type' => $e->type,
				'label' => $e->label,
				'config' => $e->config,
				'entries' => $e->entries,
			]),
		];
	}
}
