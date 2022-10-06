<?php

namespace Pantheon\TwoFactorBundle\Form\Model;

interface TwoFactorCodeModelInterface
{
    public function getCode(): string;

    public function setCode(string $code): self;
}
