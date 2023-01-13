<?php

namespace App\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagsViewTransformer implements DataTransformerInterface
{

    public function __construct(/**/) // appel de service ici !
    {
    }

    /**
     * @inheritDoc
     */
    public function transform($arrayToString): string
    {
        return implode(", ", $arrayToString);
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($stringToArray): array
    {
        return array_map('trim', explode(",", $stringToArray));
    }
}
