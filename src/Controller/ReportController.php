<?php

namespace App\Controller;

use App\PrUn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReportController extends AbstractController
{
    #[Route('/report/{region}/{month}/{year}', name: 'app_report_dashboard')]
    public function index(string $region, int $month = null, int $year = null): Response
    {
        if (!in_array($region, array_keys(PrUn::MARKETS))) {
            throw new BadRequestException('Invalid Region.');
        }

        $month = $month ?? (int)date('m');
        $year = $year ?? (int)date('Y');

        return $this->render('report/index.html.twig', [
            'region' => $region,
            'month' => $month,
            'year' => $year,
        ]);
    }
}
