<?php

declare(strict_types=1);

namespace Domain\Service\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class ServiceCollection implements IteratorAggregate, Countable
{
    /** @var Service[] */
    private array $items = [];

    public function add(Service $item): void
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
