<?php

namespace App\Http\Controllers\Application;

use App\Enum\EntryRequirementType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Giveaway\StoreGiveawayEntryRequest;
use App\Models\Entry;
use App\Models\EntryRequirement;
use App\Models\Giveaway;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class StoreGiveawayEntryController extends Controller
{
	/**
	 * Handle the incoming request.
	 */
	public function __invoke(StoreGiveawayEntryRequest $request, Giveaway $giveaway, EntryRequirement $entry_requirement): RedirectResponse
	{
		if ($entry_requirement->hasUserUsedThisRequirement()->exists()) {
			return back();
		}

		$data = $request->validated();

		$answer = null;

		if ($data['type'] !== EntryRequirementType::BUTTON_CLICK->value) {
			$answer = $data['answer'];

			$key = match ($data['type']) {
				EntryRequirementType::QUESTION->value => 'answer',
				EntryRequirementType::SECRET_CODE->value => 'secret_code',
			};

			$correctAnswer = $entry_requirement->config[$key];

			if ($answer !== $correctAnswer) {
				return Inertia::flash('wrong_answer', 'Provided answer was wrong')->back();
			}
		}

		$entry = Entry::make(
			[
				'answer' => $answer
			]
		);

		$entry->giveaway_id = $giveaway->id;
		$entry->user_id = $request->user()->id;
		$entry->entry_requirement_id = $entry_requirement->id;

		$entry->save();

		return back();
	}
}
