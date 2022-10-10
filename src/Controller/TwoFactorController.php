<?php

namespace Pantheon\TwoFactorBundle\Controller;

use App\Infrastructure\Security\AppAuthenticator;
use Pantheon\TwoFactorBundle\Form\Model\TwoFactorCodeModel;
use Pantheon\TwoFactorBundle\Form\Type\TwoFactorCodeType;
use Pantheon\TwoFactorBundle\Manager\TwoFactorManagerInterface;
use Pantheon\TwoFactorBundle\Service\User\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class TwoFactorController extends AbstractController
{
    private string $loginSuccessRoute;
    private TwoFactorManagerInterface $twoFactorManager;
    private Security $security;
    private AppAuthenticator $appAuthenticator;
    private UserAuthenticatorInterface $userAuthenticator;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        string $loginSuccessRoute,
        TwoFactorManagerInterface $twoFactorManager,
        Security $security,
        AppAuthenticator $appAuthenticator,
        UserAuthenticatorInterface $userAuthenticator,
        UserRepositoryInterface $userRepository
    ) {
        $this->loginSuccessRoute = $loginSuccessRoute;
        $this->twoFactorManager = $twoFactorManager;
        $this->security = $security;
        $this->appAuthenticator = $appAuthenticator;
        $this->userAuthenticator = $userAuthenticator;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/two-factor-authentication", name="two_factor_authentication")
     * @Template("@TwoFactor/authentication.html.twig")
     */
    public function authentication(Request $request)
    {
        $session = $request->getSession();
        $username = $session->get(Security::LAST_USERNAME);
        if (!$username) {
            throw new NotFoundHttpException('There is no last username is Session');
        }
        $user = $this->userRepository->getUser($username);
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }
        if (!$this->twoFactorManager->isTwoFactorAuthenticationAvailable()) {
            throw new NotFoundHttpException('Two factor authentication is not available');
        }
        if (!$this->twoFactorManager->isAuthenticatedPartially($user)) {
            throw new NotFoundHttpException('User is not authenticated partially');
        }
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
            if ($this->twoFactorManager->isCodeValid($code, $user)) {
                $this->twoFactorManager->setAutheticatedFully($user);
                return $this->userAuthenticator->authenticateUser($user, $this->appAuthenticator, $request);
            } else {
                $this->addFlash('error', 'Code is not correct.');
            }
        }
        return [
            'form' => $form->createView(),
            'user' => $user,
        ];
    }
}
