<?php

namespace App\Controller;

use App\Service\Welcome\WelcomeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceExampleController extends AbstractController
{
    /**
     * @Route("/service", name="service_index")
     */
    public function index(WelcomeInterface $welcome): Response
    {

        return $this->render('service_example/index.html.twig', [
            'message' => $welcome->hello()
        ]);
    }

    /**
     * @Route("/service/html", name="service_html")
     */
    public function html(WelcomeInterface $welcomeHtml, string $commercial): Response
    {

        return $this->render('service_example/index.html.twig', [
            'message' => $welcomeHtml->hello()
        ]);
    }
}
