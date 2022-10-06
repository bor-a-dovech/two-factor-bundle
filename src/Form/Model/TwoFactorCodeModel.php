<?php

namespace Pantheon\TwoFactorBundle\Form;

use Pantheon\TwoFactorBundle\Form\Model\TwoFactorCodeModelInterface;

class TwoFactorCodeModel implements TwoFactorCodeModelInterface
{
    private string $code;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }
}
