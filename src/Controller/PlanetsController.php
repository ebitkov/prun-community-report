<?php

namespace App\Controller;

use App\Entity\Planet;
use App\FIO\Client;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PlanetsController extends AbstractController
{
    #[Route('/planets', name: 'app_planets')]
    public function index(): Response
    {
        return $this->render('planet/index.html.twig');
    }

    #[Route('/planet/{naturalId}', name: 'app_planet_details')]
    public function details(#[MapEntity(mapping: ['naturalId' => 'naturalId'])] Planet $planet): Response
    {
        # $latestPOPR = $this->fio->getCsvInfrastructureReport($planet->getNaturalId())->last();
        # $sites = $this->fio->getPlanetSites($naturalId);

        return $this->render('planet/details.html.twig', [
            'planet' => $planet
        ]);
    }
}