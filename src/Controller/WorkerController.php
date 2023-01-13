<?php

namespace App\Controller;

use App\Message\SimpleHelloWorld;
use App\Service\SynchronousMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/worker", name="worker_")
 */
class WorkerController extends AbstractController
{
    private $bus;
    private $mailer;
    private $synchronousMailer;

    public function __construct(MessageBusInterface $bus, MailerInterface $mailer, SynchronousMailer $synchronousMailer)
    {
        $this->bus = $bus;
        $this->mailer = $mailer;
        $this->synchronousMailer = $synchronousMailer;
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $this->bus->dispatch(
            new SimpleHelloWorld('Ceci est un test du Composant Messenger'),
            [DelayStamp::delayFor(new \DateInterval("PT2M"))]
        );

        return $this->render('worker/index.html.twig');
    }

    /**
     * @Route("/mail", name="mail")
     */
    public function asyncmail(): Response
    {
        $email = (new Email())
            ->from(new Address('noreply@dawan.fr', 'noreply'))
            ->to(new Address('smenut@dawan.fr', "Stéphane"))
            ->subject('Async Mailer')
            ->text('Hello async mail');

        $this->mailer->send($email);

        return $this->render('worker/index.html.twig');
    }

    /**
     * @Route("/mail/sync", name="mail_sync")
     */
    public function syncmail(string $noreply): Response
    {
        $email = (new Email())
            ->from(new Address($noreply, 'noreply'))
            ->to(new Address('smenut@dawan.fr', "Stéphane"))
            ->subject('Sync Mailer')
            ->text('Hello sync mail');

        $this->synchronousMailer->send($email);

        return $this->render('worker/index.html.twig');
    }

    /**
     * @Route("/mail/mixed", name="mail_mixed")
     */
    public function mixed(): Response
    {
        $email = (new Email())
            ->from(new Address('noreply@dawan.fr', 'noreply'))
            ->to(new Address('smenut@dawan.fr', "Stéphane"))
            ->subject('Mixed Mailer')
            ->text('Hello mixed mail');

        $this->mailer->send($email);
        $this->synchronousMailer->send($email);

        return $this->render('worker/index.html.twig');
    }

    /**
     * @Route("/mail/attachment", name="mail_attachment")
     */
    public function attachment(): Response
    {

        $email = (new Email())
            ->from(new Address('noreply@dawan.fr', 'noreply'))
            ->to(new Address('smenut@dawan.fr', "Stéphane"))
            ->subject('Async Mailer + Attachment')
            ->text('Hello async mail + Attachment')
            ->attachFromPath($this->getParameter('kernel.project_dir') . "/public/build/images/event-http-kernel.png")
        ;

        $this->mailer->send($email);

        return $this->render('worker/index.html.twig');
    }
}
