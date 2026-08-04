<?php

declare(strict_types=1);

namespace Domain\Permission\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class PermissionCollection implements IteratorAggregate, Countable
{
    /** @var Permission[] */
    private array $items = [];

    public function add(Permission $item): void
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
