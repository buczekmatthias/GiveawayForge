<?php

namespace App\Concerns;

use App\Enum\EntryRequirementType;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

trait GiveawayValidationRules
{
	protected function titleValidationRules(): array
	{
		return [
			'required',
			'string'
		];
	}

	protected function descriptionValidationRules(): array
	{
		return [
			'string',
			'nullable'
		];
	}

	protected function bannerValidationRules(): array
	{
		return [
			'nullable',
			'file',
			'mimetypes:image/png,image/jpeg'
		];
	}

	protected function startsAtValidationRules(): array
	{
		return [
			Rule::date()->afterOrEqual(now()),
			'nullable'
		];
	}

	protected function endsAtValidationRules(): array
	{
		return [
			Rule::date()
				->afterOrEqual(
					Carbon::parse($this->input('starts_at')) ?? now()->addMinutes(10)
				),
			'required'
		];
	}

	protected function winnersCountValidationRules(): array
	{
		return [
			'required',
			'integer',
			'min:1'
		];
	}

	protected function entryRequirementsValidationRules(): array
	{
		return [
			'array',
			'required',
			'min:1'
		];
	}

	protected function entryTypeValidationRules(): array
	{
		return [
			'required',
			'string',
			Rule::in(EntryRequirementType::cases())
		];
	}

	protected function entryLabelValidationRules(): array
	{
		return [
			'nullable',
			'string',
		];
	}

	protected function entryEntriesWeightValidationRules(): array
	{
		return [
			'nullable',
			'integer',
			'min:1'
		];
	}

	protected function entryConfigValidationRules(): array
	{
		return [
			'sometimes',
			'array'
		];
	}

	protected function entryConfigContentValidationRules(): array
	{
		return [
			'sometimes',
			'string'
		];
	}
}
