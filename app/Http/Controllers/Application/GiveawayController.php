<?php

namespace App\Http\Controllers\Application;

use App\Enum\EntryRequirementLabel;
use App\Enum\GiveawayStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Giveaway\StoreGiveawayRequest;
use App\Models\Giveaway;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class GiveawayController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 */
	#[Middleware('verified')]
	public function create(): Response
	{
		return Inertia::render('giveaway/Create', [
			'defaultLabels' => EntryRequirementLabel::toArray()
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	#[Middleware('verified')]
	public function store(StoreGiveawayRequest $request): RedirectResponse
	{
		$data = $request->validated();

		$giveaway = DB::transaction(function () use ($data, $request) {
			$giveaway = Giveaway::make([
				'title' => $data['title'],
				'description' => $data['description'],
				'banner' => Storage::put('giveaways', $data['banner']),
				'starts_at' => Carbon::parse($data['starts_at']),
				'ends_at' => Carbon::parse($data['ends_at']),
				'winners_count' => $data['winners_count'],
				'status' => match (true) {
					Carbon::parse($data['starts_at'])->isFuture() => GiveawayStatus::SCHEDULED,
					default => GiveawayStatus::ACTIVE
				}
			]);

			$giveaway->user_id = $request->user()->id;

			$giveaway->save();

			$giveaway->entryRequirements()->createMany(
				collect($data['entry_requirements'])
					->map(function ($req) {
						$r = [
							'type' => $req['type'],
							'label' => $req['label'] ?: EntryRequirementLabel::{strtoupper($req['type'])},
							'entries' => $req['entries'] ?? 1
						];

						if (isset($req['config'])) {
							$r['config'] = $req['config'];
						}

						return $r;
					})
			);

			return $giveaway;
		});

		return to_route('giveaways.show', ['giveaway' => $giveaway->slug]);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Giveaway $giveaway): Response
	{
		return Inertia::render('giveaway/Show', [
			'giveaway' => $giveaway
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Giveaway $giveaway)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Giveaway $giveaway)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Giveaway $giveaway)
	{
		//
	}
}
