<?php

namespace App\Models;

use App\Enum\EntryRequirementType;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

#[Fillable(['slug', 'type', 'label', 'config', 'entries'])]
class EntryRequirement extends Model
{
	/** @use HasFactory<\Database\Factories\EntryRequirementFactory> */
	use HasFactory, HasSlug;

	protected function casts(): array
	{
		return [
			'config' => 'array',
			'type' => EntryRequirementType::class
		];
	}

	public function giveaway(): BelongsTo
	{
		return $this->belongsTo(Giveaway::class);
	}

	public function entries(): HasMany
	{
		return $this->hasMany(Entry::class);
	}

	public function hasUserUsedThisRequirement(): HasMany
	{
		return $this->entries()->where('user_id', Auth::user()->id);
	}
}
