<?php

namespace App\Http\Resources\Giveaway;

use Coduo\PHPHumanizer\NumberHumanizer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GiveawayListResource extends JsonResource
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
			'ends_at' => $this->ends_at->diffForHumans(),
			'winners_count' => $this->winners_count,
			'status' => $this->status,
			'participants_count' => $this->whenCounted(
				'participants',
				NumberHumanizer::metricSuffix($this->participants_count)
			)
		];
	}
}
