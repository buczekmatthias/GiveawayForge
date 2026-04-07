<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['slug', 'answer'])]
class Entry extends Model
{
	/** @use HasFactory<\Database\Factories\EntryFactory> */
	use HasFactory, HasSlug;

	protected $table = 'giveaway_entries';

	public function giveaway(): BelongsTo
	{
		return $this->belongsTo(Giveaway::class);
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function entryRequirement(): BelongsTo
	{
		return $this->belongsTo(EntryRequirement::class);
	}
}
