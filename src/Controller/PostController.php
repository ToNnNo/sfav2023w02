<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post", name="post_")
 */
class PostController extends AbstractController
{
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $posts = $this->postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()) {
            $this->postRepository->add($post, true);

            $this->addFlash('success', "Enregistrement terminé avec succès");
            return $this->redirectToRoute('post_index');
        }

        return $this->renderForm('post/edit.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()) {
            $this->postRepository->add($post, true);

            $this->addFlash('success', "Modification terminé avec succès");
            return $this->redirectToRoute('post_edit', ['id' => $post->getId()]);
        }

        return $this->renderForm('post/edit.html.twig', [
            'form' => $form
        ]);
    }

}
