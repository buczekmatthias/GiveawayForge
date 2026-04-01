<?php

namespace App\Models;

use App\Enum\GiveawayStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['slug', 'title', 'description', 'banner', 'starts_at', 'ends_at', 'winners_count', 'status'])]
class Giveaway extends Model
{
	/** @use HasFactory<\Database\Factories\GiveawayFactory> */
	use HasFactory;

	protected function casts(): array
	{
		return [
			'status' => GiveawayStatus::class,
			'starts_at' => 'datetime',
			'ends_at' => 'datetime',
		];
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function participants(): BelongsToMany
	{
		return $this->belongsToMany(User::class, 'giveaway_entries')
		->distinct();
	}

	public function hasUserEntered(User $user): bool
	{
		return $this->participants()->wherePivot('user_id', $user->id)->exists();
	}

	public function entryRequirements(): HasMany
	{
		return $this->hasMany(EntryRequirement::class);
	}

	public function entries(): HasMany
	{
		return $this->hasMany(Entry::class);
	}

	public function winners(): BelongsToMany
	{
		return $this->belongsToMany(User::class, 'giveaway_winners');
	}
}
