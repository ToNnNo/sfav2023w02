<?php

namespace App\Form;

use App\Transient\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom: ',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom: ',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email: '
            ])
            ->add('body', TextareaType::class, [
                'label' => 'Message: '
            ])
            ->add('CGU', CheckboxType::class, [
                'label' => "Accepter les conditions générales d'utilisation",
                'mapped' => false,
                'constraints' => [
                    new IsTrue()
                ]
            ])
        ;

        // DataMapper
        $builder->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'required' => false
        ]);
    }

    public function mapDataToForms($viewData, \Traversable $forms)
    {
        if( null === $viewData ) {
            return;
        }

        if( !$viewData instanceof Contact ) {
            throw new UnexpectedTypeException($viewData, Contact::class);
        }

        [$firstname, $lastname] = explode(" ", $viewData->getFullname());

        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $forms['firstname']->setData($firstname);
        $forms['lastname']->setData($lastname);
        $forms['email']->setData($viewData->getEmail());
        $forms['body']->setData($viewData->getBody());
    }

    public function mapFormsToData(\Traversable $forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $viewData = (new Contact())
            ->setFullname($forms['firstname']->getData() . " " . $forms['lastname']->getData())
            ->setEmail($forms['email']->getData())
            ->setBody($forms['body']->getData())
        ;
    }
}
