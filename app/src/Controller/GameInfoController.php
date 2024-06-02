<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\PersonalList;
use App\Entity\Score;
use App\Service\ClassGuardsParams;
use App\Service\IsRatedYet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game')]
class GameInfoController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private IsRatedYet $ratedYet,
        private ClassGuardsParams $guardsParams
    ) {
    }

    #[Route('/info/{id<\d+>}', name: 'app_game_show')]
    public function infoGame(int $id, Security $security): Response
    {
        $game = $this->entityManager->getRepository(Game::class)->find($id);
        $this->guardsParams->guardAgainstInvalidId($game);
        $privateLists = $this->entityManager->getRepository(PersonalList::class)->findBy([
            'user' => $security->getUser(),
            'isPublic' => false
        ]);
        $publicLists = $this->entityManager->getRepository(PersonalList::class)->findBy([
            'user' => $security->getUser(),
            'isPublic' => true
        ]);

        $averageScore = $this->entityManager->getRepository(Game::class)->getAverageScore($id);
        $votes = $this->entityManager->getRepository(Game::class)->getVotesGame($id);
        $userVote = $this->entityManager->getRepository(Score::class)->findBy([
            'user' => $security->getUser(),
            'game' => $game
        ]);

        return $this->render('game/index.html.twig', [
            'game' => $game,
            'publicLists' => $publicLists,
            'privateLists' => $privateLists,
            'avgScore' => $averageScore,
            'votes' => $votes,
            'userVote' => ($userVote) ? $userVote[0]->getScore() : null
        ]);
    }

    #[Route('/rate/{id<\d+>}', name: 'app_rate_game', methods: 'POST')]
    public function rateGame(
        int $id,
        Security $security,
        Request $request
    ): Response {
        $this->guardsParams->guardAgainstInvalidId($this->entityManager->getRepository(Game::class)->find($id));
        $newRate = $request->request->get('score');
        $this->guardsParams->guardAgainstInvalidRate($newRate);
        $score = $this->entityManager->getRepository(Score::class)->findBy([
            'user' => $security->getUser(),
            'game' => $id
        ]);

        $isRatedYet = $this->ratedYet->isRated($score);
        if ($isRatedYet) {
            $score[0]->setScore($newRate);
            $this->entityManager->persist($score[0]);
        } else {
            $newScore = new Score();
            $newScore->setScore($newRate);
            $newScore->setGame($this->entityManager->getRepository(Game::class)->find($id));
            $newScore->setUser($security->getUser());
            $this->entityManager->persist($newScore);
        }

        $this->entityManager->flush();

        return $this->redirectToRoute('app_game_show', [
            'id' => $id
        ]);
    }
}
