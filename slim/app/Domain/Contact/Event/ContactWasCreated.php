<?php

/**
 * Contact created event
 *
 * @author Ernie Leseberg
 */
declare(strict_types=1);

namespace EL\Domain\Contact\Event;

use EL\Domain\Contact\ContactId;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

class ContactWasCreated implements SerializablePayload
{
    /**
     * @param string $contactId Readonly
     */
    public function __construct(public readonly ContactId $contactId)
    {
    }

    public static function fromPayload(array $payload): static
    {
        return new ContactWasCreated($payload['contactId']);
    }

    public function toPayload(): array
    {
        return ['property' => $this->contactId];
    }
}