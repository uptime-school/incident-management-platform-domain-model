<?php

declare(strict_types=1);

namespace App\Domain\AuditRecord\Model;

enum AuditAction: string
{
    case Created = 'Created';
    case Updated = 'Updated';
    case StatusChanged = 'StatusChanged';
    case Assigned = 'Assigned';
    case Exported = 'Exported';
    case PermissionChanged = 'PermissionChanged';
}
