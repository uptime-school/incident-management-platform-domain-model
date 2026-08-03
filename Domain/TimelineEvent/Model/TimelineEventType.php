<?php

declare(strict_types=1);

enum TimelineEventType: string
{
    case StatusChanged = 'StatusChanged';
    case SeverityChanged = 'SeverityChanged';
    case OwnerAssigned = 'OwnerAssigned';
    case Acknowledged = 'Acknowledged';
    case CommentPosted = 'CommentPosted';
    case SystemEvent = 'SystemEvent';
}