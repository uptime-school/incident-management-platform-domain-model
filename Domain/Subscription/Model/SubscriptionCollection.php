<?php

declare(strict_types=1);

namespace Domain\Subscription\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class SubscriptionCollection implements IteratorAggregate, Countable
{
    /** @var Subscription[] */
    private array $items = [];

    public function add(Subscription $item): void
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
