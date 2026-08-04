<?php

declare(strict_types=1);

namespace Domain\Action\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class ActionCollection implements IteratorAggregate, Countable
{
    /** @var Action[] */
    private array $items = [];

    public function add(Action $item): void
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
