<?php

namespace App\Service;

use App\Event\PreUselessNothingEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class UselessHandler
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function nothing($message): string
    {
        if(!is_string($message)) {
            throw new \InvalidArgumentException('Le message doit être une chaine de caractère');
        }

        $event = new PreUselessNothingEvent($message);
        $this->dispatcher->dispatch($event, PreUselessNothingEvent::PRE_UPDATE_VALUE);

        return $event->getValue();
    }

}
