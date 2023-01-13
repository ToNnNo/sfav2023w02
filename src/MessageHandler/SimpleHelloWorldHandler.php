<?php

namespace App\MessageHandler;

use App\Message\SimpleHelloWorld;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SimpleHelloWorldHandler implements MessageHandlerInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(SimpleHelloWorld $message)
    {
        $text = "Votre message est: " . $message->getData();

        dump($text);
        $this->logger->info($text);
    }
}
