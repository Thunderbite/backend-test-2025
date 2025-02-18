<?php

namespace App\Enums;

enum GameStatus: string
{
	case ACTIVE = 'active';
	case WON = 'won';
	case LOST = 'lost';
}
