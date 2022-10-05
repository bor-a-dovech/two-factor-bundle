<?php

namespace Pantheon\TwoFactorBundle;

use Pantheon\TwoFactorBundle\DependencyInjection\TwoFactorBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class TwoFactorBundle extends Bundle
{
    public function getContainerExtension() : TwoFactorBundleExtension
    {
        if ($this->extension === null) {
            $this->extension = new TwoFactorBundleExtension();
        }
        return $this->extension;
    }
}
