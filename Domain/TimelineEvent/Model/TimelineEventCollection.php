<?php

declare(strict_types=1);

namespace Domain\TimelineEvent\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class TimelineEventCollection implements IteratorAggregate, Countable
{
    /** @var TimelineEvent[] */
    private array $items = [];

    public function add(TimelineEvent $item): void
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
