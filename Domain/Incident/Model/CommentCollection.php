<?php

declare(strict_types=1);

namespace Domain\Incident\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class CommentCollection implements IteratorAggregate, Countable
{
    /** @var Comment[] */
    private array $items = [];

    public function add(Comment $item): void
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
