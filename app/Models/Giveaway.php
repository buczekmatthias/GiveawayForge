<?php

namespace App\Models;

use App\Enum\GiveawayStatus;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

#[Fillable(['slug', 'title', 'description', 'banner', 'starts_at', 'ends_at', 'winners_count', 'status'])]
class Giveaway extends Model
{
	/** @use HasFactory<\Database\Factories\GiveawayFactory> */
	use HasFactory, HasSlug;

	protected $with = ['participantsCount'];

	protected function casts(): array
	{
		return [
			'status' => GiveawayStatus::class,
			'starts_at' => 'datetime',
			'ends_at' => 'datetime',
		];
	}

	protected static function booted(): void
	{
		static::deleted(function (Giveaway $giveaway) {
			if ($giveaway->banner) {
				DB::afterCommit(fn () => Storage::delete($giveaway->banner));
			}
		});
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

	public function participantsCount(): HasOne
	{
		return $this
			->hasOne(Entry::class)
			->selectRaw('giveaway_id, COUNT(DISTINCT user_id) as aggregate')
			->groupBy('giveaway_id');
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
