<?php

declare(strict_types=1);

enum ParticipantRole : string
{
    case owner = 'Owner';
    case responder = 'Responder';
}