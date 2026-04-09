<?php

namespace App\Http\Requests\Giveaway;

use App\Concerns\GiveawayValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreGiveawayEntryRequest extends FormRequest
{
	use GiveawayValidationRules;

	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return Auth::check();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'type' => $this->entryTypeValidationRules(),
			'answer' => $this->entryConfigContentValidationRules()
		];
	}
}
