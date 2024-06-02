<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\PersonalList;
use App\Service\ClassGuardsAccessControl;
use App\Service\ClassGuardsParams;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/list')]
class ListController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ClassGuardsParams $guardsParams,
        private ClassGuardsAccessControl $guardsAccessControl
    ) {
    }

    #[Route('/create', name: 'app_create_list')]
    public function creatingList(
        Request $request,
        Security $security,
        Session $session
    ): Response {
        $this->guardsParams->guardAgainstInvalidParam($request->request->get('listName'));
        $this->guardsParams->guardAgainstLongParam($request->request->get('listName'));
        $user = $security->getUser();
        $visibility = $request->request->get('visibility') === 'true';
        $numList = $this->entityManager->getRepository(PersonalList::class)->findBy([
            'user' => $user,
            'isPublic' => $visibility
        ]);
        $this->guardsParams->guardAgainstMaxList($numList, $request->request->get('isPublic'));
        if (empty($this->guardsParams->errors)) {
            $newList = new PersonalList();
            $newList->setName($request->request->get('listName'));
            $newList->setUser($security->getUser());
            $newList->setPublic($visibility);
            $this->entityManager->persist($newList);
            $this->entityManager->flush();
            $session->getFlashBag()->add('success', 'The list was created successfully.');
        } else {
            foreach ($this->guardsParams->errors as $error) {
                $session->getFlashBag()->add('error', $error);
            }
        }

        return $this->redirectToRoute('app_profile');

    }

    #[Route('/add-game/{id<\d+>}', name: 'app_add_game_list')]
    public function addGameList(
        Request $request,
        Session $session,
        Security $security,
        int $id
    ): Response {
        $this->guardsParams->guardAgainstInvalidId($this->entityManager->getRepository(PersonalList::class)->find($id));
        $referer = $request->headers->get('referer');
        $listsId = $request->request->all('selectedLists');
        $this->guardsParams->guardAgainstInvalidParam($listsId);
        $game = $this->entityManager->getRepository(Game::class)->find($id);
        $this->guardsParams->guardAgainstNotExistingGame($game);
        foreach ($listsId as $listId) {
            $list = $this->entityManager->getRepository(PersonalList::class)->findBy([
                'id' => $listId,
                'user' => $security->getUser()
            ]);
            $this->guardsParams->guardAgainstExistingList($list);
            $this->guardsParams->guardAgainstMaxGamesInList($list[0]->getGames());
        }

        if (empty($this->guardsParams->errors)) {
            foreach ($listsId as $listId) {
                $list = $this->entityManager->getRepository(PersonalList::class)->find($listId);
                $list->addGame($game);
                $this->entityManager->persist($list);
            }
            $this->entityManager->flush();
            $session->getFlashBag()->add('success', 'The game was added successfully.');
        } else {
            foreach ($this->guardsParams->errors as $error) {
                $session->getFlashBag()->add('error', $error);
            }
        }

        if ($referer) {
            return new RedirectResponse($referer);
        }

        return $this->redirectToRoute('app_games_filtered');
    }

    /**
     * @throws \Exception
     */
    #[Route('/remove-game/{id<\d+>}', name: 'app_remove_game_list')]
    public function removeGameList(
        Request $request,
        Session $session,
        int $id
    ): Response {
        $this->guardsParams->guardAgainstInvalidId($this->entityManager->getRepository(PersonalList::class)->find($id));
        $idGame = $request->request->get('gameRemove');
        $gameToRemove = $this->entityManager->getRepository(Game::class)->find($idGame);
        $this->guardsParams->guardAgainstNotExistingGame($gameToRemove);
        if (empty($this->guardsParams->errors)) {
            $listToRemoveGame = $this->entityManager->getRepository(PersonalList::class)->find($id);
            $listToRemoveGame->removeGame($gameToRemove);
            $this->entityManager->persist($listToRemoveGame);
            $this->entityManager->flush();
            $session->getFlashBag()->add('success', 'The game was removed successfully.');
        } else {
            foreach ($this->guardsParams->errors as $error) {
                $session->getFlashBag()->add('error', $error);
            }
        }

        return $this->redirectToRoute('app_profile');
    }

    /**
     * @throws \Exception
     */
    #[Route('/delete/{id<\d+>}', name: 'app_delete_list')]
    public function deleteList(
        Request $request,
        int $id
    ): Response {
        $this->guardsParams->guardAgainstInvalidId($this->entityManager->getRepository(PersonalList::class)->find($id));
        $listToDelete = $this->entityManager->getRepository(PersonalList::class)->find($id);
        if ($this->isCsrfTokenValid('delete' . $listToDelete->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($listToDelete);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_profile');
    }

    #[Route('/public', name: 'app_public_lists')]
    public function publicList(
        Request $request,
    ): Response {
        $queryBuilder = $this->entityManager->getRepository(PersonalList::class)->createQueryBuilder('pl')
            ->where('pl.isPublic = :isPublic')
            ->setParameter('isPublic', true);

        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            9
        );


        return $this->render('/lists/publicList.html.twig', [
            'publicLists' => $pagerfanta
        ]);
    }
}
