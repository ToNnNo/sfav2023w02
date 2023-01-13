<?php

namespace App\Controller;

use App\Service\UselessHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event", name="event_")
 */
class EventSubcriberController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {

        return $this->render('event_subcriber/index.html.twig');
    }

    /**
     * @Route("/kernel", name="kernel")
     */
    public function kernelEvent(): Response
    {
        return $this->render('event_subcriber/kernel.html.twig');
    }

    /**
     * @Route("/custom", name="custom")
     */
    public function customEvent(UselessHandler $uselessHandler): Response
    {
        $message = $uselessHandler->nothing('Hello World');

        return $this->render('event_subcriber/custom.html.twig', [
            'message' => $message
        ]);
    }
}
