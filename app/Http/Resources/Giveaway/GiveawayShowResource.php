<?php

namespace App\Http\Resources\Giveaway;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GiveawayShowResource extends GiveawayListResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			...parent::toArray($request),
			'banner' => $this->banner ? Storage::url($this->banner) : null,
			'description' => $this->description,
			'starts_at' => ($this->starts_at ?? $this->created_at)->format('d/m/Y H:i'),
			'has_started' => ($this->starts_at ?? $this->created_at)->isPast(),
			'has_ended' => $this->ends_at->isPast(),
			'can' => [
				'update' => $request->user()->can('update', $this->resource),
				'delete' => $request->user()->can('delete', $this->resource),
			]
		];
	}
}
