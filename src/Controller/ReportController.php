<?php

namespace App\Controller;

use App\PrUn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    '/report/{region}/{month}/{year}',
    name: 'app_report_',
    requirements: ['month' => '\d{2}', 'year' => '\d{4}'],
    defaults: ['month' => null, 'year' => null],
)]
final class ReportController extends AbstractController
{
    #[Route('', name: 'dashboard')]
    public function index(Request $request): Response
    {
        $this->validateRequest($request);
        return $this->render('report/dashboard.html.twig');
    }

    private function validateRequest(Request $request): void
    {
        $region = $request->attributes->get('region');
        if (!in_array($region, array_keys(PrUn::MARKETS))) {
            throw new BadRequestException('Invalid Region.');
        }
    }
}
