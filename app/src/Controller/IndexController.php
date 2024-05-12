<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Platform;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
class IndexController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $nextGames = $this->entityManager->getRepository(Game::class)->findNextReleases(9);
        $lastGames = $this->entityManager->getRepository(Game::class)->findLastReleases(9);
        $platforms = $this->entityManager->getRepository(Platform::class)->findByName([
            'Xbox Series X|S',
            'Nintendo Switch',
            'PlayStation 5',
            'PC (Microsoft Windows)'
        ]);

        return $this->render('index.html.twig', [
            'nextGames' => $nextGames,
            'lastGames' => $lastGames,
            'platforms' => $platforms
        ]);
    }
}
