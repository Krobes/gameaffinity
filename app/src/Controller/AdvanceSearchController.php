<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\PersonalList;
use App\Entity\Platform;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/advance-search')]
class AdvanceSearchController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/', name: 'app_advance_search')]
    public function index(): Response
    {
        $platforms = $this->entityManager->getRepository(Platform::class)->findAll();
        $genres = $this->entityManager->getRepository(Genre::class)->findAll();
        $developers = $this->entityManager->getRepository(Developer::class)->findAll();

        return $this->render('advance_search/index.html.twig', [
            'platforms' => $platforms,
            'genres' => $genres,
            'developers' => $developers
        ]);
    }

    #[Route('/filtered-games', name: 'app_games_filtered')]
    public function filterGames(
        Request $request,
        Security $security,
        #[MapQueryParameter] string $title = '',
        #[MapQueryParameter] string $genre = '',
        #[MapQueryParameter] string $platform = '',
        #[MapQueryParameter] string $developer = '',
    ): Response {
        $filters = [];
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder->select('g')
            ->from(Game::class, 'g')
            ->leftJoin('g.genres', 'genre')
            ->leftJoin('g.platforms', 'platform')
            ->leftJoin('g.developer', 'developer');

        $filters['Title'] = $title;
        $filters['Genre'] = $genre;
        $filters['Platform'] = $platform;
        $filters['Developer'] = $developer;

        if (!empty($title) && strlen($title) > 2) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('g.name', ':title'))
                ->setParameter('title', '%' . $title . '%');
        }

        if (!empty($genre)) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('genre.name', ':genre'))
                ->setParameter('genre', $genre);

        }

        if (!empty($platform)) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('platform.name', ':platform'))
                ->setParameter('platform', $platform);

        }

        if (!empty($developer)) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('developer.name', ':developer'))
                ->setParameter('developer', $developer);
        }

        $queryBuilder->addOrderBy('g.date_release', 'DESC');

        $privateLists = $this->entityManager->getRepository(PersonalList::class)->findBy([
            'user' => $security->getUser(),
            'isPublic' => false
        ]);

        $publicLists = $this->entityManager->getRepository(PersonalList::class)->findBy([
            'user' => $security->getUser(),
            'isPublic' => true
        ]);

        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            12
        );
        return $this->render('advance_search/filtered-games.html.twig', [
            'pager' => $pagerfanta,
            'filters' => $filters,
            'privateLists' => $privateLists,
            'publicLists' => $publicLists
        ]);
    }
}
