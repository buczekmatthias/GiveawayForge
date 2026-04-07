<?php

namespace App\Http\Requests\Giveaway;

use App\Concerns\GiveawayValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreGiveawayRequest extends FormRequest
{
	use GiveawayValidationRules;

	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return Auth::check() && $this->user()->hasVerifiedEmail();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'title' => $this->titleValidationRules(),
			'description' => $this->descriptionValidationRules(),
			'banner' => $this->bannerValidationRules(),
			'starts_at' => $this->startsAtValidationRules(),
			'ends_at' => $this->endsAtValidationRules(),
			'winners_count' => $this->winnersCountValidationRules(),
			'entry_requirements' => $this->entryRequirementsValidationRules(),
			'entry_requirements.*.type' => $this->entryTypeValidationRules(),
			'entry_requirements.*.label' => $this->entryLabelValidationRules(),
			'entry_requirements.*.entries' => $this->entryEntriesWeightValidationRules(),
			'entry_requirements.*.config' => $this->entryConfigValidationRules(),
			'entry_requirements.*.config.*' => $this->entryConfigContentValidationRules(),
		];
	}
}
