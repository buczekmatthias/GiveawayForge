<?php

namespace App\Enum;

enum EntryRequirementLabel: string
{
	case BUTTON_CLICK = 'Press the button to join!';
	case SECRET_CODE = 'Provide secret code!';
	case QUESTION = 'Answer the question below!';

	public static function toArray()
	{
		$keys = array_map(fn ($e) => strtolower($e), array_column(self::cases(), 'name'));
		$values = array_column(self::cases(), 'value');

		return array_combine($keys, $values);
	}
}
