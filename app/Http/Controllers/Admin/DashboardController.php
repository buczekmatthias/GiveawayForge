<?php

namespace App\Http\Controllers\Admin;

use App\Enum\GiveawayStatus;
use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\Giveaway;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
	/**
	 * Handle the incoming request.
	 */
	public function __invoke(): Response
	{
		$startOfMonth = now()->startOfMonth();

		return Inertia::render('staff/Dashboard', [
			'giveaways_count' => [
				'total' => Giveaway::count(),
				'active' => Giveaway::where('status', GiveawayStatus::ACTIVE)->count(),
				'complete' => Giveaway::where('status', GiveawayStatus::COMPLETE)->count(),
				'created_this_month' => Giveaway::where('created_at', '>=', $startOfMonth)->count()
			],
			'users_count' => [
				'total' => User::count(),
				'created_this_month' => User::where('created_at', '>=', $startOfMonth)->count()
			],
			'participants_count' => [
				'total' => Entry::distinct()->count(),
				'joined_this_month' => Entry::distinct()->where('created_at', '>=', $startOfMonth)->count()
			]
		]);
	}
}
