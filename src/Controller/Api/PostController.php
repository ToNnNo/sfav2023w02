<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/post", name="api_post")
 */
class PostController extends AbstractController
{
    private $postRepository;
    private $serializer;
    private $validator;

    public function __construct(
        PostRepository $postRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ){
        $this->postRepository = $postRepository;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route("", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        $posts = $this->postRepository->findAll();

        // return $this->json($posts, 200, [], ['groups' => 'title_only']);
        return $this->json($posts);
    }

    /**
     * @Route("/{id}", name="detail", methods={"GET"})
     */
    public function detail($id): Response
    {
        $post = $this->postRepository->find($id);

        if(!$post) {
            return $this->json(['message' => "La ressource n'existe pas"], Response::HTTP_NOT_FOUND);
        }

        return $this->json($post);
    }

    /**
     * @Route("", name="add", methods={"POST"})
     */
    public function add(Request $request): Response
    {
        $post = $this->serializer->deserialize($request->getContent(), Post::class, 'json');
        $errors = $this->validator->validate($post);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->postRepository->add($post, true);
        return $this->json($post, Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="edit", methods={"PUT", "PATCH"})
     */
    public function edit($id, Request $request): Response
    {
        $post = $this->postRepository->find($id);

        if(!$post) {
            return $this->json(['message' => "La ressource n'existe pas"], Response::HTTP_NOT_FOUND);
        }

        $this->serializer->deserialize(
            $request->getContent(),
            Post::class, 'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $post]
        );

        $this->postRepository->add($post, true);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete($id): Response
    {
        $post = $this->postRepository->find($id);

        if(!$post) {
            return $this->json(['message' => "La ressource n'existe pas"], Response::HTTP_NOT_FOUND);
        }

        $this->postRepository->remove($post, true);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
