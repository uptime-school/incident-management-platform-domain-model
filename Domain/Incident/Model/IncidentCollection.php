<?php

declare(strict_types=1);

namespace Domain\Incident\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class IncidentCollection implements IteratorAggregate, Countable
{
    /** @var Incident[] */
    private array $items = [];

    public function add(Incident $item): void
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
