<?php

namespace App\Controller;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ranking')]
class RankingController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/', name: 'app_ranking')]
    public function index(): Response
    {
        $topRatedGames = $this->entityManager->getRepository(Game::class)->getTopRatedGames();
        return $this->render('ranking/index.html.twig', [
            'topRatedGames' => $topRatedGames,
        ]);
    }
}
