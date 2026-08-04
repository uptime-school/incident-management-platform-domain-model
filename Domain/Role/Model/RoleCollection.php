<?php

declare(strict_types=1);

namespace Domain\Role\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class RoleCollection implements IteratorAggregate, Countable
{
    /** @var Role[] */
    private array $items = [];

    public function add(Role $item): void
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
