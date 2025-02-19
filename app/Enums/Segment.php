<?php

declare(strict_types=1);

namespace App\Enums;

enum Segment: string
{
    case LOW = 'low';
    case MED = 'med';
    case HIGH = 'high';
}
