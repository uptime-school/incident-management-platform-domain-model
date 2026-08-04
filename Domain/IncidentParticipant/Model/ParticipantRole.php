<?php

declare(strict_types=1);

namespace Domain\IncidentParticipant\Model;

enum ParticipantRole : string
{
    case owner = 'Owner';
    case responder = 'Responder';
}