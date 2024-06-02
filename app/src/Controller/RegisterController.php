<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\ClassGuardsLogin;
use App\Service\ClassGuardsParams;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RegisterController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ClassGuardsLogin $guardsLogin,
        private ClassGuardsParams $guardsParams,
        private UserPasswordHasherInterface $passwordHasher,
        private AuthenticationUtils $authenticationUtils
    ) {
    }

    /**
     * @throws \Exception
     */
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function index(Request $request, Session $session): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_index');
        }

        $params = $request->request->all();
        $this->guardsLogin->guardAgainstExistingNickOrEmail($params['nick'], $params['email']);
        $this->guardsParams->guardAgainstInvalidMail($params['email']);
        $this->guardsLogin->guardAgainstWeakPassword($params['firstPassword']);
        $this->guardsLogin->guardAgainstDifferentPasswords($params['firstPassword'], $params['secondPassword']);
        $lastUsername = $this->authenticationUtils->getLastUsername();
        if (empty($this->guardsLogin->errors)) {
            $newUser = new User();
            $newUser->setEmail($params['email']);
            $hashedPass = $this->passwordHasher->hashPassword(
                $newUser,
                $params['firstPassword']
            );
            $newUser->setPassword($hashedPass);
            $newUser->setNick($params['nick']);
            $newUser->setAvatar('/avatars/avatar' . rand(1, 6) . '.png');
            $this->entityManager->persist($newUser);
            $this->entityManager->flush();
            $session->getFlashBag()->add('success', 'You have successfully created the user!');
        }

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'errors' => $this->guardsLogin->errors,
        ]);
    }
}
