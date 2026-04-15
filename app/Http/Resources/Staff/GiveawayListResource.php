<?php

namespace App\Http\Resources\Staff;

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
		$format = 'd/m/Y H:i';

		return [
			'slug' => $this->slug,
			'title' => $this->title,
			'status' => $this->status,
			'banner' => $this->banner !== null,
			'starts_at' => $this->starts_at->format($format),
			'ends_at' => $this->ends_at->format($format),
			'created_at' => $this->ends_at->format($format),
			'winners_count' => $this->winners_count,
			'participants_count' => $this->participantsCount->aggregate,
			'user' => ['email' => $this->user->email]
		];
	}
}
