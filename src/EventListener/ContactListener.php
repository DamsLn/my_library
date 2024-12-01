<?php

namespace App\EventListener;

use App\Event\ContactRequestEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class ContactListener
{
    #[AsEventListener(event: ContactRequestEvent::class)]
    public function onContactRequestEvent(ContactRequestEvent $event): void
    {
        dd('Ce listener de va traiter que les évènements de type ContactRequestEvent qui sont dispatchés');
    }
}
