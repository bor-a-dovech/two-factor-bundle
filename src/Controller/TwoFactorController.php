<?php

namespace Pantheon\TwoFactorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TwoFactorController extends AbstractController
{
    /**
     * @Route("/twofactor", name="twofactor_test")
     */
    public function twofactor()
    {
    }
}
