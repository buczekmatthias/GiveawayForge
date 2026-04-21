<?php

namespace App\Policies;

use App\Enum\UserRole;
use App\Models\User;

class UserPolicy
{
	public function promote(User $currentUser, User $user): bool
	{
		return $currentUser->isAdmin() && $user->role === UserRole::USER;
	}

	public function demote(User $currentUser, User $user): bool
	{
		return $currentUser->isAdmin() && $user->role === UserRole::MOD;
	}

	public function delete(User $currentUser, User $user): bool
	{
		return match ($currentUser->role) {
			UserRole::ADMIN => $user->role !== UserRole::ADMIN,
			UserRole::MOD => $user->role === UserRole::USER,
			default => false
		};
	}
}
