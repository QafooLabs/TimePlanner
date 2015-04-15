<?php

namespace Qafoo\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Qafoo\UserBundle\Form\DataTransformer\NameTransformer;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            $builder->create('name', 'text')->addModelTransformer(new NameTransformer())
        );
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }

    public function getName()
    {
        return 'qafoo_user_profile';
    }
}
