<?php

declare(strict_types=1);

namespace Domain\User\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class UserCollection implements IteratorAggregate, Countable
{
    /** @var User[] */
    private array $items = [];

    public function add(User $item): void
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
