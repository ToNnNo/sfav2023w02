<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Transient\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact_index")
     */
    public function index(Request $request): Response
    {
        $contact = (new Contact())
            ->setFullname("John Doe");

        $form = $this->createForm(ContactType::class, $contact = null);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            dump($data);

            return $this->redirectToRoute('contact_index');
        }

        return $this->renderForm('contact/index.html.twig', [
            'form' => $form
        ]);
    }
}
