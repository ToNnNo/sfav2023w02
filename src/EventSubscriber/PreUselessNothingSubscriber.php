<?php

namespace App\EventSubscriber;

use App\Event\PreUselessNothingEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PreUselessNothingSubscriber implements EventSubscriberInterface
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function updateValue(PreUselessNothingEvent $event): void
    {
        $value = $event->getValue();
        $value .= " !";
        $event->setValue($value);
    }

    public function translateValue(PreUselessNothingEvent $event): void
    {
        $value = $event->getValue();
        $event->setValue( $this->translator->trans($value) );
    }

    public function valueToHTML(PreUselessNothingEvent $event): void
    {
        $value = $event->getValue();
        $event->setValue( "<strong>".$value."</strong>" );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PreUselessNothingEvent::PRE_UPDATE_VALUE => [
                ['translateValue', 1],
                ['updateValue', 0],
                ['valueToHTML', 0]
            ]
        ];
    }
}
