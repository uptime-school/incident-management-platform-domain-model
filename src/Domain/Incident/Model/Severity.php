<?php

declare(strict_types=1);

namespace App\Domain\Incident\Model;

enum Severity: int
{
    case Sev1 = 1;
    case Sev2 = 2;
    case Sev3 = 3;
    case Sev4 = 4;
    case Sev5 = 5;
}