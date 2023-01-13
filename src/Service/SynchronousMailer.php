<?php

namespace App\Service;

use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\RawMessage;

class SynchronousMailer implements MailerInterface
{
    private $mailer = null;

    public function __construct(TransportInterface $transport)
    {
        if(null === $this->mailer) {
            $this->mailer = new Mailer($transport);
        }
    }

    public function send(RawMessage $message, Envelope $envelope = null): void
    {
        $this->mailer->send($message, $envelope);
    }
}
