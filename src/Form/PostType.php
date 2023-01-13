<?php

namespace App\Form;

use App\Entity\Post;
use App\Form\Custom\ExtraTextType;
use App\Form\Transformer\TagsViewTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    private $tagsViewTransformer;

    public function __construct(TagsViewTransformer $tagsViewTransformer)
    {
        $this->tagsViewTransformer = $tagsViewTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', ExtraTextType::class, [
                'label' => "Titre: ",
                'priority' => 99,
                'attr' => [
                    'placeholder' => "Titre de l'article"
                ],
                'start_input' => '<i class="bi bi-link-45deg"></i>',
                'start_input_html' => true,
                'end_input' => '!'
            ])
            ->add('body', TextareaType::class, [
                'label' => "Contenu: ",
                'attr' => [
                    'placeholder' => "Contenu de l'article"
                ]
            ])
            ->add('tags', TextType::class, [
                'label' => "Tags: ",
                'attr' => [
                    'placeholder' => "Liste des tags associés à l'article"
                ],
                'help' => 'Séparer les tags par des virgules'
            ])
        ;

        // DataTransformer
        $builder->get('tags')->addViewTransformer($this->tagsViewTransformer);

        // FormEvent
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onConditionalSummaryField'])
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'preSetData'])
            ->addEventListener(FormEvents::POST_SET_DATA, [$this, 'postSetData'])
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit'])
            ->addEventListener(FormEvents::SUBMIT, [$this, 'submit'])
            ->addEventListener(FormEvents::POST_SUBMIT, [$this, 'postSubmit'])
        ;
    }

    public function preSetData(FormEvent $event): void
    {
        /** @var Post $post */
        $post = $event->getData();

        dump("PreSetData: ");
        dump($post->getTags());
    }

    public function postSetData(FormEvent $event): void
    {
        /** @var Post $post */
        $post = $event->getData();

        dump("PostSetData: ");
        dump($post->getTags());
    }

    public function preSubmit(FormEvent $event): void
    {
        $data = $event->getData();

        dump("preSubmit: ");
        dump($data);
    }

    public function submit(FormEvent $event): void
    {
        $data = $event->getData();

        dump("submit: ");
        dump($data);
    }

    public function postSubmit(FormEvent $event): void
    {
        $data = $event->getData();

        dump("postSubmit: ");
        dump($data);
    }

    public function onConditionalSummaryField(FormEvent $event): void
    {
        /** @var Post $post */
        $post = $event->getData();
        $form = $event->getForm();

        if (null !== $post->getId()) {
            $form->add('summary', TextareaType::class, [
                'label' => "Résumé: ",
                'attr' => [
                    'placeholder' => "Ajouter un résumé pour l'article"
                ],
                'priority' => 1
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'required' => false
        ]);
    }
}
