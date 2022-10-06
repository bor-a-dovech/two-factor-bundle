<?php

namespace Pantheon\TwoFactorBundle\Form\Type;

use Pantheon\TwoFactorBundle\Form\Type\TwoFactorCodeTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TwoFactorCodeType extends AbstractType
{
    /**
    * @param FormBuilderInterface $builder
    * @param array $options
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('code', TextType::class, [
            'required' => true,
            'attr' => [
                'placeholder' => 'Code',
            ],
        ]);
        $builder->add('submit', SubmitType::class, [
            'label' => 'Log in to system',
            'attr' => ['class' => 'button button-solid button-primary mt-12 w-100'],
        ]);
    }
}
