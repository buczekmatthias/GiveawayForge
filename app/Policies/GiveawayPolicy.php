<?php

namespace App\Policies;

use App\Models\Giveaway;
use App\Models\User;

class GiveawayPolicy
{
	/**
	 * Determine whether the user can create models.
	 */
	public function create(User $user): bool
	{
		return $user->hasVerifiedEmail();
	}

	/**
	 * Determine whether the user can update the model.
	 */
	public function update(User $user, Giveaway $giveaway): bool
	{
		return $user->id === $giveaway->user_id;
	}

	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, Giveaway $giveaway): bool
	{
		return $user->isStaff() || $user->id === $giveaway->user_id;
	}
}
