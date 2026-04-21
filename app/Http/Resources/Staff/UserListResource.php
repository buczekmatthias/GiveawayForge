<?php

namespace App\Http\Resources\Staff;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
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
			'name' => $this->name,
			'email' => $this->email,
			'email_verified_at' => $this->email_verified_at,
			'role' => $this->role,
			'created_at' => $this->created_at->format('d/m/Y H:i'),
			'can' => [
				'promote' => $request->user()->can('promote', $this->resource),
				'demote' => $request->user()->can('demote', $this->resource),
				'delete' => $request->user()->can('delete', $this->resource)
			]
		];
	}
}
