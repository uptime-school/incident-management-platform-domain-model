<?php

declare(strict_types=1);

namespace Domain\AuditRecord\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class AuditRecordCollection implements IteratorAggregate, Countable
{
    /** @var AuditRecord[] */
    private array $items = [];

    public function add(AuditRecord $item): void
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
