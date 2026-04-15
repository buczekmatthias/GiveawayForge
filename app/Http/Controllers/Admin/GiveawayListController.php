<?php

namespace App\Http\Controllers\Admin;

use App\Enum\GiveawayStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Staff\GiveawayListResource;
use App\Models\Giveaway;
use Inertia\Inertia;
use Inertia\Response;

class GiveawayListController extends Controller
{
	/**
	 * Handle the incoming request.
	 */
	public function __invoke(): Response
	{
		$sortableColumns = ['title', 'status', 'starts_at', 'ends_at', 'winners_count', 'created_at'];

		$search = strtolower(request('search'));
		$status = strtolower(request('status'));
		$hasBanner = request()->boolean('hasBanner');
		$sortColumn = strtolower(request('column', 'created_at'));
		$sortOrder = strtolower(request('order', 'DESC'));

		if (!in_array($sortColumn, $sortableColumns)) {
			$sortColumn = 'created_at';
		}

		if (!in_array($sortOrder, ['asc', 'desc'])) {
			$sortOrder = 'desc';
		}

		return Inertia::render('staff/Giveaway', [
			'paginatedGiveaways' => Inertia::scroll(
				GiveawayListResource::collection(
					Giveaway::query()
							->select(['slug', 'title', 'status', 'banner', 'starts_at', 'ends_at', 'winners_count', 'created_at', 'user_id'])
							->when($search, fn ($q) => $q->whereLike('title', "%$search%")->orWhereHas('user', fn ($qu) => $qu->whereLike('email', "%$search%")))
							->when($status, fn ($q) => $q->where('status', $status))
							->when($hasBanner, fn ($q) => $q->whereNotNull('banner'))
							->with(['user:id,email'])
							->orderBy($sortColumn, $sortOrder)
							->paginate(15)
				)
			),
			'giveaway_statuses' => array_column(GiveawayStatus::cases(), 'value'),
			'filter' => [
				'search' => $search,
				'status' => $status,
				'hasBanner' => $hasBanner,
				'activeColumn' => $sortColumn,
				'order' => $sortOrder
			],
			'sortable_columns' => $sortableColumns
		]);
	}
}
