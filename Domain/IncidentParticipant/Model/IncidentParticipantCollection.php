<?php

declare(strict_types=1);

namespace Domain\IncidentParticipant\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class IncidentParticipantCollection implements IteratorAggregate, Countable
{
    /** @var IncidentParticipant[] */
    private array $items = [];

    public function add(IncidentParticipant $item): void
    {
        $this->items[] = $item;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }
}
