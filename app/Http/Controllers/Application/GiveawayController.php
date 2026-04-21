<?php

namespace App\Http\Controllers\Application;

use App\Enum\EntryRequirementLabel;
use App\Enum\GiveawayStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Giveaway\StoreGiveawayRequest;
use App\Http\Requests\Giveaway\UpdateGiveawayEntryRequest;
use App\Http\Resources\Giveaway\EditGiveawayResource;
use App\Http\Resources\Giveaway\EntryRequirementResource;
use App\Http\Resources\Giveaway\GiveawayListResource;
use App\Http\Resources\Giveaway\GiveawayShowResource;
use App\Http\Resources\Giveaway\ParticipantResource;
use App\Jobs\EndGiveaway;
use App\Jobs\StartGiveaway;
use App\Models\Giveaway;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class GiveawayController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): Response
	{
		$tabs = array_column(GiveawayStatus::cases(), 'value');
		$status = request('status');
		$currentUser = request()->user();

		if (!in_array($status, $tabs)) {
			$status = null;
		}

		return Inertia::render('giveaway/Index', [
			'my_giveaways' => Inertia::scroll(
				GiveawayListResource::collection(
					Giveaway::query()
						->select(['slug', 'title', 'ends_at', 'winners_count', 'status'])
						->when($status, fn ($q) => $q->where('status', $status))
						->where(fn ($q) => $q->where('user_id', $currentUser->id)->orWhereHas('hasUserEntered'))
						->latest()
						->paginate(20)
				)
			),
			'tabs' => $tabs,
			'status_tab' => $status
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	#[Middleware('verified')]
	public function create(): Response
	{
		Gate::authorize('create', Giveaway::class);

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
				'banner' => $data['banner'] ? Storage::put('giveaways', $data['banner']) : null,
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

		if ($giveaway->status === GiveawayStatus::SCHEDULED) {
			StartGiveaway::dispatch($giveaway)->delay($giveaway->starts_at);
		}

		EndGiveaway::dispatch($giveaway)->delay($giveaway->ends_at);

		return to_route('giveaways.show', ['giveaway' => $giveaway->slug]);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Giveaway $giveaway): Response
	{
		$g = GiveawayShowResource::make($giveaway);

		$gA = $g->toArray(request());

		$shareRequirements = $gA['has_started'] && !$gA['has_ended'];

		return Inertia::render('giveaway/Show', [
			'giveaway' => $g,
			'entry_requirements' => $shareRequirements ? EntryRequirementResource::collection(
				$giveaway
					->entryRequirements()
					->select(['slug', 'type', 'label', 'config', 'entries'])
					->withExists(['hasUserUsedThisRequirement'])
					->get()
			) : [],
			'participants' => $gA['has_started']
				? Inertia::scroll(
					fn () => ParticipantResource::collection(
						$giveaway
							->entries()
							->select('giveaway_entries.user_id')
							->selectRaw('MAX(users.name) as name, SUM(entry_requirements.entries) as total_entries')
							->join('entry_requirements', 'entry_requirements.id', '=', 'giveaway_entries.entry_requirement_id')
							->join('users', 'users.id', '=', 'giveaway_entries.user_id')
							->groupBy('giveaway_entries.user_id')
							->paginate(2)
					)
				)
				: [],
			'winners' => $gA['has_ended']
				? $giveaway
					->winners()
					->select(['name'])
					->get()
				: []
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Giveaway $giveaway): Response
	{
		Gate::authorize('update', $giveaway);

		$giveaway->load(['entryRequirements']);

		return Inertia::render('giveaway/Edit', [
			'giveaway' => EditGiveawayResource::make($giveaway),
			'defaultLabels' => EntryRequirementLabel::toArray()
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateGiveawayEntryRequest $request, Giveaway $giveaway): RedirectResponse
	{
		$data = collect($request->validated());

		if ($data['delete_banner']) {
			Storage::delete($giveaway->banner);
		}

		DB::transaction(function () use ($data, $giveaway) {
			$giveaway->update([
				...$data->only('title', 'description', 'winners_count'),
				...($data['delete_banner'] ? ['banner' => null] : [])
			]);

			$requirements = collect($data['entry_requirements']);

			$giveaway
				->entryRequirements()
				->whereNotIn(
					'slug',
					$requirements->pluck('slug')->filter()
				)
				->delete();

			$requirements->each(function ($req) use ($giveaway) {
				if (isset($req['slug'])) {
					$giveaway->entryRequirements()
						->where('slug', $req['slug'])
						->update($req);
				} else {
					$giveaway->entryRequirements()->create($req);
				}
			});
		});

		return to_route('giveaways.show', ['giveaway' => $giveaway]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Giveaway $giveaway): RedirectResponse
	{
		Gate::authorize('delete', $giveaway);

		DB::transaction(function () use ($giveaway) {
			$giveaway->delete();
		});

		if (request('redirect_back')) {
			return back(status: 303);
		}

		return to_route('home', status: 303);
	}

	public function startEarly(Giveaway $giveaway): RedirectResponse
	{
		Gate::authorize('update', $giveaway);

		$giveaway->update([
			'starts_at' => now(),
			'status' => GiveawayStatus::ACTIVE
		]);

		return back(status: 303);
	}

	public function endEarly(Giveaway $giveaway): RedirectResponse
	{
		Gate::authorize('update', $giveaway);

		$giveaway->update([
			'ends_at' => now(),
			'status' => GiveawayStatus::ENDED
		]);

		return back(status: 303);
	}

	public function pickWinners(Giveaway $giveaway): RedirectResponse
	{
		$randomParticipants = User::select(['id'])
			->whereIn('id', function ($q) use ($giveaway) {
				$q->select('user_id')
				->from('giveaway_entries')
				->where('giveaway_id', $giveaway->id);
			})
			->inRandomOrder()
			->limit($giveaway->winners_count)
			->pluck('id');

		$now = now();
		$giveaway->winners()->attach(
			$randomParticipants,
			[
				'slug' => Str::orderedUuid(),
				'created_at' => $now,
				'updated_at' => $now
			]
		);
		$giveaway->update(['status' => GiveawayStatus::COMPLETE]);

		return back();
	}
}
