<?php

namespace App\Enum;

enum EntryRequirementLabel: string
{
	case BUTTON_CLICK = 'Press the button to join!';
	case SECRET_CODE = 'Provide secret code!';
	case QUESTION = 'Answer the question below!';
}
