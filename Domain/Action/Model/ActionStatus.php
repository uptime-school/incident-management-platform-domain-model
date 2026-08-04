<?php

declare(strict_types=1);

namespace Domain\Action\Model;

enum ActionStatus: string
{
    case ToDo = 'To Do';
    case InProgress = 'In Progress';
    case Done = 'Done';
}
