<?php

namespace App\Controller;

use App\Entity\PersonalList;
use App\Entity\Score;
use App\Entity\User;
use App\Service\ClassGuardsParams;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ClassGuardsParams $guardsParams
    ) {
    }

    #[Route('/', name: 'app_profile')]
    public function index(
        Security $security
    ): Response {
        $privateListsUser = $this->entityManager->getRepository(PersonalList::class)->findBy([
            'user' => $security->getUser(),
            'isPublic' => false
        ]);

        $publicListsUser = $this->entityManager->getRepository(PersonalList::class)->findBy([
            'user' => $security->getUser(),
            'isPublic' => true
        ]);

        $votedGames = $this->entityManager->getRepository(Score::class)->findBy([
            'user' => $security->getUser(),
        ]);

        return $this->render('profile/index.html.twig', [
            'privateLists' => $privateListsUser,
            'publicLists' => $publicListsUser,
            'votedGames' => count($votedGames)
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/submit', name: 'app_profile_submit')]
    public function submitProfile(
        Security $security,
        Request $request,
        Session $session
    ): Response {
        if ($security->getUser()->getNick() != $request->request->get('nick')) {
            $user = $this->entityManager->getRepository(User::class)->findBy([
                'nick' => $request->request->get('nick')
            ]);
            $this->guardsParams->guardAgainstExistingNick($user);
        }

        if ($security->getUser()->getEmail() != $request->request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)->findBy([
                'email' => $request->request->get('email')
            ]);
            $this->guardsParams->guardAgainstExistingEmail($user);
        }

        $this->guardsParams->guardAgainstInvalidParam($request->request->get('nick'));
        $this->guardsParams->guardAgainstInvalidParam($request->request->get('email'));
        $this->guardsParams->guardAgainstInvalidMail($request->request->get('email'));
        $this->guardsParams->guardAgainstInvalidPhone($request->request->get('phone'));
        $this->guardsParams->guardAgainstLongParam($request->request->get('name'));
        $this->guardsParams->guardAgainstLongParam($request->request->get('surname'));
        $this->guardsParams->guardAgainstLongParam($request->request->get('favouriteGame'));
        $this->guardsParams->guardAgainstInvalidAvatar($request->request->get('selectedAvatar'));

        if (empty($this->guardsParams->errors)) {
            $user = $this->entityManager->getRepository(User::class)->find($security->getUser()->getId());
            $user->setEmail($request->request->get('email'));
            $user->setNick($request->request->get('nick'));
            $user->setName($request->request->get('name'));
            $user->setSurname($request->request->get('surname'));
            $user->setPhone($request->request->get('phone'));
            $user->setFavouriteGame($request->request->get('favouriteGame'));
            if ($request->request->get('selectedAvatar') != null) {
                $user->setAvatar($request->request->get('selectedAvatar'));
            }
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $session->getFlashBag()->add('success', 'The profile was edited successfully.');
        } else {
            foreach ($this->guardsParams->errors as $error) {
                $session->getFlashBag()->add('error', $error);
            }
        }

        return $this->redirectToRoute('app_profile');
    }
}
