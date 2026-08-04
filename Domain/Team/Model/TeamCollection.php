<?php

declare(strict_types=1);

namespace Domain\Team\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class TeamCollection implements IteratorAggregate, Countable
{
    /** @var Team[] */
    private array $items = [];

    public function add(Team $item): void
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
