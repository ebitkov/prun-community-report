<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $today = new \DateTime();
        return $this->redirectToRoute('app_report_dashboard', [
            'region' => 'antares',
            'year' => $today->format('Y'),
            'month' => $today->format('n'),
        ]);
    }
}
