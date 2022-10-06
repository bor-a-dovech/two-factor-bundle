<?php

namespace Pantheon\TwoFactorBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

interface TwoFactorCodeTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options);
}
