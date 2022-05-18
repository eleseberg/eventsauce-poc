<?php

/**
 * Contact aggregate root ID
 *
 * @author Ernie Leseberg
 */
declare(strict_types=1);

namespace EL\Domain\Contact;

use EventSauce\EventSourcing\AggregateRootId;

class ContactId implements AggregateRootId
{
    private $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public function toString(): string
    {
        return $this->id;
    }

    public static function fromString(string $aggregateRootId): static
    {
        return new static($aggregateRootId);
    }
}