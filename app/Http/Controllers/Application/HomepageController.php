<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Http\Resources\Giveaway\GiveawayListResource;
use Inertia\Inertia;
use Inertia\Response;

class HomepageController extends Controller
{
	/**
	 * Handle the incoming request.
	 */
	public function __invoke(): Response
	{
		return Inertia::render('Homepage', [
			'endedCreatedGiveawaysCount' => request()
				->user()
				->endedCreatedGiveaways()
				->count(),
			'createdGiveaways' => GiveawayListResource::collection(
				request()
					->user()
					->createdGiveaways()
					->limit(5)
					->get()
			),
			'activeJoinedGiveaways' => GiveawayListResource::collection(
				request()
					->user()
					->activeJoinedGiveaways()
					->limit(5)
					->get()
			)
		]);
	}
}
