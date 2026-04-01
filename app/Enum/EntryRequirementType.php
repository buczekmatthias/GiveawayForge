<?php

namespace App\Enum;

enum EntryRequirementType: string
{
	case BUTTON_CLICK = 'button_click';
	case SECRET_CODE = 'secret_code';
	case QUESTION = 'question';
}
