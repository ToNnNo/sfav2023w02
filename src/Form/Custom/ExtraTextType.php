<?php

namespace App\Form\Custom;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExtraTextType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['start_input'] = $options['start_input'];
        $view->vars['end_input'] = $options['end_input'];
        $view->vars['start_input_html'] = $options['start_input_html'];
        $view->vars['end_input_html'] = $options['end_input_html'];
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['start_input', 'end_input', 'start_input_html', 'end_input_html']);

        // $resolver->setRequired();

        $resolver->setAllowedTypes('start_input', ['string', 'null']);
        $resolver->setAllowedTypes('end_input', ['string', 'null']);
        $resolver->setAllowedTypes('start_input_html', 'bool');
        $resolver->setAllowedTypes('end_input_html', 'bool');

        $resolver->setDefaults([
            'start_input' => null,
            'end_input' => null,
            'start_input_html' => false,
            'end_input_html' => false,
        ]);

    }

    public function getParent(): string
    {
        return TextType::class;
    }
}
