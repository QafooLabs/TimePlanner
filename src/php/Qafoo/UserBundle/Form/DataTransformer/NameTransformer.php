<?php

namespace Qafoo\UserBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

use Qafoo\UserBundle\Domain\Name;

class NameTransformer implements DataTransformerInterface
{
    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param Name|null $name
     * @return string
     */
    public function transform($name)
    {
        return (string) $name;
    }

    /**
     * Transforms a string to a Name object
     *
     * @param  string $name
     * @return Name
     */
    public function reverseTransform($name)
    {
        return Name::createFromName($name);
    }
}
