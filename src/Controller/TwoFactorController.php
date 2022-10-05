<?php

namespace Pantheon\TwoFactorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TwoFactorController extends AbstractController
{
    /**
     * @Route("/twofactor", name="twofactor_test")
     */
    public function twofactor(): Response
    {
        return new Response('ok');
    }

    /**
     * @Route("/two-factor-authentication", name="two_factor_authentication")
     * @Template("two_factor/authentication.html.twig")
     */
    public function authentication(Request $request)
    {
//        /** @var User $user */
//        $user = $this->security->getUser();

        $lastUserName = $request->getSession()->get(Security::LAST_USERNAME);
        $user = $this->userRepository->findOneBy(['username' => $lastUserName]);

//        if (!$user) {
//            throw new NotFoundHttpException('not logged in');
//        }
        if (!$this->twoFactorManager->isTwoFactorAuthenticationEnabled()) {
            throw new NotFoundHttpException('two factor authentication is not enabled');
        }
//        if (!$this->twoFactorManager->isAuthenticatedPartially($user)) {
//            throw new NotFoundHttpException('user is not authenticated partially');
//        }
        $codeModel = new TwoFactorCodeModel();
        $form = $this->createForm(TwoFactorCodeType::class, $codeModel,  ['csrf_protection' => false]);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            $this->twoFactorManager->sendCode($user);
        }
        if ($form->isSubmitted() and $form->isValid()) {
            /** @var TwoFactorCodeModel $codeModel */
            $codeModel = $form->getData();
            $code = $codeModel->getCode();
            if ($this->twoFactorManager->isCodeValid($user, $code)) {
                $this->twoFactorManager->setAutheticatedFully($user);


                return $this->authenticator->authenticateUser($user, $this->appAuthenticator, $request);



                return $this->redirectToRoute('front');
            } else {
                $this->addFlash('error', 'Code is not correct.');
            }
        }
        return [
            'form' => $form->createView(),
            'message' => null,
            'user' => $user,
        ];
    }

}
