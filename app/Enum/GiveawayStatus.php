<?php

namespace App\Enum;

enum GiveawayStatus: string
{
	case SCHEDULED = 'scheduled';
	case ACTIVE = 'active';
	case ENDED = 'ended';
	case COMPLETE = 'complete';
}
