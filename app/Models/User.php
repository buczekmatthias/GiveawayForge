<?php

namespace App\Models;

// use Laravel\Fortify\TwoFactorAuthenticatable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enum\GiveawayStatus;
use App\Enum\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['slug', 'name', 'email', 'password', 'role'])]
#[Hidden(['password', /* 'two_factor_secret', 'two_factor_recovery_codes', */ 'remember_token'])]
class User extends Authenticatable
{
	/** @use HasFactory<UserFactory> */
	use HasFactory, Notifiable /*, TwoFactorAuthenticatable */;

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'email_verified_at' => 'datetime',
			'password' => 'hashed',
			// 'two_factor_confirmed_at' => 'datetime',
			'role' => UserRole::class
		];
	}

	public function createdGiveaways(): HasMany
	{
		return $this->hasMany(Giveaway::class);
	}

	public function joinedGiveaways(): BelongsToMany
	{
		return $this->belongsToMany(Giveaway::class, 'giveaway_entries')
			->distinct();
	}

	public function wonGiveaways(): BelongsToMany
	{
		return $this->belongsToMany(Giveaway::class, 'giveaway_winners');
	}

	public function scopeEndedCreatedGiveaways(): HasMany
	{
		return $this->createdGiveaways()->where('status', GiveawayStatus::ENDED);
	}

	public function scopeActiveJoinedGiveaways(): BelongsToMany
	{
		return $this->joinedGiveaways()
			->where('status', GiveawayStatus::ACTIVE)
			->withCount(['participants'])
			->latest();
	}

	public function scopeCreatedGiveaways(): HasMany
	{
		return $this->createdGiveaways()
			->withCount(['participants'])
			->oldest('ends_at');
	}
}
