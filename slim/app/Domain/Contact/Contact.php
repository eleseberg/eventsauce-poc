<?php

/**
 * Contact aggregate root
 *
 * @author Ernie Leseberg
 */
declare(strict_types=1);

namespace EL\Domain\Contact;

use EL\Domain\Contact\Event\ContactWasCreated;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;

class Contact implements AggregateRoot
{
    use AggregateRootBehaviour;

    private ContactId $contactId;

    public static function initiate(ContactId $id): Contact
    {
        $contact = new static($id);
        $contact->recordThat(new ContactWasCreated($id));

        return $contact;
    }

    public function applyContactWasCreated(ContactWasCreated $contactCreated) {
        $this->contactId = $contactCreated->contactId;
    }

}
